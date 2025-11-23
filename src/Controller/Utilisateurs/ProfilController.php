<?php
namespace Controller\Utilisateurs;

use Repository\UtilisateursRepository;

class ProfilController
{
    private UtilisateursRepository $repo;

    public function __construct(UtilisateursRepository $repo)
    {
        $this->repo = $repo;
    }

    public function profilUser(): void
    {

        if (empty($_SESSION['user'])) {
            header('Location: index.php?entity=utilisateurs&action=login');
            exit;
        }

        $user = $_SESSION['user'];
        $message = '';
        $success = false;

        require __DIR__ . '/../../View/utilisateurs/profil_utilisateur.php';
    }

    public function updateProfilUtilisateur(): void
    {
        $userId = $_SESSION['user_id'] ?? null;
        if (!$userId) {
            header('Location: index.php?entity=accueil&action=se_connecter');
            exit;
        }

        $utilisateur = $this->repo->findById($userId);
        $message = '';
        $success = false;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // === Champs indépendants ===
            if (!empty($_POST['nom'])) $utilisateur->setNom($_POST['nom']);
            if (!empty($_POST['prenom'])) $utilisateur->setPrenom($_POST['prenom']);
            if (!empty($_POST['telephone'])) $utilisateur->setTelephone($_POST['telephone']);
            if (!empty($_POST['type_utilisateur'])) $utilisateur->setTypeUtilisateur($_POST['type_utilisateur']);

            // === Upload photo ===
            if (!empty($_FILES['photo']['name'])) {

                $allowed = ['jpg', 'jpeg', 'png', 'webp'];
                $ext = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));

                if (!in_array($ext, $allowed)) {
                    $message = "❌ Format d’image non autorisé.";
                } else {
                    $uploadDir = __DIR__ . '/../../public/uploads/photos/';
                    if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

                    $fileName = uniqid('photo_') . '.' . $ext;
                    $uploadPath = $uploadDir . $fileName;

                    if (move_uploaded_file($_FILES['photo']['tmp_name'], $uploadPath)) {
                        // Supprimer ancienne photo si existante et pas default-avatar
                        $oldPhoto = $utilisateur->getPhoto();
                        if ($oldPhoto && !str_contains($oldPhoto, 'default-avatar.jpg')) {
                            $oldPath = __DIR__ . '/../../public' . $oldPhoto;
                            if (file_exists($oldPath)) unlink($oldPath);
                        }
                        $utilisateur->setPhoto('/uploads/photos/' . $fileName);
                    } else {
                        $message = "❌ Erreur lors du téléchargement de l’image.";
                    }
                }
            }

            // === Sauvegarde ===
            if ($this->repo->updateUtilisateur($utilisateur)) {
                // Mise à jour session
                $_SESSION['user']->setNom($utilisateur->getNom());
                $_SESSION['user']->setPrenom($utilisateur->getPrenom());
                $_SESSION['user']->setTelephone($utilisateur->getTelephone());
                $_SESSION['user']->setPhoto($utilisateur->getPhoto());
                $_SESSION['user']->setTypeUtilisateur($utilisateur->getTypeUtilisateur());

                $success = true;
                if (!$message) $message = "✅ Profil mis à jour avec succès !";
            } else {
                if (!$message) $message = "❌ Échec de la mise à jour.";
            }
        }

        require __DIR__ . '/../../View/utilisateurs/profil/mise_a_jour_profil.php';
    }


    public function updateTypeUtilisateur(): void
    {
        $userId = $_SESSION['user_id'] ?? null;
        if (!$userId) exit;

        $utilisateur = $this->repo->findById($userId);

        $type = $_POST['type_utilisateur'] ?? null;

        if ($type) {
            $utilisateur->setTypeUtilisateur($type);
            $this->repo->updateTypeUtilisateur($utilisateur);
            $_SESSION['user']->setTypeUtilisateur($type);
        }

        // Si conducteur ou passager+conducteur
        if ($type === 'conducteur' || $type === 'PC') {
            // Véhicules
            if (!empty($_POST['vehicule'])) {
                foreach ($_POST['vehicule'] as $vehiculeData) {
                    $this->vehiculeRepo->addVehicule($userId, $vehiculeData);
                }
            }

            // Préférences fixes
            $prefFixes = $_POST['preferences_fixes'] ?? [];
            foreach ($prefFixes as $pref) {
                $this->prefRepo->addPreference($userId, $pref);
            }

            // Préférences personnalisées
            $prefCustom = $_POST['pref_custom'] ?? [];
            foreach ($prefCustom as $pref) {
                $this->prefRepo->addPreference($userId, $pref);
            }
        }

        header('Location: index.php?entity=utilisateurs&action=profil');
        exit;
    }



}
