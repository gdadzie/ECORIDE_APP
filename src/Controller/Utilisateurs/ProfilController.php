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
        if (!$userId) header('Location: index.php?entity=accueil&action=se_connecter');

        $utilisateur = $this->repo->findById($userId);
        $message = '';
        $success = false;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $utilisateur->setNom($_POST['nom'] ?? '');
            $utilisateur->setPrenom($_POST['prenom'] ?? '');
            $utilisateur->setTelephone($_POST['telephone'] ?? '');
            $utilisateur->setTypeUtilisateur($_POST['type_utilisateur'] ?? 'passager');

            if ($this->repo->updateUtilisateur($utilisateur)) {
                $_SESSION['user']->setNom($utilisateur->getNom());
                $_SESSION['user']->setPrenom($utilisateur->getPrenom());
                $_SESSION['user']->setTelephone($utilisateur->getTelephone());
                $_SESSION['user']->setTypeUtilisateur($utilisateur->getTypeUtilisateur());
                $success = true;
                $message = "✅ Profil mis à jour avec succès !";
            }
        }

        require __DIR__ . '/../../View/utilisateurs/profil/mise_a_jour_profil.php';
    }
}
