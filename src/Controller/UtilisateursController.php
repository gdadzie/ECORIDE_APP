<?php
namespace Controller;

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../Entity/Utilisateur.php';
require_once __DIR__ . '/../Repository/UtilisateursRepository.php';
require_once __DIR__ . '/../../Config/Database.php';

use Config\Database;
use Entity\Utilisateur;
use Repository\UtilisateursRepository;

class UtilisateursController
{
    private UtilisateursRepository $repo;

    public function __construct()
    {
        $pdo = Database::getConnection();
        $this->repo = new UtilisateursRepository($pdo);
    }

    //------ SE CONNECTER ------//
    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];
            $mdp = $_POST['mdp'];

            $user = $this->repo->verifyLogin($email, $mdp);

            if ($user) {
                $_SESSION['user_id'] = $user->getIdUtilisateur();
                $_SESSION['user_name'] = $user->getPseudo();

                header('Location: index.php?page=tableau_de_bord');
                exit;
            } else {
                $error = "Email ou mot de passe incorrect.";
                include __DIR__ . '/../view/utilisateurs/connection.php';
            }
        } else {
            include __DIR__ . '/../view/utilisateurs/connection.php';
        }
    }


    // ---------------------------
    // Affiche la liste des utilisateurs
    // ---------------------------
    public function index(): void
    {
        $users = $this->repo->findAll();
        require __DIR__ . '/../View/accueil/index.php';
    }

    // ---------------------------
    // Affiche le formulaire d'inscription (visiteur)
    // ---------------------------
    public function register(): void
    {
        $error = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                if (empty($_POST['pseudo']) || empty($_POST['email']) || empty($_POST['mot_de_passe'])) {
                    throw new \Exception("Tous les champs obligatoires doivent être remplis.");
                }
                if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                    throw new \Exception("Adresse e-mail invalide.");
                }

                $user = new Utilisateur();
                $user->setPseudo($_POST['pseudo']);
                $user->setEmail($_POST['email']);
                $user->setMdp(password_hash($_POST['mot_de_passe'], PASSWORD_BCRYPT));
                $user->setNom($_POST['nom'] ?? '');
                $user->setPrenom($_POST['prenom'] ?? '');
                $user->setTelephone($_POST['telephone'] ?? '');
                $user->setRole($_POST['role'] ?? 'user');
                $user->setTypeCovoiturage($_POST['type_covoiturage'] ?? 'passager');
                $user->setActif(1);
                $user->setPhoto('');

                $this->repo->save($user);

                header('Location: index.php?action=index&success=1');
                exit;

            } catch (\Exception $e) {
                $error = $e->getMessage();
            }
        }

        require __DIR__ . '/../View/utilisateurs/connection.php';
    }

    // ---------------------------
    // Affiche le formulaire de création (admin)
    // ---------------------------
    public function create(): void
    {
        $error = '';
        require __DIR__ . '/../View/utilisateurs/creer_utilisateur.php';
    }

    // ---------------------------
    // Enregistre un nouvel utilisateur (admin)
    // ---------------------------


    // ---------------------------
    // Affiche le formulaire de modification
    // ---------------------------

    // ---------------------------
    // Met à jour un utilisateur
    // ---------------------------
    public function update(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)($_POST['id_utilisateur'] ?? 0);
            $user = $this->repo->find($id);

            if (!$user) {
                header('Location: index.php?action=index&error=Utilisateur introuvable');
                exit;
            }

            $user->setNom($_POST['nom']);
            $user->setPrenom($_POST['prenom']);
            $user->setEmail($_POST['email']);
            $user->setTelephone($_POST['telephone']);
            $user->setPseudo($_POST['pseudo']);
            $user->setRole($_POST['role']);
            $user->setTypeCovoiturage($_POST['type_covoiturage']);
            $user->setActif(isset($_POST['actif']) ? 1 : 0);

            $this->repo->update($user);

            header('Location: index.php?action=index&success=2');
            exit;
        }
    }

    // ---------------------------
    // Supprime un utilisateur
    // ---------------------------
    public function destroy(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_utilisateur'])) {
            $id = (int)$_POST['id_utilisateur'];
            $user = $this->repo->find($id);

            if ($user) {
                $this->repo->delete($user->getIdUtilisateur());
                header('Location: index.php?action=index&success=3');
                exit;
            } else {
                header('Location: index.php?action=index&error=Utilisateur introuvable');
                exit;
            }
        }

        // Si pas de POST, afficher la page de suppression
        $users = $this->repo->findAll();
        require __DIR__ . '/../View/utilisateurs/supprimer_utilisateur.php';
    }
}
