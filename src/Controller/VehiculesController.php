<?php

namespace Controller;

use Repository\VehiculesRepository;
use Entity\Vehicule;

class VehiculesController
{
    private VehiculesRepository $repo;

    public function __construct(VehiculesRepository $repo)
    {
        $this->repo = $repo;
    }

    public function ajouter(): void
    {
        if (empty($_SESSION['user'])) {
            header('Location: index.php?entity=utilisateurs&action=se_connecter');
            exit;
        }

        $message = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // ✅ Création de l’objet Vehicule à partir du POST
            $vehicule = new Vehicule(
                $_SESSION['user']->getIdUtilisateur(),
                $_POST['marque'] ?? null, // <-- correspond au <select name="marque">
                $_POST['modele'] ?? '',
                $_POST['couleur'] ?? '',
                $_POST['energie'] ?? '',
                $_POST['immatriculation'] ?? '',
                $_POST['date_immatriculation'] ?? '',
                (int)($_POST['nb_places'] ?? 0)
            );


            // ✅ Appel du repository avec un objet, pas un array
            if ($this->repo->create($vehicule)) {
                $message = "✅ Véhicule ajouté avec succès !";
            } else {
                $message = "❌ Erreur lors de l'ajout du véhicule.";
            }
        }

        // ✅ Récupération des marques pour le formulaire
        $marques = $this->repo->getAllMarques();

        require __DIR__ . '/../View/vehicules/ajouter_vehicule.php';
    }
}
