<?php
namespace Controller\Covoiturages;

use Repository\CovoituragesRepository;

class CovoituragesReservationController
{
    private CovoituragesRepository $repo;

    public function __construct(CovoituragesRepository $repo)
    {
        $this->repo = $repo;
    }

    public function reserverCovoiturage(): void
    {
        header('Content-Type: application/json; charset=utf-8');

        $userId = $_SESSION['user_id'] ?? null;
        $idCovoiturage = (int) ($_POST['id_covoiturage'] ?? 0);

        if (!$userId || $idCovoiturage <= 0) {
            echo json_encode(['success' => false, 'message' => 'Utilisateur non connecté ou covoiturage invalide.']);
            return;
        }

        $covoiturage = $this->repo->getCovoiturageById($idCovoiturage);
        if (!$covoiturage) {
            echo json_encode(['success' => false, 'message' => 'Covoiturage introuvable.']);
            return;
        }

        if ((int) $covoiturage['nb_places'] <= 0) {
            echo json_encode(['success' => false, 'message' => 'Plus de places disponibles.']);
            return;
        }

        $this->repo->updateStatutCovoiturage($idCovoiturage, 'réservé');
        echo json_encode(['success' => true, 'message' => 'Covoiturage réservé avec succès !']);
    }
}
