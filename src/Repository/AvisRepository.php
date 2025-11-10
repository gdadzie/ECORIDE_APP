<?php
namespace Repository;

use Config\Database;
use Entity\Avis;
use PDO;

class AvisRepository
{
    private PDO $conn;

    public function __construct()
    {
        $this->conn = Database::getConnection();
    }

    // Ajouter un avis
    public function add(Avis $avis): bool
    {
        $sql = "INSERT INTO avis (id_covoiturage, id_emetteur, id_receveur, note, commentaire, statut, date_avis)
                VALUES (:id_covoiturage, :id_emetteur, :id_receveur, :note, :commentaire, :statut, :date_avis)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':id_covoiturage' => $avis->getIdCovoiturage(),
            ':id_emetteur'    => $avis->getIdEmetteur(),
            ':id_receveur'    => $avis->getIdReceveur(),
            ':note'           => $avis->getNote(),
            ':commentaire'    => $avis->getCommentaire(),
            ':statut'         => $avis->getStatut(),
            ':date_avis'      => $avis->getDateAvis(),
        ]);
    }

    // Récupérer les avis reçus par un utilisateur
    public function getAvisByReceveur(int $id_receveur): array
    {
        $sql = "SELECT * FROM avis WHERE id_receveur = :id ORDER BY date_avis DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':id', $id_receveur, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Récupérer les avis émis par un utilisateur
    public function getAvisByEmetteur(int $id_emetteur): array
    {
        $sql = "SELECT * FROM avis WHERE id_emetteur = :id ORDER BY date_avis DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':id', $id_emetteur, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Récupérer la note moyenne
    public function getMoyenneNotesUtilisateur(int $id_utilisateur): ?float
    {
        $sql = "SELECT AVG(note) as moyenne FROM avis WHERE id_receveur = :id AND statut = 'validé'";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':id', $id_utilisateur, PDO::PARAM_INT);
        $stmt->execute();
        $res = $stmt->fetch(PDO::FETCH_ASSOC);
        return $res && $res['moyenne'] !== null ? round((float)$res['moyenne'], 1) : null;
    }
}
