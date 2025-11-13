<?php
namespace Controller\Api;

use Service\CovoituragesService;

class CovoituragesApiController
{
    private CovoituragesService $service;

    public function __construct(CovoituragesService $service)
    {
        $this->service = $service;
    }

    // Recherche JSON
    public function recherche(): void
    {
        header('Content-Type: application/json; charset=utf-8');
        $villeDepart = trim($_POST['ville_depart'] ?? '');
        $villeArrivee = trim($_POST['ville_arrivee'] ?? '');
        $dateDepart = $_POST['date_depart'] ?? null;

        if (empty($villeDepart) && empty($villeArrivee)) {
            echo json_encode(['success'=>false,'message'=>'Veuillez saisir au moins une ville de départ ou d’arrivée.']);
            return;
        }

        $covoiturages = $this->service->rechercherSouple($villeDepart, $villeArrivee, $dateDepart);
        $data = array_map(fn($c) => [
            'id'=>$c['id_covoiturage'],
            'ville_depart_nom'=>$c['ville_depart_nom'],
            'ville_arrivee_nom'=>$c['ville_arrivee_nom'],
            'date_depart'=>$c['date_depart'],
            'heure_depart'=>$c['heure_depart'] ?? '—',
            'nb_places'=>$c['nb_places'],
            'prix'=>$c['prix'],
            'ecologique'=>(bool)$c['ecologique'],
            'duree_minutes'=>$c['duree_minutes'],
            'statut'=>$c['statut'],
            'pseudo'=>$c['pseudo'] ?? null
        ], $covoiturages);

        echo json_encode(['success'=>true,'count'=>count($covoiturages),'data'=>$data]);
    }

    // Réservation AJAX
    public function reserver(): void
    {
        header('Content-Type: application/json; charset=utf-8');
        $userId = $_SESSION['user_id'] ?? null;
        $idCovoiturage = (int)($_POST['id_covoiturage'] ?? 0);

        if (!$userId || $idCovoiturage <= 0) {
            echo json_encode(['success'=>false,'message'=>'Utilisateur non connecté ou covoiturage invalide.']);
            return;
        }

        $success = $this->service->reserver($idCovoiturage);
        echo json_encode($success
            ? ['success'=>true,'message'=>'Covoiturage réservé avec succès !']
            : ['success'=>false,'message'=>'Plus de places disponibles ou covoiturage introuvable.']);
    }
}
