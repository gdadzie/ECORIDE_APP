<?php
namespace Controller\Covoiturages;

use Controller\Credits\CreditsController;
use DateTime;
use Entity\Covoiturage;
use Entity\Marque;
use Entity\Reservation;
use Entity\Vehicule;
use JetBrains\PhpStorm\NoReturn;
use Repository\CovoituragesRepository;
use Repository\CreditsRepository;
use Repository\MarquesRepository;
use Repository\ReservationRepository;
use Repository\UtilisateursRepository;
use Repository\AvisRepository;
use Repository\VehiculesRepository;
use Service\CreditsService;
use Service\ReservationService;

class CovoituragesDisplayController
{
    private CovoituragesRepository $covoituragesRepo;
    private CreditsService $creditsService;
    private ReservationService $reservationService;
    private UtilisateursRepository $utilisateursRepo;
    private AvisRepository $avisRepo;
    private ?VehiculesRepository $vehiculesRepo;
    private ?Covoiturage $covoiturage;
    private ?Vehicule $vehicule;
    private ReservationRepository $reservationsRepo;
    private MarquesRepository $marquesRepo;

    /**
     * Constructeur
     * On passe tous les repository et services dont on aura besoin
     * C'est un peu long mais pratique pour tout utiliser après sans recréer des objets partout
     */
    public function __construct(
        CovoituragesRepository $covoituragesRepo,
        UtilisateursRepository $utilisateursRepo,
        AvisRepository $avisRepo,
        CreditsService $creditsService,
        ReservationService $reservationService,
        ReservationRepository $reservationsRepo,
        VehiculesRepository $vehiculesRepo,
        MarquesRepository $marquesRepo,
    ) {
        $this->covoituragesRepo   = $covoituragesRepo;
        $this->utilisateursRepo   = $utilisateursRepo;
        $this->avisRepo           = $avisRepo;
        $this->creditsService     = $creditsService;
        $this->reservationService = $reservationService;
        $this->reservationsRepo   = $reservationsRepo;
        $this->vehiculesRepo      = $vehiculesRepo;
        $this->marquesRepo        = $marquesRepo;
    }



    /**
     * Affiche les détails d’un covoiturage et gère la réservation
     *
     * J'affiche toutes les infos utiles :
     * - conducteur, horaires, villes
     * - crédits de l'utilisateur
     * - places restantes
     *
     * Et je gère le formulaire de réservation si soumis
     */
    public function showDetails(): void
    {
        // ────────────────────────────────
        // 1️⃣ Récupération de l'ID du covoiturage
        // ────────────────────────────────
        $id = intval($_GET['id'] ?? 0);

        // Valeurs par défaut pour la vue
        $errorMessage = '';
        $successMessage = '';
        $confirmNeeded = false;

        //VEHICULES
        $vehiculeNom = '-';
        $vehiculeModele = '-';
        $vehiculeEnergie = '-';

        //VILLES
        $villeDepart = '-';
        $villeArrivee = '-';

        //COVOITURAGES
        $affichageDate = '-';
        $heureDepart = '-';
        $heureArrivee = '-';
        $duree = 0;
        $distance = 0;
        $prix = 0;
        $nbPlaces = 0;
        $preferences = ['fumeur' => false, 'animaux' => false];
        $ecologique = false;

        //CONDUCTEUR
        $pseudoConducteur = '-';
        $avatarConducteur = 'assets/img/default-avatar.png';
        $noteUtilisateur = 0;


        // ────────────────────────────────
        // 2️⃣ Vérification ID
        // ────────────────────────────────
        if (!$id) {
            $errorMessage = "Covoiturage introuvable.";
            require __DIR__ . '/../../View/covoiturages/detail_covoiturage.php';
            return;
        }


        // ────────────────────────────────
        // 3️⃣ Récupération du covoiturage
        // ────────────────────────────────
        $covoiturage = $this->covoituragesRepo->findById($id);

        if (!$covoiturage) {
            $errorMessage = "Covoiturage introuvable.";
            require __DIR__ . '/../../View/covoiturages/detail_covoiturage.php';
            return;
        }


        // ────────────────────────────────
        // 4️⃣ Hydratation des infos trajets
        // ────────────────────────────────
        $villeDepart   = $covoiturage->getVilleDepartNom()      ?? '-';
        $villeArrivee  = $covoiturage->getVilleArriveeNom()     ?? '-';

        $affichageDate = $covoiturage->getDateDepartFormatee()  ?? '-';
        $heureDepart   = $covoiturage->getHeureDepart()         ?? '-';
        $heureArrivee  = $covoiturage->getHeureArrivee()        ?? '-';

        $duree     = $covoiturage->getDureeMinutes()            ?? 0;
        $distance  = $covoiturage->getDistanceKm()              ?? 0;
        $prix      = $covoiturage->getPrix()                    ?? 0;
        $nbPlaces  = $covoiturage->getNbPlaces()                ?? 0;

        $ecologique = $covoiturage->isEcologique();
        $preferences = [
            'fumeur'  => $covoiturage->getFumeur() ?? false,
            'animaux' => $covoiturage->getAnimaux() ?? false,
        ];


        // ────────────────────────────────
        // 5️⃣ Informations véhicule
        // ────────────────────────────────
        $idVehicule = $covoiturage->getIdVehicule();
        $vehicule   = $idVehicule ? $this->vehiculesRepo->getEntityById($idVehicule) : null;

        $idMarque = $vehicule?->getIdMarque();
        $marque = $idMarque ? $this->marquesRepo->find($idMarque): null;



        if ($vehicule) {
            $vehiculeModele = $vehicule->getModele() ?? '-';

            // Récupération marque

            $vehiculeNom = $marque?->getNomMarque() ?? '-';



        }

        // Énergie
        $vehiculeEnergie = $covoiturage->getVehiculeEnergie() ?? '-';


        // ────────────────────────────────
        // 6️⃣ Informations conducteur
        // ────────────────────────────────
        $idConducteur = $covoiturage->getIdUtilisateur();
        $conducteur   = $this->utilisateursRepo->findById($idConducteur);

        if ($conducteur) {
            $pseudoConducteur = $conducteur->getPseudo();
            $avatarConducteur = $conducteur->getPhoto();
            $noteUtilisateur  = $conducteur->getNote();
        }


        // ────────────────────────────────
        // 7️⃣ Utilisateur connecté et gestion participation
        // ────────────────────────────────
        $userConnecte = $_SESSION['user'] ?? null;
        $creditsUser  = $userConnecte ? $this->creditsService->getCredits($userConnecte) : 0;

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['participer'])) {

            if (!$userConnecte) {
                header('Location: index.php?entity=accueil&action=connexion');
                exit;
            }

            $nbPlacesRestantes = $nbPlaces;
            $coutCredits = $prix;

            if ($creditsUser < $coutCredits) {
                $errorMessage = "Crédits insuffisants pour réserver ce covoiturage.";
            } elseif ($nbPlacesRestantes <= 0) {
                $errorMessage = "Plus de place disponible.";
            } else {
                if (isset($_POST['confirm']) && $_POST['confirm'] === 'oui') {

                    // Débit des crédits
                    $this->creditsService->updateCredits($userConnecte, $creditsUser - $coutCredits);

                    // Mise à jour des places
                    $this->covoituragesRepo->updatePlacesAndStatut(
                        $covoiturage->getIdCovoiturage(),
                        $nbPlacesRestantes - 1,
                        'en cours'
                    );

                    // Enregistrement réservation
                    $this->reservationService->createReservation($userConnecte, $covoiturage, $coutCredits);

                    $successMessage = "Votre réservation a été confirmée !";
                    $nbPlacesRestantes--;

                } else {
                    $confirmNeeded = true;
                }
            }
        }


        // ────────────────────────────────
        // 8️⃣ Chargement de la vue
        // ────────────────────────────────
        require __DIR__ . '/../../View/covoiturages/detail_covoiturage.php';
    }






    /**
     * Liste des covoiturages de l’utilisateur
     */
    public function mesCovoiturages(): void
    {
        $userId = $_SESSION['user_id'] ?? null;

        // Si pas connecté, redirige vers connexion
        if (!$userId) {
            header('Location: index.php?entity=accueil&action=connexion');
            exit;
        }

        // Récupération de l'utilisateur
        $user = $this->utilisateursRepo->findById($userId);
        if (!$user) {
            session_destroy(); // On détruit la session si l'utilisateur n'existe plus
            header('Location: index.php?entity=accueil&action=connexion');
            exit;
        }

        // Récupération de tous les covoiturages
        $covoiturages = $this->covoituragesRepo->getCovoituragesByUtilisateur($userId);

        // Pour chaque covoiturage, calcul heure d'arrivée et infos véhicule
        foreach ($covoiturages as $c) {
            // Calcul heure d'arrivée
            $c->calculerHeureArrivee();

            $vehicule = $c->getVehicule();
            if ($vehicule) {
                // Utiliser le nom de la marque directement depuis le covoiturage
                $c->setVehiculeNom($c->getVehiculeNom() ?? '—');
                $c->setVehiculeModele($vehicule->getModele() ?? '—');
            } else {
                $c->setVehiculeNom('—');
                $c->setVehiculeModele('—');
            }
        }

        // Affichage de la vue
        require __DIR__ . '/../../View/covoiturages/mes_covoiturages.php';
    }


    /**
     * Supprime un covoiturage
     * ⚠️ Attention : vérifie que l'utilisateur est bien propriétaire
     */
    #[NoReturn]
    public function supprimer(): void
    {
        $userId = $_SESSION['user_id'] ?? null;
        $covoiturageId = (int)($_GET['id'] ?? 0);

        // Vérification de l'identité utilisateur + ID covoiturage valide
        if (!$userId || $covoiturageId <= 0) {
            header('Location: index.php?entity=accueil&action=connexion');
            exit;
        }

        // 1. Récupérer le covoiturage
        $covoiturage = $this->covoituragesRepo->findById($covoiturageId);

        // 2. Vérifier que le covoiturage existe ET qu'il appartient à l'utilisateur
        if (!$covoiturage || $covoiturage->getIdUtilisateur() != $userId) {
            header('Location: index.php?entity=covoiturages&action=mes_covoiturages');
            exit;
        }

        // 3. Supprimer
        $this->covoituragesRepo->delete($covoiturageId);

        // 4. Redirection
        header('Location: index.php?entity=covoiturages&action=mes_covoiturages');
        exit;
    }


    /**
     * Recherche de covoiturages avec filtres souples
     */
    public function rechercher(): void
    {
        $villeDepart = $_GET['ville_depart'] ?? '';
        $villeArrivee = $_GET['ville_arrivee'] ?? '';
        $dateDepart = $_GET['date_depart'] ?? null;

        $covoiturages = $this->covoituragesRepo->rechercherCovoiturages($villeDepart, $villeArrivee, $dateDepart);
        require __DIR__ . '/../../View/covoiturages/liste_covoiturages.php';
    }

    /**
     * Calcul simple de l'heure d'arrivée
     * - Heure départ + durée en minutes
     */
    private function calculerHeureArrivee(string $heureDepart, int $dureeMinutes): string
    {
        if (empty($heureDepart) || $dureeMinutes <= 0) return '00:00:00';
        $depart = new DateTime($heureDepart);
        $depart->modify("+{$dureeMinutes} minutes");
        return $depart->format("H:i:s");
    }

    /**
     * Met à jour le nombre de places et le statut du covoiturage
     */
    public function updatePlacesAndStatut(Covoiturage $covoiturage, int $nbPlaces, string $statut): bool
    {
        return $this->covoituragesRepo->updatePlacesAndStatut(
            $covoiturage,
            $nbPlaces,
            $statut
        );
    }

    /**
     * Détail covoiturage (ancienne méthode / alternative)
     * Gère aussi la réservation directe
     */
    public function detailCovoiturage(int $id): void
    {
        session_start();
        $userId = $_SESSION['user_id'] ?? null;
        $covoiturage = $this->covoituragesRepo->findById($id);

        if (!$covoiturage) {
            header('Location: index.php?entity=covoiturages&action=mes_covoiturages');
            exit;
        }

        $errorMessage = '';
        $successMessage = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['participer'])) {
            if (!$userId) {
                header('Location: index.php?entity=accueil&action=connexion');
                exit;
            }

            $user = $this->utilisateursRepo->findById($userId);
            $creditsUser = $user->getCredit() ?? 0; // récupération crédits
            $coutCredits = $covoiturage->getPrix() ?? 1;
            $nbPlacesRestantes = $covoiturage->getNbPlaces() ?? 0;

            if ($creditsUser < $coutCredits) {
                $errorMessage = "Crédits insuffisants pour réserver ce covoiturage.";
            } elseif ($nbPlacesRestantes <= 0) {
                $errorMessage = "Plus de place disponible pour ce trajet.";
            } else {
                // Débit des crédits
                $user->setCredit($creditsUser - $coutCredits);
                $this->utilisateursRepo->updateUtilisateur($user); // mise à jour

                // Mise à jour du covoiturage
                $covoiturage->setNbPlaces($nbPlacesRestantes - 1);
                $this->covoituragesRepo->update($covoiturage); // update

                // Création de la réservation
                $reservation = new \Entity\Reservation();
                $reservation->setIdUtilisateur($userId);
                $reservation->setIdCovoiturage($id);
                $reservation->setDateReservation((new \DateTime())->format('Y-m-d H:i:s'));
                $reservation->setStatut('en cours');
                $reservation->setConfirmation($coutCredits);
                $this->covoituragesRepo->update($reservation); // save

                $successMessage = "✅ Réservation confirmée ! $coutCredits crédits ont été débités.";
            }
        }

        require_once __DIR__ . '/../../View/covoiturages/detail_covoiturage.php';
    }


    /**
     * 🔹 Valide un covoiturage
     *
     * Cette fonction permet au conducteur d’un covoiturage de valider que le trajet a bien eu lieu.
     * Concrètement, elle fait plusieurs trucs :
     * - Vérifie que l’utilisateur est connecté
     * - Vérifie que l’utilisateur est bien le conducteur du covoiturage
     * - Change le statut du covoiturage en "validé" ou "terminé"
     * - Optionnel : pourrait déclencher des notifications ou mise à jour des crédits pour les participants
     *
     * Ça sert à ce que le conducteur puisse confirmer la fin du trajet et que tout soit bien enregistré
     * dans la base. Genre, c’est la dernière étape après la réservation et la participation.
     */

    #[NoReturn]
    public function valider(): void
    {
        // Récupération de l'ID utilisateur et de l'ID du covoiturage
        $userId = $_SESSION['user_id'] ?? null;
        $covoiturageId = (int)($_GET['id'] ?? 0);

        // Vérification basique
        if (!$userId || $covoiturageId <= 0) {
            header('Location: index.php?entity=accueil&action=connexion');
            exit;
        }

        // Récupération du covoiturage
        $covoiturage = $this->covoituragesRepo->getEntityById($covoiturageId);
        if (!$covoiturage) {
            header('Location: index.php?entity=covoiturages&action=mes_covoiturages');
            exit;
        }

        // Vérification que c'est bien le conducteur qui valide
        if ($covoiturage->getConducteur()->getIdUtilisateur() != $userId) {
            header('Location: index.php?entity=covoiturages&action=mes_covoiturages');
            exit;
        }

        // Mise à jour du statut
        $this->updatePlacesAndStatut($covoiturage, $covoiturage->getNbPlaces(), 'terminé');

        // Redirection avec succès
        header('Location: index.php?entity=covoiturages&action=mes_covoiturages&msg=valide');
        exit;
    }

// =====================================
    // Valide la participation au covoiturage
    // - Débite les crédits de l'utilisateur
    // - Réduit le nombre de places disponibles
    // - Crée une réservation
    // =====================================
    public function validity($userId, $covoiturageId) {
        $user = $this->utilisateursRepo->findById($userId);
        $covoiturage = $this->covoituragesRepo->findById($covoiturageId);

        if (!$user || !$covoiturage) {
            return ['error' => "Utilisateur ou covoiturage introuvable."];
        }

        $creditsUser = $user->getCredits();
        $prix = $covoiturage->getPrix();
        $nbPlacesRestantes = $covoiturage->getNbPlaces();

        if ($creditsUser < $prix) {
            return ['error' => "Crédits insuffisants pour réserver ce covoiturage."];
        }

        if ($nbPlacesRestantes <= 0) {
            return ['error' => "Plus de place disponible pour ce trajet."];
        }

        // Débit des crédits
        $user->setCredits($creditsUser - $prix);
        $this->utilisateursRepo->update($user);

        // Mise à jour du covoiturage
        $covoiturage->setNbPlaces($nbPlacesRestantes - 1);
        $this->covoituragesRepo->update($covoiturage);

        // Création de la réservation
        $reservation = new \Entity\Reservation();
        $reservation->setIdUtilisateur($userId);
        $reservation->setIdCovoiturage($covoiturageId);
        $reservation->setDateReservation((new DateTime())->format('Y-m-d H:i:s'));
        $reservation->setStatut('en cours');
        $this->reservationsRepo->save($reservation);

        return ['success' => "✅ Réservation confirmée ! $prix crédits ont été débités."];
    }

    public function participer(int $userId, int $covoiturageId): array
    {
        // ⚡ On récupère le covoiturage
        $covoiturage = $this->covoituragesRepo->getEntityById($covoiturageId);
        if (!$covoiturage) {
            return ['error' => 'Covoiturage introuvable.'];
        }

        // ⚡ Vérification utilisateur
        $user = $this->utilisateursRepo->findById($userId);
        if (!$user) {
            return ['error' => 'Utilisateur non trouvé.'];
        }

        $creditsUser = $this->creditsService->getCredits($user);
        $coutCredits = $covoiturage->getPrix() ?? 1;
        $nbPlacesRestantes = $covoiturage->getNbPlaces() ?? 0;

        // ⚡ Vérifications des conditions
        if ($creditsUser < $coutCredits) {
            return ['error' => 'Crédits insuffisants pour réserver ce covoiturage.'];
        }
        if ($nbPlacesRestantes <= 0) {
            return ['error' => 'Plus de place disponible pour ce trajet.'];
        }

        // ⚡ Débit des crédits
        $this->creditsService->updateCredits($user, $creditsUser - $coutCredits);

        // ⚡ Mise à jour du covoiturage
        $this->updatePlacesAndStatut($covoiturage, $nbPlacesRestantes - 1, 'en cours');

        // ⚡ Création de la réservation
        $this->reservationService->createReservation($user, $covoiturage, $coutCredits);

        return ['success' => "✅ Réservation confirmée ! $coutCredits crédits ont été débités."];
    }


    //Fonction qui modifie un covoiturage
    public function modifierCovoiturage(): void
    {
        // Récupération de l'ID depuis l'URL
        $id = intval($_GET['id'] ?? 0);
        if (!$id) {
            echo "Covoiturage introuvable.";
            return;
        }

        // Récupération du covoiturage
        $covoiturage = $this->covoituragesRepo->getEntityById($id);
        if (!$covoiturage) {
            echo "Covoiturage introuvable.";
            return;
        }

        // Vérification utilisateur
        $userId = $_SESSION['user_id'] ?? null;
        if (!$userId || $covoiturage->getIdUtilisateur() !== $userId) {
            echo "Vous n'êtes pas autorisé à modifier ce covoiturage.";
            return;
        }

        $errorMessage = '';
        $successMessage = '';

        // Si le formulaire est soumis
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Récupération des données du formulaire
            $villeDepart = $_POST['ville_depart'] ?? $covoiturage->getVilleDepart();
            $villeArrivee = $_POST['ville_arrivee'] ?? $covoiturage->getVilleArrivee();
            $dateDepart = $_POST['date_depart'] ?? $covoiturage->getDateDepart();
            $heureDepart = $_POST['heure_depart'] ?? $covoiturage->getHeureDepart();
            $dureeMinutes = (int)($_POST['duree_minutes'] ?? $covoiturage->getDureeMinutes());
            $prix = (float)($_POST['prix'] ?? $covoiturage->getPrix());
            $nbPlaces = (int)($_POST['nb_places'] ?? $covoiturage->getNbPlaces());
            $description = $_POST['description'] ?? $covoiturage->getDescription();

            // Mise à jour de l'objet
            $covoiturage->setVilleDepart($villeDepart);
            $covoiturage->setVilleArrivee($villeArrivee);
            $covoiturage->setDateDepart($dateDepart);
            $covoiturage->setHeureDepart($heureDepart);
            $covoiturage->setDureeMinutes($dureeMinutes);
            $covoiturage->setPrix($prix);
            $covoiturage->setNbPlaces($nbPlaces);
            $covoiturage->setDescription($description);

            // Vérification de validité
            if (!$covoiturage->validate()) {
                $errorMessage = "Les informations fournies sont invalides.";
            } else {
                // Mise à jour dans la base via le repository
                $this->covoituragesRepo->update($covoiturage); // <-- ta méthode update doit exister ici
                $successMessage = "Covoiturage modifié avec succès !";
            }
        }

        // Affichage de la vue (formulaire pré-rempli)
        require __DIR__ . '/../../View/covoiturages/modifier_covoiturage.php';
    }






}
