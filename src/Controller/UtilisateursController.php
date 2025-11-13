<?php
namespace Controller;

use AllowDynamicProperties;
use Entity\Utilisateur;
use Config\Database;
use PDO;
use PDOException;
use Repository\UtilisateursRepository;
use Repository\CovoituragesRepository;
use Repository\VehiculesRepository;
use Throwable;

require_once __DIR__ . '/../Entity/Utilisateur.php';
require_once __DIR__ . '/../Entity/Covoiturage.php';
require_once __DIR__ . '/../Entity/Vehicule.php';
require_once __DIR__ . '/../../Config/Database.php';

#[AllowDynamicProperties]
class UtilisateursController
{
    private UtilisateursRepository $repo;
    private VehiculesRepository $vehiculeRepo;

    private ?PDO $conn = null;

    public function __construct(UtilisateursRepository $repo, VehiculesRepository $vehiculeRepo)
    {
        $this->repo = $repo;
        $this->vehiculeRepo = $vehiculeRepo;
    }

    //--------------------  CREER UN COMPTE UTILISATEUR --------------------//
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
                require_once __DIR__ . '/../View/accueil/se_connecter.php';
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

        require_once __DIR__ . '/../View/accueil/se_connecter.php';
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
                $utilisateurs = $this->repo->findByEmail($searchEmail);
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

    //-------------------- SUPPRESSION D'UN UTILISATEUR --------------------//
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

    //-------------------- PAGE CHARTE GRAPHIQUE --------------------//
    public function charteGraphique(): void
    {
        require_once __DIR__ . '/../View/utilisateurs/charte_graphique.php';
    }

    public function profilUser(): void
    {
        // 🔹 Vérifie si l'utilisateur est connecté
        if (empty($_SESSION['user'])) {
            header('Location: index.php?entity=utilisateurs&action=login');
            exit;
        }

        $user = $_SESSION['user'];

        // 🔹 Initialisation des messages
        $message = '';
        $success = false;

        // 🔹 Couleurs et énergies disponibles pour le formulaire
        $couleurs = ['Noir', 'Blanc', 'Gris', 'Rouge', 'Bleu', 'Vert', 'Jaune', 'Autre'];
        $energies = ['Essence', 'Diesel', 'Électrique', 'Hybride', 'GPL', 'Autre'];

        // 🔹 Traitement du formulaire d’ajout de véhicule
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_vehicle'])) {

            $data = [
                'id_utilisateur' => $user->getIdUtilisateur(),
                'id_marque' => (int)($_POST['id_marque'] ?? 0),
                'modele' => trim($_POST['modele'] ?? ''),
                'couleur' => trim($_POST['couleur'] ?? ''),
                'energie' => trim($_POST['energie'] ?? ''),
                'nb_places' => (int)($_POST['nb_places'] ?? 1),
                'immatriculation' => trim($_POST['immatriculation'] ?? ''),
                'date_premiere_immatriculation' => trim($_POST['date_premiere_immatriculation'] ?? '')
            ];

            // 🔹 Validation des champs obligatoires
            if (
                empty($data['id_marque']) || empty($data['modele']) || empty($data['couleur']) ||
                empty($data['energie']) || empty($data['immatriculation']) || empty($data['date_premiere_immatriculation'])
            ) {
                $message = "❌ Veuillez remplir tous les champs obligatoires.";
            } else {
                if ($this->vehiculeRepo->addVehicule($data)) {
                    $success = true;
                    $message = "✅ Véhicule <strong>" . htmlspecialchars($data['modele']) . "</strong> ajouté avec succès !";
                } else {
                    $success = false;
                    $message = "❌ Erreur lors de l’ajout du véhicule.";
                }
            }
        }

        // 🔹 Récupération des véhicules existants pour l'utilisateur
        $vehicules = $this->vehiculeRepo->getVehiculesByUtilisateur($user->getIdUtilisateur());

        // 🔹 Si tu as un repo de covoiturages passé au contrôleur
        $covoitRepo = new \Repository\CovoituragesRepository(); // ou mieux : injecte-le via le constructeur
        $covoiturages = $covoitRepo->getAllCovoiturages($user->getIdUtilisateur());

        // 🔹 Préparer les variables pour la vue
        $viewData = [
            'vehicules'    => $vehicules,
            'covoiturages' => $covoiturages,
            'couleurs'     => $couleurs,
            'energies'     => $energies,
            'success'      => $success,
            'message'      => $message,
            'user'         => $user
        ];

        // 🔹 Extraire les variables pour la vue
        extract($viewData);

        // 🔹 Inclure la vue
        require_once __DIR__ . '/../View/utilisateurs/profil_utilisateur.php';
    }





    public function findById(int $id): ?Utilisateur
    {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM utilisateurs WHERE id_utilisateur = :id");
            $stmt->execute([':id' => $id]);
            $data = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($data) {
                $user = new Utilisateur(
                    $data['nom'] ?? '',
                    $data['prenom'] ?? '',
                    $data['pseudo'] ?? '',
                    $data['email'] ?? '',
                    $data['telephone'] ?? '',
                    $data['mdp'] ?? '',
                    $data['role'] ?? 'user',
                    $data['type_utilisateur'] ?? 'passager',
                    $data['actif'] ?? 1,
                    $data['photo'] ?? '',
                    $data['date_creation'] ?? ''
                );

                $user->setIdUtilisateur((int)$data['id_utilisateur']);

                return $user;
            }

            return null;
        } catch (PDOException $e) {
            $this->lastError = $e->getMessage();
            error_log("Erreur findById Utilisateur : " . $e->getMessage());
            return null;
        }
    }

    public function getLastError(): ?string
    {
        return $this->lastError;
    }

    public function profilPassager(): void
    {
        require __DIR__ . '/../View/utilisateurs/passagers/profil_passager.php';
    }

    public function profilConducteur(): void
    {
        require __DIR__ . '/../View/utilisateurs/conducteur/profil_conducteur.php';
    }

    public function espaceEmploye(): void
    {
        require __DIR__ . '/../View/utilisateurs/employe/espace_employe.php';
    }

    public function espaceAdmin(): void
    {
        require __DIR__ . '/../View/utilisateurs/admin/espace_admin.php';
    }

    public function updateProfilUtilisateur(): void
    {
        session_start();

        $userId = $_SESSION['user_id'] ?? null;
        if (!$userId) {
            header('Location: index.php?entity=accueil&action=se_connecter');
            exit;
        }

        $utilisateur = $this->repo->findById($userId);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $utilisateur->setNom($_POST['nom'] ?? '');
            $utilisateur->setPrenom($_POST['prenom'] ?? '');
            $utilisateur->setTelephone($_POST['telephone'] ?? '');
            $utilisateur->setTypeUtilisateur($_POST['type_utilisateur'] ?? 'passager');

            if ($this->repo->updateUtilisateur($utilisateur)) {
                $message = "✅ Profil mis à jour avec succès !";
            } else {
                $message = "❌ Erreur lors de la mise à jour du profil : " . $this->repo->getLastError();
            }
        }

        require __DIR__ . '/../View/utilisateurs/mise_a_jour_profil.php';
    }







}