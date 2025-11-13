<?php
namespace Repository;

use Exception;
use PDO;
use PDOException;
use Entity\Utilisateur;
use RuntimeException;

class UtilisateursRepository
{
    private PDO $conn;
    private ?string $lastError = null;

    public function __construct(PDO $conn)
    {
        $this->conn = $conn;
    }

    //-------------------- CREER UN NOUVEL UTILISATEUR --------------------//

    /**
     * @throws Exception
     */
    public function create(Utilisateur $user): void
    {
        try {
            $user->validate();

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

    //-------------------- EXISTE PAR EMAIL OU PSEUDO --------------------//
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

    //-------------------- METTRE À JOUR UN UTILISATEUR --------------------//
    public function updateUtilisateur(Utilisateur $utilisateur): bool
    {
        try {
            // Validation côté entité
            $utilisateur->validate();

            // --- GESTION DE L’UPLOAD PHOTO --- //
            $photoPath = $utilisateur->getPhoto();

            if (!empty($_FILES['photo']['tmp_name'])) {
                // Dossier d’upload complet basé sur la racine du projet
                $dossier = __DIR__ . '/../../public/uploads/photos/';

                // Vérifie ou crée le dossier
                if (!is_dir($dossier)) {
                    if (!mkdir($dossier, 0777, true)) {
                        throw new Exception("Impossible de créer le dossier d'upload : " . $dossier);
                    }
                }

                // Vérifie l’accès en écriture
                if (!is_writable($dossier)) {
                    throw new Exception("Le dossier d'upload n'est pas accessible en écriture : " . $dossier);
                }

                // Vérifie les erreurs PHP d’upload
                if ($_FILES['photo']['error'] !== UPLOAD_ERR_OK) {
                    throw new Exception("Erreur lors de l'upload (code PHP : " . $_FILES['photo']['error'] . ")");
                }

                // Nettoyage du nom et création d’un nom unique
                $nomFichier = uniqid('photo_', true) . "_" . basename($_FILES['photo']['name']);
                $cheminComplet = $dossier . $nomFichier;

                // Déplace le fichier temporaire
                if (!move_uploaded_file($_FILES['photo']['tmp_name'], $cheminComplet)) {
                    throw new Exception("Erreur lors de l'enregistrement de la photo : " . $cheminComplet);
                }

                // Convertit le chemin pour l’accès web (stocké en relatif)
                $photoPath = 'uploads/photos/' . $nomFichier;
                $utilisateur->setPhoto($photoPath);
            }


            // Prépare la requête
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

    //-------------------- RECUPERER UN UTILISATEUR PAR ID --------------------//
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

    //-------------------- RECUPERER TOUS LES UTILISATEURS --------------------//
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

    //-------------------- SUPPRIMER UN UTILISATEUR --------------------//
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

    //-------------------- GET LAST ERROR --------------------//
    public function getLastError(): ?string
    {
        return $this->lastError;
    }

    //-------------------- PRIVATE : MAP ROW TO UTILISATEUR --------------------//
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

        if ($row) {
            return $this->mapRowToUtilisateur($row);
        }

        return null;
    }

}
