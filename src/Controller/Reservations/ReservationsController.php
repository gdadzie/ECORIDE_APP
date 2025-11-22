<?php
namespace Controller\Reservations;

use Controller\Credits\CreditsController;
use Controller\Covoiturages\CovoituragesDisplayController;
use Repository\AvisRepository;
use Repository\CreditsRepository;
use Repository\MarquesRepository;
use Repository\ReservationRepository;
use Repository\CovoituragesRepository;

use Entity\Reservation;
use Entity\Utilisateur;
use Entity\Covoiturage;
use Repository\UtilisateursRepository;
use Repository\VehiculesRepository;
use Service\CreditsService;
use Service\ReservationService;

class ReservationsController
{
    private CreditsController $creditsController;
    private ReservationRepository $reservationsRepo;
    private CovoituragesDisplayController $covoituragesDisplayController;

    public function __construct(\PDO $conn, $reservationsRepo, $covoituragesDisplayController)
    {
        // Instanciation des repositories
        $covoituragesRepo   = new CovoituragesRepository($conn);
        $utilisateursRepo   = new UtilisateursRepository($conn);
        $avisRepo           = new AvisRepository($conn);
        $creditsRepo        = new CreditsRepository($conn);
        $vehiculesRepo      = new VehiculesRepository($conn);
        $reservationsRepo   = new ReservationRepository($conn);
        $marquesRepo        = new MarquesRepository($conn);

        // Instanciation des services
        $creditsService     = new CreditsService($creditsRepo);
        $reservationService = new ReservationService($reservationsRepo);

        // Instanciation du contrôleur
        $this->covoituragesDisplayController = new CovoituragesDisplayController(
            $covoituragesRepo,
            $utilisateursRepo,
            $avisRepo,
            $creditsService,
            $reservationService,
            $reservationsRepo,
            $vehiculesRepo,
            $marquesRepo,
        );

        $this->reservationsRepo = $reservationsRepo;

        // CreditsController existant
        $this->creditsController = new CreditsController($conn);
    }
    public function reserver(Utilisateur $user, Covoiturage $covoiturage): void
    {
        if (!$user) {
            // Enregistre la page actuelle pour y revenir après login
            session_start();
            $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
            header('Location: index.php?entity=accueil&action=connexion');
            exit;
        }


        if (!$covoiturage) {
            echo "Covoiturage introuvable.";
            exit;
        }

        $coutCredits = $covoiturage->getPrix();
        $nbPlacesRestantes = $covoiturage->getNbPlaces();
        $creditsUser = $this->creditsController->getCredits($user);

        $errorMessage = '';
        $successMessage = '';
        $confirmNeeded = false;

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['participer'])) {

            // Vérification crédits et places
            if ($nbPlacesRestantes <= 0) {
                $errorMessage = "Plus de place disponible pour ce covoiturage.";
            } elseif ($creditsUser < $coutCredits) {
                $errorMessage = "Crédits insuffisants pour réserver ce covoiturage.";
            } else {
                if (isset($_POST['confirm']) && $_POST['confirm'] === 'oui') {
                    try {
                        // Commence une transaction
                        $this->reservationsRepo->getConn()->beginTransaction();

                        // Débiter crédits
                        $this->creditsController->updateCredits($user, $creditsUser - $coutCredits);

                        // Décrémenter places
                        $this->covoituragesDisplayController->updatePlacesAndStatut(
                            $covoiturage,
                            $nbPlacesRestantes - 1,
                            'en cours'
                        );

                        // Enregistrer réservation
                        $reservation = new Reservation();
                        $reservation->setIdUtilisateur($user->getIdUtilisateur());
                        $reservation->setIdCovoiturage($covoiturage->getId());
                        $reservation->setDateReservation((new \DateTime())->format('Y-m-d H:i:s'));
                        $reservation->setStatut('en cours');
                        $reservation->setConfirmation($coutCredits);

                        $this->reservationsRepo->save($reservation);

                        $this->reservationsRepo->getConn()->commit();

                        $successMessage = "Votre réservation a été confirmée ! $coutCredits crédits ont été débités.";

                    } catch (\Exception $e) {
                        $this->reservationsRepo->getConn()->rollBack();
                        $errorMessage = "Erreur lors de la réservation : " . $e->getMessage();
                    }

                } else {
                    // Première étape de confirmation
                    $confirmNeeded = true;
                }
            }
        }

        include __DIR__ . '/../../View/reservations/test.php';
    }
}
