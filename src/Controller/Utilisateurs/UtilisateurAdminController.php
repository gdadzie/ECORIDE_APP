<?php
namespace Controller\Utilisateurs;

use Repository\UtilisateursRepository;

class UtilisateurAdminController
{
    private UtilisateursRepository $repo;

    public function __construct(UtilisateursRepository $repo)
    {
        $this->repo = $repo;
    }

    public function liste(): void
    {

        if (empty($_SESSION['user'])) {
            header('Location: index.php?entity=utilisateurs&action=login');
            exit;
        }

        $searchEmail = trim($_GET['email'] ?? '');
        $utilisateurs = $searchEmail !== '' ? $this->repo->findByEmail($searchEmail) : $this->repo->findAll();

        $utilisateurs = $utilisateurs ?? [];

        require_once __DIR__ . '/../../View/utilisateurs/index.php';
    }

    public function supprimer(): void
    {

        if (empty($_SESSION['user']) || (int)$_SESSION['user']['role'] !== 2) {
            http_response_code(403);
            echo "<h2 style='color:red;text-align:center;margin-top:50px;'>⚠️ Accès refusé</h2>";
            exit;
        }

        $id = (int)($_GET['id'] ?? 0);
        if ($id > 0 && $this->repo->delete($id)) {
            header('Location: index.php?entity=utilisateurs&action=liste_utilisateurs&success=1');
        } else {
            header('Location: index.php?entity=utilisateurs&action=liste_utilisateurs&error=1');
        }
        exit;
    }

    public function espaceAdmin(): void
    {
        require __DIR__ . '/../../View/utilisateurs/admin/espace_admin.php';
    }

    public function espaceEmploye(): void
    {
        require __DIR__ . '/../../View/utilisateurs/employe/espace_employe.php';
    }
}
