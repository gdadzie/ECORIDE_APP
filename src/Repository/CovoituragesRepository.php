<?php

namespace Repository;

use Entity\Covoiturage;
use Config\Database;
use PDO;
use PDOException;

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
    // 🔹 Créer un covoiturage
    // ────────────────────────────────
    public function create(Covoiturage $covoiturage): bool
    {
        try {
            $stmt = $this->conn->prepare('
                INSERT INTO covoiturages
                (id_utilisateur, id_vehicule, ville_depart, ville_arrivee, date_depart,
                 heure_depart, distance_km, prix, nb_places, ecologique, statut, duree_minutes)
                VALUES
                (:id_utilisateur, :id_vehicule, :ville_depart, :ville_arrivee, :date_depart,
                 :heure_depart, :distance_km,:prix, :nb_places, :ecologique, :statut, :duree_minutes)
            ');

            return $stmt->execute([
                ':id_utilisateur' => $covoiturage->getIdUtilisateur(),
                ':id_vehicule'    => $covoiturage->getIdVehicule(),
                ':ville_depart'   => $covoiturage->getVilleDepart(),
                ':ville_arrivee'  => $covoiturage->getVilleArrivee(),
                ':date_depart'    => $covoiturage->getDateDepart(),
                ':heure_depart'   => $covoiturage->getHeureDepart(),
                ':distance_km'    => $covoiturage->getDistanceKm(),
                ':prix'           => $covoiturage->getPrix(),
                ':nb_places'      => $covoiturage->getNbPlaces(),
                ':ecologique'     => $covoiturage->isEcologique(),
                ':statut'         => $covoiturage->getStatut(),
                ':duree_minutes'  => $covoiturage->getDureeMinutes()
            ]);
        } catch (PDOException $e) {
            $this->lastError = $e->getMessage();
            error_log('Erreur lors de la création du covoiturage : ' . $e->getMessage());
            return false;
        }
    }

    // ────────────────────────────────
    // 🔹 Récupérer covoiturages par utilisateur (avec noms de villes)
    // ────────────────────────────────
    public function getCovoiturageByUtilisateur(int $id): ?array
    {
        try {

            $sql = "
        SELECT 
            c.id_covoiturage,
            c.date_depart,
            c.heure_depart,
            c.distance_km,
            c.prix,
            c.nb_places,
            c.ecologique,
            c.duree_minutes,
            c.statut,
            
            u.pseudo AS conducteur,
            u.photo AS photo_conducteur,
            
            
            vd.nom_ville AS ville_depart_nom,
            va.nom_ville AS ville_arrivee_nom
            
        FROM covoiturages c
        JOIN utilisateurs u ON c.id_utilisateur = u.id_utilisateur
        JOIN villes vd ON c.ville_depart = vd.id_ville
        JOIN villes va ON c.ville_arrivee = va.id_ville
        WHERE c.id_covoiturage = :id
    ";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':id' => $id]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ?: null;
        } catch (\PDOException $e) {
            error_log('Erreur getCovoiturageById : ' . $e->getMessage());
            return null;
        }
    }


    // ────────────────────────────────
    // 🔹 Filtrer par covoiturages écologiques
    // ────────────────────────────────
    public function filterByEcologique(int $ecologique): array
    {
        try {
            $stmt = $this->conn->prepare('
                SELECT c.*,
                       vd.nom_ville AS ville_depart_nom,
                       va.nom_ville AS ville_arrivee_nom
                FROM covoiturages c
                JOIN villes vd ON c.ville_depart = vd.id_ville
                JOIN villes va ON c.ville_arrivee = va.id_ville
                WHERE c.ecologique = :ecologique
            ');

            $stmt->execute([':ecologique' => $ecologique]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Erreur filterByEcologique : ' . $e->getMessage());
            return [];
        }
    }

    // ────────────────────────────────
    // 🔹 Filtrer par prix maximum
    // ────────────────────────────────
    public function filterByPrixMax(float $prixMax): array
    {
        try {
            $stmt = $this->conn->prepare('
                SELECT c.*,
                       vd.nom_ville AS ville_depart_nom,
                       va.nom_ville AS ville_arrivee_nom
                FROM covoiturages c
                JOIN villes vd ON c.ville_depart = vd.id_ville
                JOIN villes va ON c.ville_arrivee = va.id_ville
                WHERE c.prix <= :prixMax
                ORDER BY c.prix ASC
            ');

            $stmt->execute([':prixMax' => $prixMax]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Erreur filterByPrixMax : ' . $e->getMessage());
            return [];
        }
    }

    // ────────────────────────────────
    // 🔹 Filtrer par durée maximale
    // ────────────────────────────────
    public function filterByDureeMax(int $dureeMax): array
    {
        try {
            $stmt = $this->conn->prepare('
                SELECT c.*,
                       vd.nom_ville AS ville_depart_nom,
                       va.nom_ville AS ville_arrivee_nom
                FROM covoiturages c
                JOIN villes vd ON c.ville_depart = vd.id_ville
                JOIN villes va ON c.ville_arrivee = va.id_ville
                WHERE c.duree_minutes <= :dureeMax
                ORDER BY c.duree_minutes ASC
            ');

            $stmt->execute([':dureeMax' => $dureeMax]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Erreur filterByDureeMax : ' . $e->getMessage());
            return [];
        }
    }

    // ────────────────────────────────
    // 🔹 Recherche souple par villes (LIKE)
    // ────────────────────────────────
    public function rechercherCovoituragesSouples(string $villeDepart, string $villeArrivee, ?string $dateDepart = null): array
    {
        $query = "
                SELECT c.*, vd.nom_ville AS ville_depart_nom, va.nom_ville AS ville_arrivee_nom
                FROM covoiturages c
                JOIN villes vd ON c.ville_depart = vd.id_ville
                JOIN villes va ON c.ville_arrivee = va.id_ville
                WHERE (vd.nom_ville LIKE :villeDepart OR va.nom_ville LIKE :villeDepart
                   OR vd.nom_ville LIKE :villeArrivee OR va.nom_ville LIKE :villeArrivee)
        ";

        if (!empty($dateDepart)) {
            $query .= " AND c.date_depart = :dateDepart";
        }


        $params = [];

        if (!empty($villeDepart)) {
            $query .= " AND LOWER(vd.nom_ville) LIKE LOWER(:villeDepart)";
            $params[':villeDepart'] = "%$villeDepart%";
        }

        if (!empty($villeArrivee)) {
            $query .= " AND LOWER(va.nom_ville) LIKE LOWER(:villeArrivee)";
            $params[':villeArrivee'] = "%$villeArrivee%";
        }

        if (!empty($dateDepart)) {
            $query .= " AND c.date_depart = :dateDepart";
            $params[':dateDepart'] = $dateDepart;
        }

        $stmt = $this->conn->prepare($query);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }



    // ────────────────────────────────
    // 🔹 Récupérer la dernière erreur
    // ────────────────────────────────
    public function getLastError(): ?string
    {
        return $this->lastError;
    }

    /**
     * Récupère tous les covoiturages présents dans la base de données
     * avec les noms des villes de départ et d'arrivée.
     *
     * @return array Tableau associatif contenant tous les covoiturages
     */
    public function getAllCovoiturages(): array
    {
        try {
            $stmt = $this->conn->query("
            SELECT c.*, 
                   vd.nom_ville AS ville_depart_nom, 
                   va.nom_ville AS ville_arrivee_nom
            FROM covoiturages c
            JOIN villes vd ON c.ville_depart = vd.id_ville
            JOIN villes va ON c.ville_arrivee = va.id_ville
            ORDER BY c.date_depart ASC, c.heure_depart ASC
        ");

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            error_log('Erreur getAllCovoiturages : ' . $e->getMessage());
            return [];
        }
    }

    // ────────────────────────────────
// 🔹 Mettre à jour le statut d'un covoiturage
// ────────────────────────────────
    public function updateStatutCovoiturage(int $id, string $statut): bool
    {
        try {
            $stmt = $this->conn->prepare("
            UPDATE covoiturages
            SET statut = :statut
            WHERE id_covoiturage = :id
        ");
            return $stmt->execute([
                ':statut' => $statut,
                ':id'     => $id
            ]);
        } catch (\PDOException $e) {
            $this->lastError = $e->getMessage();
            error_log('Erreur updateStatutCovoiturage : ' . $e->getMessage());
            return false;
        }
    }


}
