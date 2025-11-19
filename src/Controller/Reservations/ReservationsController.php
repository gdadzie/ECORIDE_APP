<?php
namespace Controller\Reservations;

use Controller\Credits\CreditsController;
use Controller\Covoiturages\CovoituragesController;
use Repository\ReservationRepository;
use Entity\Reservation;
use Entity\Utilisateur;
use Entity\Covoiturage;

class ReservationsController
{
    private CreditsController $creditsController;
    private ReservationRepository $reservationsRepo;
    private CovoituragesController $covoituragesController;

    public function __construct(\PDO $conn, $reservationsRepo, $covoituragesController)
    {
        $this->creditsController = new \Controller\Credits\CreditsController($conn);
        $this->reservationsRepo = $reservationsRepo;
        $this->covoituragesController = $covoituragesController;
    }

    public function reserver(Utilisateur $user, Covoiturage $covoiturage): void
    {
        if (!$user) {
            header('Location: /login.php');
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
                        $this->covoituragesController->updatePlacesAndStatut(
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
