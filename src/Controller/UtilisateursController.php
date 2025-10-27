<?php
namespace Controller;

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../Entity/Utilisateur.php';
require_once __DIR__ . '/../Repository/UtilisateursRepository.php';
require_once __DIR__ . '/../../Config/Database.php';

use Repository\UtilisateursRepository;
use Entity\Utilisateur;
use Config\Database;

class UtilisateursController
{
    private UtilisateursRepository $repo;

    public function __construct()
    {
        $pdo = Database::getConnection();
        $this->repo = new UtilisateursRepository($pdo);
    }

    // ------ Connexion ------
    public function login()
    {
        if(session_status() === PHP_SESSION_NONE) session_start();

        if(isset($_SESSION['user'])){
            header('Location: index.php?entity=utilisateurs&action=tableau_de_bord');
            exit;
        }

        $error = '';
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $login = trim($_POST['login'] ?? '');
            $mdp   = $_POST['mot_de_passe'] ?? '';

            if($login === '' || $mdp === ''){
                $error = "Tous les champs doivent être remplis.";
            } else {
                $user = $this->repo->verifyLogin($login, $mdp);
                if($user){
                    $_SESSION['user'] = [
                        'id' => $user->getIdUtilisateur(),
                        'pseudo' => $user->getPseudo(),
                        'email' => $user->getEmail(),
                        'role' => $user->getRole()
                    ];
                    header('Location: index.php?entity=utilisateurs&action=tableau_de_bord');
                    exit;
                } else {
                    $error = "Email ou mot de passe incorrect.";
                }
            }
        }

        require __DIR__ . '/../View/utilisateurs/connection.php';
    }

    // ------ Inscription ------

    public function register(): void
    {
        // Démarre la session si ce n'est pas déjà fait
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $error = '';
        $success = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // Vérification des champs obligatoires
                if (empty($_POST['pseudo']) || empty($_POST['email']) || empty($_POST['mdp'])) {
                    throw new \Exception("Tous les champs obligatoires doivent être remplis.");
                }

                // Vérification du format de l'email
                if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                    throw new \Exception("Adresse e-mail invalide.");
                }

                // Vérifie si l'email ou le pseudo existe déjà
                $existingEmail = $this->repo->findByEmail($_POST['email']);
                if ($existingEmail) {
                    throw new \Exception("Cet email est déjà utilisé.");
                }

                // Création de l'objet Utilisateur
                $user = new Utilisateur();
                $user->setNom($_POST['nom'] ?? '');
                $user->setPrenom($_POST['prenom'] ?? '');
                $user->setPseudo($_POST['pseudo']);
                $user->setEmail($_POST['email']);
                $user->setMdp(password_hash($_POST['mdp'], PASSWORD_BCRYPT));
                $user->setTelephone($_POST['telephone'] ?? '');
                $user->setRole($_POST['role'] ?? '1'); // 1 = Utilisateur par défaut
                $user->setTypeCovoiturage($_POST['type_covoiturage'] ?? 'passager');
                $user->setActif(isset($_POST['actif']) ? 1 : 0);
                $user->setPhoto('');
                $user->setDateCreation(date('Y-m-d H:i:s'));

                // Sauvegarde dans la base
                $this->repo->save($user);

                // Message de succès et redirection vers la page de connexion
                $success = "Compte créé avec succès. Vous pouvez maintenant vous connecter.";
                header('Location: index.php?entity=utilisateurs&action=login&success=1');
                exit;

            } catch (\Exception $e) {
                $error = $e->getMessage();
            }
        }

        // Affiche le formulaire avec éventuelles erreurs
        require __DIR__ . '/../View/utilisateurs/creer_utilisateur.php';
    }


    // ------ Tableau de bord ------
    public function dashboard()
    {
        if(session_status() === PHP_SESSION_NONE) session_start();

        if(!isset($_SESSION['user'])){
            header('Location: index.php?entity=utilisateurs&action=se_connecter');
            exit;
        }

        // Empêche cache pour bouton arrière
        header("Cache-Control: no-cache, no-store, must-revalidate");
        header("Pragma: no-cache");
        header("Expires: 0");

        $pseudo = $_SESSION['user']['pseudo'] ?? 'Utilisateur';
        require __DIR__ . '/../View/utilisateurs/tableau_de_bord.php';
    }

    // ------ Déconnexion ------
    public function logout()
    {
        if(session_status() === PHP_SESSION_NONE) session_start();
        $_SESSION = [];
        session_destroy();

        header("Cache-Control: no-cache, no-store, must-revalidate");
        header("Pragma: no-cache");
        header("Expires: 0");

        header('Location: index.php?entity=utilisateurs&action=se_connecter&message=logout');
        exit;
    }
}
