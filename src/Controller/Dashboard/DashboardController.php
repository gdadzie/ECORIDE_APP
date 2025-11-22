<?php
namespace Controller\Dashboard;

use Config\Database;
use Repository\UtilisateursRepository;

class DashboardController
{
    private UtilisateursRepository $repo;

    public function __construct(UtilisateursRepository $repo)
    {
        $this->repo = $repo;
    }

    public function dashboard(): void
    {
        if (empty($_SESSION['user'])) {
            header('Location: index.php?entity=utilisateurs&action=login');
            exit;
        }

        $user = $this->repo->findById($_SESSION['user_id']);

        $publicDir = realpath(__DIR__ . '/../../public') . DIRECTORY_SEPARATOR;
        $userPhoto = ltrim($user->getPhoto(), '/');
        $fullPath = $publicDir . $userPhoto;
        $photoPathWeb = (!empty($userPhoto) && file_exists($fullPath))
            ? '/' . $userPhoto
            : '/uploads/photos/default-avatar.jpg';

        // Définir pseudo et rôle pour la vue
        $userPseudo = $user->getPseudo() ?? 'Utilisateur';
        $userRole   = $user->getRole() ?? 0;

        $_SESSION['user'] = $user;

        require __DIR__ . '/../../View/dashboard/tableau_de_bord.php';
    }


    public function charteGraphique(): void
    {
        require __DIR__ . '/../../View/utilisateurs/charte_graphique.php';
    }
}
