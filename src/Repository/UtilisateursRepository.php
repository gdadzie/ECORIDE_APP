<?php
namespace Repository;

use Entity\Utilisateur;
use Config\Database;
use PDO;
use PDOException;
use Exception;

class UtilisateursRepository
{
    private PDO $conn;

    public function __construct()
    {
        $this->conn = Database::getConnection();
    }

    // 🔹 CREATE
    public function create(Utilisateur $user): bool
    {
        try {
            $stmt = $this->conn->prepare("
                INSERT INTO utilisateurs (nom, prenom, pseudo, email, telephone, mdp, role, type_covoiturage, actif, photo, date_creation)
                VALUES (:nom, :prenom, :pseudo, :email, :telephone, :mdp, :role, :type_covoiturage, :actif, :photo, :date_creation)
            ");
            $result = $stmt->execute([
                ':nom' => $user->getNom(),
                ':prenom' => $user->getPrenom(),
                ':pseudo' => $user->getPseudo(),
                ':email' => $user->getEmail(),
                ':telephone' => $user->getTelephone(),
                ':mdp' => $user->getMdp(),
                ':role' => $user->getRole(),
                ':type_covoiturage' => $user->getTypeCovoiturage(),
                ':actif' => $user->getActif(),
                ':photo' => $user->getPhoto(),
                ':date_creation' => $user->getDateCreation()
            ]);

            if ($result) {
                $user->setIdUtilisateur((int)$this->conn->lastInsertId());
            }

            return $result;

        } catch (PDOException $e) {
            error_log('Erreur lors de la création de l’utilisateur : ' . $e->getMessage());
            return false;
        }
    }

    // 🔹 READ (trouver par ID)
    public function find(int $id): ?Utilisateur
    {
        $stmt = $this->conn->prepare("SELECT * FROM utilisateurs WHERE id_utilisateur = :id");
        $stmt->execute([':id' => $id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$data) {
            return null;
        }

        $user = new Utilisateur(
            $data['nom'],
            $data['prenom'],
            $data['pseudo'],
            $data['email'],
            $data['telephone'],
            $data['mdp'],
            $data['role'],
            $data['type_covoiturage'],
            (bool)$data['actif'],
            $data['photo'] ?? '',
            $data['date_creation'] ?? ''
        );
        $user->setIdUtilisateur((int)$data['id_utilisateur']);

        return $user;
    }

    // 🔹 READ ALL
    public function findAll(): array
    {
        $stmt = $this->conn->query("SELECT * FROM utilisateurs");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $users = [];

        foreach ($rows as $data) {
            $user = new Utilisateur(
                $data['nom'],
                $data['prenom'],
                $data['pseudo'],
                $data['email'],
                $data['telephone'],
                $data['mdp'],
                $data['role'],
                $data['type_covoiturage'],
                (bool)$data['actif'],
                $data['photo'] ?? '',
                $data['date_creation'] ?? ''
            );
            $user->setIdUtilisateur((int)$data['id_utilisateur']);
            $users[] = $user;
        }

        return $users;
    }

    // 🔹 UPDATE
    public function update(Utilisateur $user): bool
    {
        if ($user->getIdUtilisateur() === null) {
            throw new Exception("Impossible de modifier un utilisateur non créé.");
        }

        try {
            $stmt = $this->conn->prepare("
                UPDATE utilisateurs
                SET nom = :nom,
                    prenom = :prenom,
                    pseudo = :pseudo,
                    email = :email,
                    telephone = :telephone,
                    mdp = :mdp,
                    role = :role,
                    type_covoiturage = :type_covoiturage,
                    actif = :actif,
                    photo = :photo
                WHERE id_utilisateur = :id
            ");

            return $stmt->execute([
                ':nom' => $user->getNom(),
                ':prenom' => $user->getPrenom(),
                ':pseudo' => $user->getPseudo(),
                ':email' => $user->getEmail(),
                ':telephone' => $user->getTelephone(),
                ':mdp' => $user->getMdp(),
                ':role' => $user->getRole(),
                ':type_covoiturage' => $user->getTypeCovoiturage(),
                ':actif' => $user->getActif(),
                ':photo' => $user->getPhoto(),
                ':id' => $user->getIdUtilisateur()
            ]);

        } catch (PDOException $e) {
            error_log('Erreur lors de la mise à jour : ' . $e->getMessage());
            return false;
        }
    }

    // 🔹 DELETE
    public function delete(int $id): bool
    {
        try {
            $stmt = $this->conn->prepare("DELETE FROM utilisateurs WHERE id_utilisateur = :id");
            return $stmt->execute([':id' => $id]);
        } catch (PDOException $e) {
            error_log('Erreur lors de la suppression : ' . $e->getMessage());
            return false;
        }
    }

    // 🔹 SAVE (Create ou Update)
    public function save(Utilisateur $user): bool
    {
        if ($user->getIdUtilisateur() === null) {
            return $this->create($user);
        } else {
            return $this->update($user);
        }
    }

    // 🔹 Trouver un utilisateur par son email
    public function findByEmail(string $email): ?Utilisateur
    {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM utilisateurs WHERE email = :email");
            $stmt->execute([':email' => $email]);
            $data = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$data) {
                return null; // Aucun utilisateur trouvé
            }

            $user = new Utilisateur(
                $data['nom'],
                $data['prenom'],
                $data['pseudo'],
                $data['email'],
                $data['telephone'],
                $data['mdp'],
                $data['role'],
                $data['type_covoiturage'],
                (bool)$data['actif'],
                $data['photo'] ?? '',
                $data['date_creation'] ?? ''
            );
            $user->setIdUtilisateur((int)$data['id_utilisateur']);

            return $user;

        } catch (PDOException $e) {
            error_log('Erreur findByEmail : ' . $e->getMessage());
            return null;
        }
    }

// 🔹 Vérifier la connexion (login)
    public function verifyLogin(string $email, string $mdp): ?Utilisateur
    {
        $user = $this->findByEmail($email);

        if ($user && password_verify($mdp, $user->getMdp())) {
            return $user; // Connexion réussie
        }

        return null; // Email ou mot de passe invalide
    }

}
