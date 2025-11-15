<?php
namespace Controller\Vehicules;

use JetBrains\PhpStorm\NoReturn;
use Repository\VehiculesRepository;

class VehiculesController
{
    private VehiculesRepository $repo;

    public function __construct(VehiculesRepository $repo = null)
    {
        $this->repo = $repo ?? new VehiculesRepository();
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    // ================= Afficher tous les véhicules de l'utilisateur =================
    public function showVehicule(): void
    {
        $idUtilisateur = $_SESSION['user_id'] ?? null;
        if ($idUtilisateur === null) {
            header('Location: index.php?entity=users&action=login');
            exit;
        }

        $vehicules = $this->repo->getVehiculesByUtilisateur($idUtilisateur);
        require_once __DIR__ . '/../../View/vehicules/liste_vehicules.php';
    }

    // ================= Ajouter un véhicule =================
    public function ajouter(): void
    {
        $idUtilisateur = $_SESSION['user_id'] ?? null;
        if ($idUtilisateur === null) {
            header('Location: index.php?entity=users&action=login');
            exit;
        }

        $message = '';
        $success = false;

        $couleurs = ['Noir','Blanc','Gris','Rouge','Bleu','Vert','Jaune','Autre'];
        $energies = ['Essence','Diesel','Électrique','Hybride','GPL','Autre'];
        $marques = $this->repo->getAllMarques();

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_vehicle'])) {
            $data = [
                'id_utilisateur' => $idUtilisateur,
                'id_marque' => (int)($_POST['id_marque'] ?? 0),
                'modele' => trim($_POST['modele'] ?? ''),
                'couleur' => $_POST['couleur'] ?? '',
                'energie' => $_POST['energie'] ?? '',
                'nb_places' => isset($_POST['nb_places']) ? (int)$_POST['nb_places'] : null,
                'immatriculation' => trim($_POST['immatriculation'] ?? ''),
                'date_premiere_immatriculation' => $_POST['date_premiere_immatriculation'] ?? ''
            ];

            if (empty($data['id_marque']) || empty($data['modele']) || empty($data['couleur']) ||
                empty($data['energie']) || empty($data['immatriculation']) || empty($data['date_premiere_immatriculation'])) {
                $message = "❌ Veuillez remplir tous les champs obligatoires.";
            } else {
                if ($this->repo->addVehicule($data)) {
                    $success = true;
                    $message = "✅ Véhicule <strong>" . htmlspecialchars($data['modele']) . "</strong> ajouté avec succès !";
                } else {
                    $message = "❌ Erreur lors de l’ajout du véhicule.";
                }
            }
        }

        $vehicules = $this->repo->getVehiculesByUtilisateur($idUtilisateur);
        require_once __DIR__ . '/../../View/vehicules/ajouter_vehicule.php';
    }

    // ================= Supprimer un véhicule =================
    #[NoReturn]
    public function delete(): void
    {
        $idUtilisateur = $_SESSION['user_id'] ?? null;
        if ($idUtilisateur === null) {
            header('Location: index.php?entity=users&action=login');
            exit;
        }

        if (!isset($_GET['id_vehicule'])) {
            header('Location: index.php?entity=vehicules&action=liste_vehicules');
            exit;
        }

        $idVehicule = (int)$_GET['id_vehicule'];
        $deleted = $this->repo->deleteVehiculeByUtilisateur($idVehicule, $idUtilisateur);

        header('Location: index.php?entity=vehicules&action=liste_vehicules&deleted=' . ($deleted ? 1 : 0));
        exit;
    }

    public function deleteMultiple(): void {
        if (empty($_GET['ids'])) {
            header('Location: index.php?entity=vehicules&action=liste_vehicules&deleted=0');
            exit;
        }

        $idUtilisateur = $_SESSION['user_id'] ?? null;
        $deletedAll = true;

        foreach ($_GET['ids'] as $idVehicule) {
            $deleted = $this->repo->deleteVehiculeByUtilisateur((int)$idVehicule, $idUtilisateur);
            if (!$deleted) $deletedAll = false;
        }

        header('Location: index.php?entity=vehicules&action=liste_vehicules&deleted=' . ($deletedAll ? 1 : 0));
        exit;
    }

}
