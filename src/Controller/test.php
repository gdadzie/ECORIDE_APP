<?php
namespace Controller\Covoiturages;

use Controller\Credits\CreditsController;
use DateTime;
use Entity\Covoiturage;
use Entity\Reservation;
use Entity\Vehicule;
use JetBrains\PhpStorm\NoReturn;
use Repository\CovoituragesRepository;
use Repository\CreditsRepository;
use Repository\ReservationRepository;
use Repository\UtilisateursRepository;
use Repository\AvisRepository;
use Repository\VehiculesRepository;

class CovoituragesDisplayController
{
    private CovoituragesRepository $covoituragesRepo;
    private CreditsController $creditsController;
    private ReservationRepository $reservationsRepo;
    private UtilisateursRepository $utilisateursRepo;
    private AvisRepository $avisRepo;
    private ?VehiculesRepository $vehiculesRepo;
    private ?Covoiturage $covoiturage;
    private ?Vehicule $vehicule;

    public function __construct(
        CovoituragesRepository $covoituragesRepo,
        UtilisateursRepository $utilisateursRepo,
        AvisRepository $avisRepo,
        ReservationRepository $reservationsRepo,
        CreditsController $creditsController,
        ?CreditsRepository $creditsRepo = null,
        ?VehiculesRepository $vehiculesRepo = null,
        ?Covoiturage $covoiturage = null,
        ?Vehicule $vehicule = null
    ) {
        $this->covoituragesRepo = $covoituragesRepo;
        $this->utilisateursRepo = $utilisateursRepo;
        $this->avisRepo = $avisRepo;
        $this->reservationsRepo = $reservationsRepo;
        $this->creditsController = $creditsController;
        $this->vehiculesRepo = $vehiculesRepo;
        $this->covoiturage = $covoiturage;
        $this->vehicule = $vehicule;
    }

    // 🔍 Affichage des résultats d’une recherche
    public function resultatsRecherche(): void
    {
        $depart = $_GET['depart'] ?? null;
        $arrivee = $_GET['arrivee'] ?? null;
        $date = $_GET['date'] ?? null;

        $covoiturages = $this->covoituragesRepo->rechercherCovoiturages($depart, $arrivee, $date);

        require __DIR__ . '/../../View/accueil/resultats_recherches_covoiturages.php';
    }

    // Détails d’un covoiturage
    public function showDetails(): void
    {
        $dateStr = $_GET['date'] ?? null;
        $id = intval($_GET['id'] ?? 0);

        if (!$id) {
            echo "<p class='text-center text-danger'>Covoiturage introuvable.</p>";
            return;
        }

        $covoiturage = $this->covoituragesRepo->getEntityById($id);
        if (!$covoiturage) {
            echo "<p class='text-center text-danger'>Covoiturage introuvable.</p>";
            return;
        }

        $villeDepart = $covoiturage->getVilleDepartNom() ?? '-';
        $villeArrivee = $covoiturage->getVilleArriveeNom() ?? '-';
        $heureArrivee = $covoiturage->calculerHeureArrivee();

        $conducteur = $covoiturage->getConducteur();
        $nbAvis = $conducteur ? $this->avisRepo->getNbAvisByUtilisateur($conducteur->getIdUtilisateur()) : 0;
        $noteMoyenne = $conducteur ? $this->avisRepo->getNoteMoyenneByUtilisateur($conducteur->getIdUtilisateur()) : 0;

        $user = $_SESSION['user'] ?? null;
        $creditsUser = $user ? $this->creditsController->getCredits($user) : 0.0;

        $nbPlacesRestantes = $covoiturage->getNbPlaces() ?? 0;
        $coutCredits = $covoiturage->getPrix() ?? 1;

        $errorMessage = '';
        $successMessage = '';
        $confirmNeeded = false;

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['participer'])) {
            if (!$user) {
                header('Location: index.php?entity=accueil&action=connexion');
                exit;
            }

            if ($creditsUser < $coutCredits) {
                $errorMessage = "Crédits insuffisants pour réserver ce covoiturage.";
            } elseif ($nbPlacesRestantes <= 0) {
                $errorMessage = "Plus de place disponible pour ce trajet.";
            } else {
                if (isset($_POST['confirm']) && $_POST['confirm'] === 'oui') {
                    $this->creditsController->updateCredits($user, $creditsUser - $coutCredits);
                    $this->updatePlacesAndStatut($covoiturage, $nbPlacesRestantes - 1, 'en cours');

                    $reservation = new Reservation();
                    $reservation->setIdUtilisateur($user->getIdUtilisateur());
                    $reservation->setIdCovoiturage($covoiturage->getIdCovoiturage());
                    $reservation->setDateReservation((new DateTime())->format('Y-m-d H:i:s'));
                    $reservation->setStatut('en cours');
                    $reservation->setConfirmation($coutCredits);
                    $this->reservationsRepo->save($reservation);

                    $successMessage = "Votre réservation a été confirmée ! $coutCredits crédits ont été débités.";
                    $nbPlacesRestantes--;
                } else {
                    $confirmNeeded = true;
                }
            }
        }

        $avatar = $conducteur ? $conducteur->getPhoto() : null;
        $pseudoConducteur = $conducteur ? $conducteur->getPseudo() : 'Inconnu';
        $ecologique = $covoiturage->isEcologique();

        require __DIR__ . '/../../View/covoiturages/detail_covoiturage.php';
    }

    // Liste des covoiturages de l’utilisateur
    public function mesCovoiturages(): void
    {
        $userId = $_SESSION['user_id'] ?? null;
        if (!$userId) {
            header('Location: index.php?entity=accueil&action=connexion');
            exit;
        }

        $user = $this->utilisateursRepo->findById($userId);
        if (!$user) {
            session_destroy();
            header('Location: index.php?entity=accueil&action=connexion');
            exit;
        }

        $covoiturages = $this->covoituragesRepo->getEntityById($userId);
        foreach ($covoiturages as $c) {
            $c->calculerHeureArrivee();

            $vehiculeId = $c->getIdVehicule();
            $vehicule = $vehiculeId && $this->vehiculesRepo ? $this->vehiculesRepo->getEntityById($vehiculeId) : null;

            $c->setVehiculeNom($vehicule ? $vehicule->getNomMarque() : '—');
            $c->setVehiculeModele($vehicule ? $vehicule->getModele() : '—');
        }

        require __DIR__ . '/../../View/covoiturages/mes_covoiturages.php';
    }

    #[NoReturn]
    public function supprimer(): void
    {
        $userId = $_SESSION['user_id'] ?? null;
        $covoiturageId = (int)($_GET['id'] ?? 0);

        if (!$userId || $covoiturageId <= 0) {
            header('Location: index.php?entity=accueil&action=connexion');
            exit;
        }

        $covoiturage = $this->covoituragesRepo->getEntityById($covoiturageId);
        if (!$covoiturage || $covoiturage->getIdUtilisateur() != $userId) {
            header('Location: index.php?entity=covoiturages&action=mes_covoiturages');
            exit;
        }

        $this->covoituragesRepo->delete($covoiturageId);
        header('Location: index.php?entity=covoiturages&action=mes_covoiturages');
        exit;
    }

    // Recherche souple
    public function rechercher(): void
    {
        $villeDepart = $_GET['ville_depart'] ?? '';
        $villeArrivee = $_GET['ville_arrivee'] ?? '';
        $dateDepart = $_GET['date_depart'] ?? null;

        $covoiturages = $this->covoituragesRepo->rechercherCovoiturages($villeDepart, $villeArrivee, $dateDepart);
        require __DIR__ . '/../../View/covoiturages/liste_covoiturages.php';
    }

    private function calculerHeureArrivee(string $heureDepart, int $dureeMinutes): string
    {
        if (empty($heureDepart) || $dureeMinutes <= 0) return '00:00:00';
        $depart = new DateTime($heureDepart);
        $depart->modify("+{$dureeMinutes} minutes");
        return $depart->format("H:i:s");
    }

    public function updatePlacesAndStatut(Covoiturage $covoiturage, int $nbPlaces, string $statut): bool
    {
        // Appelle directement le repository avec l'objet
        return $this->covoituragesRepo->updatePlacesAndStatut(
            $covoiturage,
            $nbPlaces,
            $statut
        );
    }

}
