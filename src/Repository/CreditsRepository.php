<?php
namespace Repository;

use PDO;
use Entity\Utilisateur;

class CreditsRepository
{
    private PDO $conn;

    public function __construct(PDO $conn)
    {
        $this->conn = $conn;
    }

    // Récupère les crédits d'un utilisateur
    public function getCreditsByUtilisateur(Utilisateur $user): ?object
    {
        $stmt = $this->conn->prepare(
            "SELECT credit FROM credits WHERE id_utilisateur = :idUtilisateur"
        );
        $stmt->execute(['idUtilisateur' => $user->getIdUtilisateur()]);

        return $stmt->fetch(PDO::FETCH_OBJ) ?: null;
    }


    // Met à jour les crédits d'un utilisateur
    public function updateCredits(Utilisateur $user, float $creditSolde): bool
    {
        $stmt = $this->conn->prepare("
            UPDATE credits 
            SET credit = :creditSolde 
            WHERE id_utilisateur = :idUtilisateur
        ");
        return $stmt->execute([
            'creditSolde' => $creditSolde,
            'idUtilisateur' => $user->getIdUtilisateur()
        ]);
    }
}
