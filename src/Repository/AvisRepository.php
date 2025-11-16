<?php
namespace Repository;

use Config\Database;
use PDO;
use Entity\Avis;

class AvisRepository
{
    private ?PDO $conn;

    public function __construct()
    {
        $this->conn = Database::getConnection();
        if (!$this->conn) {
            $error = Database::getLastError() ?? "Connexion à la base de données impossible.";
            throw new \RuntimeException($error);
        }

    }

    /**
     * ➤ CREATE : Ajouter un avis (note + commentaire)
     */
    public function create(Avis $avis): int
    {
        $sql = "INSERT INTO avis (id_covoiturage, id_emetteur, id_receveur, note, commentaire, statut, date_avis)
                VALUES (:covoiturage, :emetteur, :receveur, :note, :commentaire, :statut, :date_avis)";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'covoiturage' => $avis->getIdCovoiturage(),
            'emetteur' => $avis->getIdEmetteur(),
            'receveur' => $avis->getIdReceveur(),
            'note' => $avis->getNote(),
            'commentaire' => $avis->getCommentaire(),
            'statut' => $avis->getStatut(),
            'date_avis' => $avis->getDateAvis()
        ]);

        return (int)$this->conn->lastInsertId();
    }

    /**
     * ➤ UPDATE : modifier un avis existant
     */
    public function update(Avis $avis): void
    {
        $sql = "UPDATE avis 
                SET note = :note, commentaire = :commentaire, statut = :statut
                WHERE id_avis = :id";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'note' => $avis->getNote(),
            'commentaire' => $avis->getCommentaire(),
            'statut' => $avis->getStatut(),
            'id' => $avis->getIdAvis()
        ]);
    }

    /**
     * ➤ READ : Avis d’un covoiturage
     */
    public function getByCovoiturage(int $id): array
    {
        $stmt = $this->conn->prepare("SELECT * FROM avis WHERE id_covoiturage = :id ORDER BY date_avis DESC");
        $stmt->execute(['id' => $id]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $avisList = [];
        foreach ($rows as $r) {
            $avis = new Avis(
                $r['id_covoiturage'],
                $r['id_emetteur'],
                $r['id_receveur'],
                $r['note'],
                $r['commentaire'],
                $r['statut'],
                $r['date_avis']
            );
            $avis->setIdAvis($r['id_avis']);
            $avisList[] = $avis;
        }

        return $avisList;
    }

    /**
     * ➤ Nombre d'avis reçus par un utilisateur
     */
    public function getNbAvisByUtilisateur(int $idUtilisateur): int
    {
        $stmt = $this->conn->prepare("SELECT COUNT(*) AS nb FROM avis WHERE id_receveur = :id");
        $stmt->execute(['id' => $idUtilisateur]);
        $nbAvis = $stmt->fetch(PDO::FETCH_ASSOC)['nb'] ?? 0;
        return (int)$nbAvis;
    }

    /**
     * ➤ Note moyenne reçue par un utilisateur
     */
    public function getNoteMoyenneByUtilisateur(int $idUtilisateur): ?float
    {
        $stmt = $this->conn->prepare("SELECT AVG(note) AS moyenne FROM avis WHERE id_receveur = :id AND note IS NOT NULL");
        $stmt->execute(['id' => $idUtilisateur]);
        $moyenne = $stmt->fetch(PDO::FETCH_ASSOC)['moyenne'];
        return $moyenne !== null ? (float)$moyenne : null;
    }
}
