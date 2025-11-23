<?php
namespace Repository;

use Exception;
use PDO;
use PDOException;
use Entity\Utilisateur;
use RuntimeException;

class UtilisateursRepository
{
    // ===============================
    // Propriétés
    // ===============================
    private PDO $conn;
    private ?string $lastError = null;

    // ===============================
    // Constructeur
    // ===============================
    public function __construct(PDO $conn)
    {
        $this->conn = $conn;
    }

    // ===============================
    // CREATION D’UN UTILISATEUR
    // ===============================
    /**
     * Crée un nouvel utilisateur en BDD.
     *
     * @throws Exception
     */
    public function create(Utilisateur $user): void
    {
        try {


            if ($this->findByEmailOrPseudo($user->getEmail(), $user->getPseudo())) {
                throw new RuntimeException("L'email ou le pseudo est déjà utilisé.");
            }

            $stmt = $this->conn->prepare("
                INSERT INTO utilisateurs 
                (nom, prenom, pseudo, email, telephone, mdp, role, type_utilisateur, actif, photo, date_creation)
                VALUES 
                (:nom, :prenom, :pseudo, :email, :telephone, :mdp, :role, :type_utilisateur, :actif, :photo, :date_creation)
            ");

            $mdpHash = password_hash($user->getMdp(), PASSWORD_DEFAULT);

            $stmt->execute([
                ':nom'             => $user->getNom(),
                ':prenom'          => $user->getPrenom(),
                ':pseudo'          => $user->getPseudo(),
                ':email'           => $user->getEmail(),
                ':telephone'       => $user->getTelephone(),
                ':mdp'             => $mdpHash,
                ':role'            => $user->getRole(),
                ':type_utilisateur'=> $user->getTypeUtilisateur(),
                ':actif'           => $user->getActif(),
                ':photo'           => $user->getPhoto(),
                ':date_creation'   => $user->getDateCreation(),
            ]);

            $user->setIdUtilisateur((int)$this->conn->lastInsertId());

        } catch (Exception $e) {
            error_log("Erreur création utilisateur : " . $e->getMessage());
            throw $e;
        }
    }

    // ===============================
    // VERIFICATION EXISTENCE EMAIL / PSEUDO
    // ===============================
    public function findByEmailOrPseudo(string $email, string $pseudo): bool
    {
        $stmt = $this->conn->prepare("
            SELECT COUNT(*) 
            FROM utilisateurs 
            WHERE email = :email OR pseudo = :pseudo
        ");
        $stmt->execute([
            ':email'  => trim($email),
            ':pseudo' => trim($pseudo)
        ]);

        return (bool) $stmt->fetchColumn();
    }

    // ===============================
    // MISE À JOUR D’UN UTILISATEUR
    // ===============================
    public function updateUtilisateur(Utilisateur $utilisateur): bool
    {
        try {

            $photoPath = $utilisateur->getPhoto();

            // Gestion upload photo si présent
            if (!empty($_FILES['photo']['tmp_name'])) {
                $photo = $_FILES['photo'];
                $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];

                if (!in_array($photo['type'], $allowedTypes) || $photo['size'] > 2.5 * 1024 * 1024) {
                    throw new Exception("Format ou taille de fichier invalide (max 2,5 Mo, JPG/PNG/GIF).");
                }

                $ext = pathinfo($photo['name'], PATHINFO_EXTENSION);
                $dossier = __DIR__ . '/../../public/uploads/photos/';
                $newFileName = 'user_' . $utilisateur->getIdUtilisateur() . '.' . $ext;
                $cheminComplet = $dossier . $newFileName;

                if (!is_dir($dossier) && !mkdir($dossier, 0755, true)) {
                    throw new Exception("Impossible de créer le dossier d'upload : " . $dossier);
                }

                if (!is_writable($dossier)) {
                    throw new Exception("Le dossier d'upload n'est pas accessible en écriture : " . $dossier);
                }

                $anciennePhoto = $utilisateur->getPhoto();
                if ($anciennePhoto && $anciennePhoto !== '/uploads/photos/default-avatar.jpg') {
                    $anciennePhotoPath = __DIR__ . '/../../public' . $anciennePhoto;
                    if (file_exists($anciennePhotoPath)) {
                        unlink($anciennePhotoPath);
                    }
                }

                if (!move_uploaded_file($photo['tmp_name'], $cheminComplet)) {
                    throw new Exception("Erreur lors de l'enregistrement de la photo : " . $cheminComplet);
                }

                $photoPath = '/uploads/photos/' . $newFileName;
                $utilisateur->setPhoto($photoPath);
            }

            $sql = "UPDATE utilisateurs
                    SET nom = :nom,
                        prenom = :prenom,
                        telephone = :telephone,
                        type_utilisateur = :type_utilisateur,
                        photo = :photo
                    WHERE id_utilisateur = :id_utilisateur";

            $stmt = $this->conn->prepare($sql);
            $success = $stmt->execute([
                ':nom' => $utilisateur->getNom(),
                ':prenom' => $utilisateur->getPrenom(),
                ':telephone' => $utilisateur->getTelephone(),
                ':type_utilisateur' => $utilisateur->getTypeUtilisateur(),
                ':photo' => $photoPath,
                ':id_utilisateur' => $utilisateur->getIdUtilisateur()
            ]);

            if (!$success) {
                $this->lastError = implode(', ', $stmt->errorInfo());
            }

            error_log("Upload photo OK : " . $photoPath);

            return $success;

        } catch (Exception $e) {
            $this->lastError = $e->getMessage();
            error_log("Erreur update utilisateur : " . $e->getMessage());
            return false;
        }
    }

    /**
     * Upload spécifique de photo pour AvatarController
     */
    public function uploadPhotoUtilisateur(Utilisateur $utilisateur, array $file): void
    {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($file['type'], $allowedTypes) || $file['size'] > 2.5 * 1024 * 1024) {
            throw new Exception("Format ou taille de fichier invalide (max 2,5 Mo, JPG/PNG/GIF).");
        }

        if ($file['error'] !== UPLOAD_ERR_OK) {
            throw new Exception("Erreur lors de l'upload (code PHP : " . $file['error'] . ")");
        }

        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $newFileName = 'user_' . $utilisateur->getIdUtilisateur() . '_' . time() . '.' . $ext;
        $uploadDir = __DIR__ . '/../../public/uploads/photos/';
        $uploadPath = $uploadDir . $newFileName;

        if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
        if (!is_writable($uploadDir)) throw new Exception("Le dossier d'upload n'est pas accessible en écriture : " . $uploadDir);

        if (!move_uploaded_file($file['tmp_name'], $uploadPath)) {
            throw new Exception("Impossible de déplacer le fichier uploadé.");
        }

        $utilisateur->setPhoto('/uploads/photos/' . $newFileName);
    }

    // ===============================
    // RECUPERATION UTILISATEUR
    // ===============================
    public function findById(int $id): ?Utilisateur
    {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM utilisateurs WHERE id_utilisateur = :id");
            $stmt->execute([':id' => $id]);
            $data = $stmt->fetch(PDO::FETCH_ASSOC);

            return $data ? $this->mapRowToUtilisateur($data) : null;

        } catch (PDOException $e) {
            $this->lastError = $e->getMessage();
            error_log("Erreur findById Utilisateur : " . $e->getMessage());
            return null;
        }
    }

    public function findUserByEmailOrPseudo(string $emailOrPseudo): ?Utilisateur
    {
        $stmt = $this->conn->prepare("
            SELECT * 
            FROM utilisateurs 
            WHERE email = :val OR pseudo = :val
            LIMIT 1
        ");
        $stmt->execute([':val' => trim($emailOrPseudo)]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ? $this->mapRowToUtilisateur($row) : null;
    }

    public function findAll(): array
    {
        $stmt = $this->conn->prepare("SELECT * FROM utilisateurs");
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $utilisateurs = [];
        foreach ($rows as $row) {
            $utilisateurs[] = $this->mapRowToUtilisateur($row);
        }
        return $utilisateurs;
    }

    // ===============================
    // SUPPRESSION UTILISATEUR
    // ===============================
    public function delete(int $id_utilisateur): bool
    {
        try {
            $stmt = $this->conn->prepare('DELETE FROM utilisateurs WHERE id_utilisateur = :id');
            return $stmt->execute([':id' => $id_utilisateur]);
        } catch (PDOException $e) {
            $this->lastError = $e->getMessage();
            error_log("Erreur suppression utilisateur : " . $e->getMessage());
            return false;
        }
    }

    // ===============================
    // UTILITAIRES
    // ===============================
    public function getLastError(): ?string
    {
        return $this->lastError;
    }

    // Mapping row BDD → Entité Utilisateur
    private function mapRowToUtilisateur(array $row): Utilisateur
    {
        $user = new Utilisateur(
            $row['nom'] ?? '',
            $row['prenom'] ?? '',
            $row['pseudo'] ?? '',
            $row['email'] ?? '',
            $row['telephone'] ?? '',
            $row['mdp'] ?? '',
            $row['role'] ?? 'user',
            $row['type_utilisateur'] ?? 'passager',
            $row['actif'] ?? 1,
            $row['photo'] ?? '',
            $row['date_creation'] ?? ''
        );
        $user->setIdUtilisateur((int)$row['id_utilisateur']);
        return $user;
    }


    public function InitialiserCredit(int $idUtilisateur, float $montant = 20.00): void
    {
        try {
            $stmt = $this->conn->prepare("
            INSERT INTO credits (id_utilisateur, credit) 
            VALUES (:idUtilisateur, :credit)
        ");
            $stmt->execute([
                ':idUtilisateur' => $idUtilisateur,
                ':credit' => $montant
            ]);
        } catch (\PDOException $e) {
            error_log("Erreur initialisation crédit : " . $e->getMessage());
            throw new \Exception("Impossible d'initialiser le crédit de l'utilisateur.");
        }
    }

}
