<?php
namespace Controller\Utilisateurs;

use Entity\Utilisateur;
use Repository\UtilisateursRepository;

class AuthController
{
    private UtilisateursRepository $repo;

    public function __construct(UtilisateursRepository $repo)
    {
        $this->repo = $repo;
    }

    public function register(): void
    {
        $message = '';
        $success = false;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $pseudo = trim($_POST['pseudo'] ?? '');
            $email  = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
            $mdp    = $_POST['mdp'] ?? '';

            try {

                // 🔍 Validation
                if (empty($pseudo) || empty($email) || empty($mdp)) {
                    throw new \Exception("Tous les champs sont obligatoires.");
                }

                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    throw new \Exception("Email invalide.");
                }

                if (!preg_match('/^[a-zA-Z0-9_]{3,20}$/', $pseudo)) {
                    throw new \Exception("Le pseudo doit contenir entre 3 et 20 caractères alphanumériques.");
                }

                if (strlen($mdp) < 6) {
                    throw new \Exception("Le mot de passe doit contenir au moins 6 caractères.");
                }

                // 🆕 IMPORTANT : on envoie le mot de passe brut
                $user = new Utilisateur(
                    nom: '',
                    prenom: '',
                    pseudo: $pseudo,
                    email: $email,
                    telephone: '',
                    mdp: $mdp,
                    role: 'user',
                    type_utilisateur: 'passager',
                    actif: 1,
                    photo: '/uploads/photos/default-avatar.jpg',
                    date_creation: date('Y-m-d H:i:s')
                );

                // 👇 Le hash sera fait dans le repository
                $this->repo->create($user);

                // Crédit d'accueil : 20€
                $this->repo->InitialiserCredit($user->getIdUtilisateur());

                $success = true;
                $message = "Compte créé avec succès !";

            } catch (\Exception $e) {
                $message = "Erreur : " . $e->getMessage();
            }
        }

        include __DIR__ . '/../../View/utilisateurs/creer_compte_utilisateur.php';
    }
    public function login(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $message = '';
        $max_attempts = 5;
        $lockout_time = 300; // 5 minutes

        if (!isset($_SESSION['login_attempts'])) {
            $_SESSION['login_attempts'] = 0;
            $_SESSION['last_attempt_time'] = time();
        }

        if ($_SESSION['login_attempts'] >= $max_attempts) {
            $elapsed = time() - $_SESSION['last_attempt_time'];
            if ($elapsed < $lockout_time) {
                $minutes = ceil(($lockout_time - $elapsed) / 60);
                $message = "Trop de tentatives échouées. Réessayez dans $minutes minute(s).";
                require_once __DIR__ . '/../../View/accueil/se_connecter.php';
                return;
            } else {
                $_SESSION['login_attempts'] = 0;
            }
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $identifiant = trim($_POST['identifiant'] ?? '');
            $mdp = $_POST['mdp'] ?? '';

            if (empty($identifiant) || empty($mdp)) {
                $message = "Veuillez renseigner tous les champs.";
            } else {
                $user = $this->repo->findUserByEmailOrPseudo($identifiant);


                if (!($user instanceof \Entity\Utilisateur)) {
                    $message = "Email ou pseudo incorrect.";
                    $_SESSION['login_attempts']++;
                    $_SESSION['last_attempt_time'] = time();
                } elseif (password_verify($mdp, $user->getMdp())) {
                    $_SESSION['login_attempts'] = 0;
                    $_SESSION['user'] = $user;
                    $_SESSION['user_id'] = $user->getIdUtilisateur();
                    $_SESSION['pseudo'] = $user->getPseudo();
                    header('Location: index.php?entity=utilisateurs&action=tableau_de_bord');
                    exit;
                } else {
                    $message = "Mot de passe incorrect.";
                    $_SESSION['login_attempts']++;
                    $_SESSION['last_attempt_time'] = time();
                }
            }
        }

        require_once __DIR__ . '/../../View/accueil/se_connecter.php';
    }

    public function logout(): void
    {
        session_destroy();
        header('Location: index.php?entity=accueil&action=index');
        exit;
    }
}
