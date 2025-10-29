<?php
namespace Repository;

use PDO;
use PDOException;
use Entity\Utilisateur;

class UtilisateursRepository
{
    private $conn;

    public function __construct(PDO $conn)
    {
        $this->conn = $conn;
    }

    //-------------------- CREER UN NOUVEL UTILISATEUR --------------------//
    public function create(Utilisateur $user): void
    {
        try {
            // Étape 1 : Validation du format
            $user->validate();

            // Étape 2 : Vérification d’unicité email/pseudo
            if ($this->existsByEmailOrPseudo($user->getEmail(), $user->getPseudo())) {
                throw new \RuntimeException("L'email ou le pseudo est déjà utilisé.");
            }

            $stmt = $this->conn->prepare("
                INSERT INTO utilisateurs 
                (nom, prenom, pseudo, email, telephone, mdp, role, type_covoiturage,actif, photo, date_creation )
                VALUES 
                (:nom, :prenom, :pseudo, :email, :telephone, :mdp, :role, :type_covoiturage,:actif, :photo, :date_creation )
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
                ':type_covoiturage'=> $user->getTypeCovoiturage(),

                ':actif'           => $user->getActif(),
                ':photo'           => $user->getPhoto(),
                ':date_creation'   => $user->getDateCreation(),

            ]);

            $user->setIdUtilisateur((int)$this->conn->lastInsertId());

        } catch (\InvalidArgumentException $e) {
            error_log("Erreur validation utilisateur : " . $e->getMessage());
            throw $e;
        } catch (\RuntimeException $e) {
            error_log("Erreur exécution : " . $e->getMessage());
            throw $e;
        }
    }

    //-------------------- EXISTE PAR EMAIL OU PSEUDO --------------------//
    private function existsByEmailOrPseudo(string $email, string $pseudo): bool
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

    //-------------------- MAPPER UNE LIGNE EN UTILISATEUR --------------------//
    private function mapRowToUtilisateur(array $row): Utilisateur
    {
        // Conversion sécurisée des colonnes
        $idUtilisateur    = isset($row['id_utilisateur']) ? (int)$row['id_utilisateur'] : null;
        $nom              = $row['nom'] ?? '';
        $prenom           = $row['prenom'] ?? '';
        $pseudo           = $row['pseudo'] ?? '';
        $email            = $row['email'] ?? '';
        $telephone        = $row['telephone'] ?? '';
        $mdp              = $row['mdp'] ?? '';
        $role             = isset($row['role']) ? (int)$row['role'] : 1;
        $typeCovoiturage  = $row['type_covoiturage'] ?? 'passager';
        $actif            = isset($row['actif']) ? (int)$row['actif'] : 1;
        $photo            = $row['photo'] ?? '';
        $dateCreation     = $row['date_creation'] ?? date('Y-m-d H:i:s');


        // Création de l'objet Utilisateur
        $user = new Utilisateur(
            $nom,
            $prenom,
            $pseudo,
            $email,
            $telephone,
            $mdp,
            $role,
            $typeCovoiturage,

            $actif,
            $photo,
            $dateCreation,
              // Assurez-vous que votre constructeur accepte ce paramètre à la fin
        );

        // On assigne l'ID utilisateur
        $user->setIdUtilisateur($idUtilisateur);

        return $user;
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

    //-------------------- FILTRER PAR EMAIL PARTIEL --------------------//
    public function findByEmailPart(string $emailPart): array
    {
        $stmt = $this->conn->prepare("
            SELECT * FROM utilisateurs
            WHERE email LIKE :emailPart
        ");
        $stmt->execute([
            ':emailPart' => "%$emailPart%"
        ]);

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $utilisateurs = [];
        foreach ($rows as $row) {
            $utilisateurs[] = $this->mapRowToUtilisateur($row);
        }

        return $utilisateurs;
    }

    //-------------------- RECUPERER UN UTILISATEUR PAR EMAIL OU PSEUDO --------------------//
    public function findByEmailOrPseudo(string $email, string $pseudo): ?Utilisateur
    {
        $stmt = $this->conn->prepare("
            SELECT * 
            FROM utilisateurs
            WHERE email = :email OR pseudo = :pseudo
            LIMIT 1
        ");
        $stmt->execute([
            ':email'  => trim($email),
            ':pseudo' => trim($pseudo)
        ]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            return null;
        }

        return $this->mapRowToUtilisateur($row);
    }


    //-------------------- SUPPRIMER UN UTILISATEUR PAR SON ID --------------------//
    public function delete(int $id_utilisateur): bool
    {
        try {
            $stmt = $this->conn->prepare('DELETE FROM utilisateurs WHERE id_utilisateur = :id');
            return $stmt->execute([':id' => $id_utilisateur]);
        } catch (PDOException $e) {
            error_log('Erreur lors de la suppression de l\'utilisateur : ' . $e->getMessage());
            return false;
        }
    }

    // Mettre à jour les informations d'un utilisateur
    public function updateUtilisateur(Utilisateur $user): bool
    {
        try {
            $stmt = $this->conn->prepare('
                UPDATE utilisateurs
                SET pseudo = :pseudo,
                    email = :email,
                    mdp = :mdp
                WHERE id_utilisateur = :id_utilisateur
            ');

            return $stmt->execute([
                ':pseudo' => $user->getPseudo(),
                ':email' => $user->getEmail(),
                ':mdp' => $user->getMdp(),
                ':id_utilisateur' => $user->getIdUtilisateur()
            ]);

        } catch (PDOException $e) {
            error_log('Erreur updateUtilisateur : ' . $e->getMessage());
            return false;
        }
    }

}
