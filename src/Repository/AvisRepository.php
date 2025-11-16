<?php
namespace Repository;

use PDO;
use Entity\Avis;

class AvisRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * ➤ CREATE : Ajouter un avis (note + commentaire)
     */
    public function create(Avis $avis): int
    {
        $sql = "INSERT INTO avis (id_covoiturage, id_emetteur, id_receveur, note, commentaire, statut, date_avis)
                VALUES (:covoiturage, :emetteur, :receveur, :note, :commentaire, :statut, :date_avis)";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            'covoiturage' => $avis->getIdCovoiturage(),
            'emetteur' => $avis->getIdEmetteur(),
            'receveur' => $avis->getIdReceveur(),
            'note' => $avis->getNote(),
            'commentaire' => $avis->getCommentaire(),
            'statut' => $avis->getStatut(),
            'date_avis' => $avis->getDateAvis()
        ]);

        return (int)$this->pdo->lastInsertId();
    }

    /**
     * ➤ UPDATE : modifier un avis existant
     */
    public function update(Avis $avis): void
    {
        $sql = "UPDATE avis 
                SET note = :note, commentaire = :commentaire, statut = :statut
                WHERE id_avis = :id";

        $stmt = $this->pdo->prepare($sql);
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
        $stmt = $this->pdo->prepare("SELECT * FROM avis WHERE id_covoiturage = :id ORDER BY date_avis DESC");
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
     * ➤ READ : note moyenne d’un conducteur
     */
    public function getMoyenneUtilisateur(int $idUtilisateur): ?float
    {
        $stmt = $this->pdo->prepare("SELECT AVG(note) AS moyenne FROM avis WHERE id_receveur = :id");
        $stmt->execute(['id' => $idUtilisateur]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row['moyenne'] !== null ? (float)$row['moyenne'] : null;
    }
}
