<?php
namespace Repository;

use Config\Database;
use PDO;
use PDOException;
use Entity\Covoiturage;

class CovoituragesRepository
{
    private ?PDO $conn = null;
    private ?string $lastError = null;

    public function __construct()
    {
        $this->conn = Database::getConnection();
        if (!$this->conn) {
            $error = Database::getLastError() ?? "Connexion à la base de données impossible.";
            throw new \RuntimeException($error);
        }
    }

    // ────────────────────────────────
// 🔹 Calcul automatique de l’heure d’arrivée
// ────────────────────────────────
    private function calculerHeureArrivee(string $heureDepart, int $dureeMinutes): string
    {
        if (empty($heureDepart) || $dureeMinutes <= 0) {
            return '00:00:00';
        }

        try {
            $depart = new \DateTime($heureDepart);
            $depart->modify("+{$dureeMinutes} minutes");
            return $depart->format("H:i:s");
        } catch (\Exception $e) {
            return '00:00:00';
        }
    }


    // ────────────────────────────────
    // 🔹 Créer un covoiturage
    // ────────────────────────────────
    public function create(Covoiturage $covoiturage): bool
    {
        try {
            $stmt = $this->conn->prepare('
                INSERT INTO covoiturages
(id_utilisateur, id_vehicule, ville_depart, ville_arrivee, date_depart,
 heure_depart, heure_arrivee, distance_km, prix, nb_places, ecologique, statut, duree_minutes)
VALUES
(:id_utilisateur, :id_vehicule, :ville_depart, :ville_arrivee, :date_depart,
 :heure_depart, :heure_arrivee, :distance_km, :prix, :nb_places, :ecologique, :statut, :duree_minutes)
    
            ');

            return $stmt->execute([
                ':id_utilisateur' => $covoiturage->getIdUtilisateur(),
                ':id_vehicule'    => $covoiturage->getIdVehicule(),
                ':ville_depart'   => $covoiturage->getVilleDepart(),
                ':ville_arrivee'  => $covoiturage->getVilleArrivee(),
                ':date_depart'    => $covoiturage->getDateDepart(),
                ':heure_depart'   => $covoiturage->getHeureDepart(),
                ':heure_arrivee' => $this->calculerHeureArrivee(
                    $covoiturage->getHeureDepart(),
                    $covoiturage->getDureeMinutes()
                ),

                ':distance_km'    => $covoiturage->getDistanceKm(),
                ':prix'           => $covoiturage->getPrix(),
                ':nb_places'      => $covoiturage->getNbPlaces(),
                ':ecologique'     => $covoiturage->isEcologique(),
                ':statut'         => $covoiturage->getStatut(),
                ':duree_minutes'  => $covoiturage->getDureeMinutes()
            ]);
        } catch (PDOException $e) {
            $this->lastError = $e->getMessage();
            error_log('Erreur create : ' . $e->getMessage());
            return false;
        }
    }

    // ────────────────────────────────
    // 🔹 Hydration d’un covoiturage
    // ────────────────────────────────
    private function hydrateCovoiturage(array $row): Covoiturage
    {
        $c = new Covoiturage(
            (int)$row['id_utilisateur'],
            (int)$row['id_vehicule'],
            $row['ville_depart'],
            $row['ville_arrivee'],
            $row['date_depart'],
            $row['heure_depart'],
            (int)$row['duree_minutes'],
            (float)$row['distance_km'],
            (float)$row['prix'],
            (int)$row['nb_places'],
            (int)$row['ecologique'] === 1,
            $row['statut'],
            $row['pseudo'] ?? null,
            $row['photo'] ?? null,
            isset($row['note']) ? (float)$row['note'] : null
        );

        $c->setIdCovoiturage((int)$row['id_covoiturage']);
        $c->setVilleDepartNom($row['ville_depart_nom'] ?? '');
        $c->setVilleArriveeNom($row['ville_arrivee_nom'] ?? '');
        $c->setHeureArrivee(
            $this->calculerHeureArrivee($row['heure_depart'], (int)$row['duree_minutes'])
        );

        return $c;
    }

    // ────────────────────────────────
    // 🔹 Récupérer tous les covoiturages
    // ────────────────────────────────
    public function getAllEntities(): array
    {
        try {
            $stmt = $this->conn->query("
                SELECT c.*, u.pseudo, u.photo, vd.nom_ville AS ville_depart_nom, va.nom_ville AS ville_arrivee_nom
                FROM covoiturages c
                JOIN utilisateurs u ON c.id_utilisateur = u.id_utilisateur
                JOIN villes vd ON c.ville_depart = vd.id_ville
                JOIN villes va ON c.ville_arrivee = va.id_ville
                ORDER BY c.date_depart ASC, c.heure_depart ASC
            ");
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return array_map([$this, 'hydrateCovoiturage'], $rows);
        } catch (PDOException $e) {
            $this->lastError = $e->getMessage();
            return [];
        }
    }

    public function getEntityById(int $id): ?Covoiturage
    {
        try {
            $stmt = $this->conn->prepare("
                SELECT c.*, u.pseudo, u.photo, vd.nom_ville AS ville_depart_nom, va.nom_ville AS ville_arrivee_nom
                FROM covoiturages c
                JOIN utilisateurs u ON c.id_utilisateur = u.id_utilisateur
                JOIN villes vd ON c.ville_depart = vd.id_ville
                JOIN villes va ON c.ville_arrivee = va.id_ville
                WHERE c.id_covoiturage = :id
            ");
            $stmt->execute([':id' => $id]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row ? $this->hydrateCovoiturage($row) : null;
        } catch (PDOException $e) {
            $this->lastError = $e->getMessage();
            return null;
        }
    }

    public function getEntitiesByUtilisateur(int $userId): array
    {
        try {
            $stmt = $this->conn->prepare("
                SELECT c.*, u.pseudo, u.photo, vd.nom_ville AS ville_depart_nom, va.nom_ville AS ville_arrivee_nom
                FROM covoiturages c
                JOIN utilisateurs u ON c.id_utilisateur = u.id_utilisateur
                JOIN villes vd ON c.ville_depart = vd.id_ville
                JOIN villes va ON c.ville_arrivee = va.id_ville
                WHERE c.id_utilisateur = :userId
                ORDER BY c.date_depart DESC, c.heure_depart DESC
            ");
            $stmt->execute([':userId' => $userId]);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return array_map([$this, 'hydrateCovoiturage'], $rows);
        } catch (PDOException $e) {
            $this->lastError = $e->getMessage();
            return [];
        }
    }

    // ────────────────────────────────
    // 🔹 Filtrer par écologique
    // ────────────────────────────────
    public function filterByEcologique(int $eco = 1): array
    {
        try {
            $stmt = $this->conn->prepare("
                SELECT c.*, u.pseudo, u.photo, vd.nom_ville AS ville_depart_nom, va.nom_ville AS ville_arrivee_nom
                FROM covoiturages c
                JOIN utilisateurs u ON c.id_utilisateur = u.id_utilisateur
                JOIN villes vd ON c.ville_depart = vd.id_ville
                JOIN villes va ON c.ville_arrivee = va.id_ville
                WHERE c.ecologique = :eco
                ORDER BY c.date_depart ASC, c.heure_depart ASC
            ");
            $stmt->execute([':eco' => $eco]);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return array_map([$this, 'hydrateCovoiturage'], $rows);
        } catch (PDOException $e) {
            $this->lastError = $e->getMessage();
            return [];
        }
    }

    // ────────────────────────────────
    // 🔹 Suppression
    // ────────────────────────────────
    public function delete(int $id): bool
    {
        try {
            $stmt = $this->conn->prepare("DELETE FROM covoiturages WHERE id_covoiturage = :id");
            return $stmt->execute([':id' => $id]);
        } catch (PDOException $e) {
            $this->lastError = $e->getMessage();
            return false;
        }
    }

    // ────────────────────────────────
    // 🔹 Recherche souple
    // ────────────────────────────────
    public function rechercherCovoituragesSouples(string $villeDepart = '', string $villeArrivee = '', ?string $dateDepart = null): array
    {
        $sql = "
            SELECT c.*, vd.nom_ville AS ville_depart_nom, va.nom_ville AS ville_arrivee_nom
            FROM covoiturages c
            JOIN villes vd ON c.ville_depart = vd.id_ville
            JOIN villes va ON c.ville_arrivee = va.id_ville
            WHERE 1=1
        ";

        $params = [];
        if ($villeDepart !== '') {
            $sql .= " AND LOWER(vd.nom_ville) LIKE LOWER(:villeDepart)";
            $params[':villeDepart'] = "%$villeDepart%";
        }
        if ($villeArrivee !== '') {
            $sql .= " AND LOWER(va.nom_ville) LIKE LOWER(:villeArrivee)";
            $params[':villeArrivee'] = "%$villeArrivee%";
        }
        if ($dateDepart !== null) {
            $sql .= " AND c.date_depart = :dateDepart";
            $params[':dateDepart'] = $dateDepart;
        }

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ────────────────────────────────
    // 🔹 Mettre à jour le statut
    // ────────────────────────────────
    public function updateStatutCovoiturage(int $id, string $statut): bool
    {
        try {
            $stmt = $this->conn->prepare("
                UPDATE covoiturages SET statut = :statut WHERE id_covoiturage = :id
            ");
            return $stmt->execute([':statut' => $statut, ':id' => $id]);
        } catch (\PDOException $e) {
            $this->lastError = $e->getMessage();
            return false;
        }
    }

    // ────────────────────────────────
    // 🔹 Récupérer la dernière erreur
    // ────────────────────────────────
    public function getLastError(): ?string
    {
        return $this->lastError;
    }
}
