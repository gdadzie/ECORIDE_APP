<?php
namespace Controller;

use Entity\Utilisateur;
use Config\Database;
use PDO;
use Repository\UtilisateursRepository;
use Throwable;

require_once __DIR__ . '/../Entity/Utilisateur.php';
require_once __DIR__ . '/../../Config/Database.php';

class UtilisateursController
{
    private UtilisateursRepository $repo;

    public function __construct(UtilisateursRepository $repo)
    {
        $this->repo = $repo;
    }

    //--------------------  CREER UN COMPTE UTILISATEUR--------------------//
    public function register(): void
    {
        $message = '';
        $success = false;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // 1️⃣ Récupération et nettoyage des données
            $pseudo = trim($_POST['pseudo'] ?? '');
            $email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
            $mdp = $_POST['mdp'] ?? '';

            try {
                // 2️⃣ Création de l’objet Utilisateur
                $user = new Utilisateur(
                    nom: '',
                    prenom: '',
                    pseudo: $pseudo,
                    email: $email,
                    mdp: $mdp
                );

                // 3️⃣ Validation
                $user->validate();

                // 4️⃣ Appel du repository pour insertion
                $this->repo->create($user);

                $message = "Utilisateur créé avec succès (ID : {$user->getIdUtilisateur()})";
                $success = true;

            } catch (\InvalidArgumentException $e) {
                $message = "Erreur de validation : " . $e->getMessage();

            } catch (\RuntimeException $e) {
                $message = "Erreur : " . $e->getMessage();
            }
        }

        // 5️⃣ Affichage du formulaire
        include __DIR__ . '/../View/utilisateurs/creer_compte_utilisateur.php';
    }

    //-------------------- CONNECTION DE L'UTILISATEUR --------------------//
    public function login(): void
    {


        $message = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $identifiant = trim($_POST['identifiant'] ?? ''); // email ou pseudo
            $mdp = $_POST['mdp'] ?? '';

            if (empty($identifiant) || empty($mdp)) {
                $message = "Veuillez renseigner tous les champs.";
            } else {
                // Recherche par email OU pseudo
                $user = $this->repo->findByEmailOrPseudo($identifiant, $identifiant);

                if ($user === null) {
                    $message = "Email ou pseudo incorrect.";
                } else {
                    // Vérifie le mot de passe
                    if (password_verify($mdp, $user->getMdp())) {

                        // ✅ Stocke l'objet utilisateur entier dans la session
                        $_SESSION['user'] = $user;

                        // Redirige vers le tableau de bord
                        header('Location: index.php?entity=utilisateurs&action=tableau_de_bord');
                        exit;
                    } else {
                        $message = "Mot de passe incorrect.";
                    }
                }
            }
        }

        // Affiche la vue du formulaire de connexion
        require_once __DIR__ . '/../View/utilisateurs/se_connecter.php';
    }

    //-------------------- TABLEAU DE BORD --------------------//
    public function dashboard(): void
    {


        // Si pas connecté → redirection
        if (empty($_SESSION['user'])) {
            header('Location: index.php?entity=utilisateurs&action=se_connecter');
            exit;
        }

        // Récupère l’utilisateur connecté
        $user = $_SESSION['user'];

        // Affiche la vue du tableau de bord
        require_once __DIR__ . '/../View/utilisateurs/tableau_de_bord.php';
    }

    //-------------------- DECONNEXION --------------------//
    public function logout(): void
    {


        session_destroy();
        header('Location: index.php?entity=accueil&action=index');
        exit;
    }

    //-------------------- LISTE DE TOUS LES UTILISATEURS --------------------//
    public function liste(): void
    {
        error_reporting(E_ALL);
        ini_set('display_errors', 1);

        try {
            if (empty($_SESSION['user'])) {
                header('Location: index.php?entity=utilisateurs&action=se_connecter');
                exit;
            }

            $searchEmail = trim($_GET['email'] ?? '');

            if ($searchEmail !== '') {
                $utilisateurs = $this->repo->findByEmailPart($searchEmail);
            } else {
                $utilisateurs = $this->repo->findAll();
            }

            // ⚠️ Assurer que la variable existe toujours
            $utilisateurs = $utilisateurs ?? [];

            require_once __DIR__ . '/../View/utilisateurs/index.php';
        } catch (Throwable $e) {
            echo "<pre style='color:red'>";
            echo "Erreur : " . $e->getMessage() . "\n";
            echo $e->getFile() . " : " . $e->getLine();
            echo "</pre>";
        }
    }


    public function supprimer(): void
    {
        // Vérifie que l'utilisateur est connecté
        if (empty($_SESSION['user'])) {
            header('Location: index.php?entity=utilisateurs&action=login');
            exit;
        }

        // Vérifie que c'est un administrateur (role = 2)
        if ((int)$_SESSION['user']['role'] !== 2) {
            http_response_code(403);
            echo "<h2 style='color:red;text-align:center;margin-top:50px;'>
                ⚠️ Accès refusé : vous n’avez pas les droits pour supprimer un utilisateur.
              </h2>";
            exit;
        }

        // Récupère l'ID à supprimer
        $id = (int)($_GET['id'] ?? 0);

        if ($id > 0 && $this->repo->delete($id)) {
            header('Location: index.php?entity=utilisateurs&action=liste_utilisateurs&success=1');
            exit;
        } else {
            header('Location: index.php?entity=utilisateurs&action=liste_utilisateurs&error=1');
            exit;
        }
    }

    public function charteGraphique()
    {
        require_once __DIR__ . '/../View/utilisateurs/charte_graphique.php';
    }
}







