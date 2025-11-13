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

        $db = Database::getConnection();
        $user = $this->repo->findById($_SESSION['user_id']);

        $_SESSION['user'] = $user;

        $publicDir = realpath(__DIR__ . '/../../public') . DIRECTORY_SEPARATOR;
        $userPhoto = ltrim($user->getPhoto(), '/');
        $fullPath = $publicDir . $userPhoto;
        $photoPathWeb = (!empty($userPhoto) && file_exists($fullPath))
            ? '/' . $userPhoto
            : '/uploads/photos/default-avatar.jpg';

        require __DIR__ . '/../../View/dashboard/tableau_de_bord.php';
    }

    public function charteGraphique(): void
    {
        require __DIR__ . '/../../View/utilisateurs/charte_graphique.php';
    }
}
