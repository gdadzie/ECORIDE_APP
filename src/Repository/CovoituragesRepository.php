<?php

namespace Repository;

use Config\Database;
use Entity\Utilisateur;
use PDO;
use PDOException;
use Entity\Covoiturage;
use DateTime;


class CovoituragesRepository
{
    private ?PDO $conn = null;
    private ?string $lastError = null;

    // ────────────────────────────────
    // 🔹 Constructeur
    // ────────────────────────────────
    public function __construct()
    {
        $this->conn = Database::getConnection();
        if (!$this->conn) {
            $error = Database::getLastError() ?? "Connexion à la base de données impossible.";
            throw new \RuntimeException($error);
        }
    }

    // ────────────────────────────────
    // 🔹 Méthodes privées utilitaires
    // ────────────────────────────────

    /**
     * Calcul automatique de l’heure d’arrivée
     */
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

    private function hydrateCovoiturage(array $row): Covoiturage
    {
        $covoiturage = new Covoiturage();
        $covoiturage->setIdCovoiturage($row['id_covoiturage']);
        $covoiturage->setIdUtilisateur($row['id_utilisateur']);
        $covoiturage->setVilleDepart($row['ville_depart']);
        $covoiturage->setVilleArrivee($row['ville_arrivee']);

        // -----------------------------
        // FORMATAGE DE LA DATE EN FRANÇAIS
        // -----------------------------
        // Définir la locale française (Unix et Windows)
        setlocale(LC_TIME, 'fr_FR.UTF-8', 'fra', 'fr_FR', 'French_France');

        if (!empty($row['date_depart'])) {
            $timestamp = strtotime($row['date_depart']);
            // Format : lun 15 jan 2025
            $dt = new DateTime($row['date_depart']);
            $formattedDate = $dt->format('d/m/Y'); // ou 'd F Y' selon le format souhaité

        } else {
            $covoiturage->setDateDepart('—');
        }

        $covoiturage->setHeureDepart($row['heure_depart']);

        // Calcul de l'heure d'arrivée
        $heureArrivee = $this->calculerHeureArrivee(
            $row['heure_depart'] ?? null,
            (int)($row['duree_minutes'] ?? 0)
        );
        $covoiturage->setHeureArrivee($heureArrivee);

        $covoiturage->setNbPlaces($row['nb_places']);
        $covoiturage->setPrix($row['prix']);

        // Villes jointes
        $covoiturage->setVilleDepartNom($row['ville_depart_nom']);
        $covoiturage->setVilleArriveeNom($row['ville_arrivee_nom']);

        // --- Conducteur ---
        $conducteur = new Utilisateur();
        $conducteur->setIdUtilisateur($row['id_utilisateur']);
        $conducteur->setPseudo($row['pseudo']);
        $conducteur->setPhoto($row['photo']);
        $conducteur->setNote($row['note_conducteur'] ?? null);
        $covoiturage->setConducteur($conducteur);

        // --- Véhicule ---
        $covoiturage->setVehiculeNom($row['vehicule_nom'] ?? null);
        $covoiturage->setVehiculeModele($row['vehicule_modele'] ?? null);

        return $covoiturage;
    }


    // ────────────────────────────────
    // 🔹 CRUD de base
    // ────────────────────────────────

    /**
     * Créer un covoiturage
     */
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
                ':id_vehicule' => $covoiturage->getIdVehicule(),
                ':ville_depart' => $covoiturage->getVilleDepart(),
                ':ville_arrivee' => $covoiturage->getVilleArrivee(),
                ':date_depart' => $covoiturage->getDateDepart(),
                ':heure_depart' => $covoiturage->getHeureDepart(),
                ':heure_arrivee' => $this->calculerHeureArrivee(
                    $covoiturage->getHeureDepart(),
                    $covoiturage->getDureeMinutes()
                ),
                ':distance_km' => $covoiturage->getDistanceKm(),
                ':prix' => $covoiturage->getPrix(),
                ':nb_places' => $covoiturage->getNbPlaces(),
                ':ecologique' => $covoiturage->isEcologique(),
                ':statut' => $covoiturage->getStatut(),
                ':duree_minutes' => $covoiturage->getDureeMinutes()
            ]);
        } catch (PDOException $e) {
            $this->lastError = $e->getMessage();
            error_log('Erreur create : ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Récupérer tous les covoiturages
     */
    public function getAllEntities(): array
    {
        try {
            $stmt = $this->conn->query("
            SELECT 
                c.*, 
                u.pseudo, 
                u.photo, 
                v.nom AS vehicule_nom, 
                v.modele AS vehicule_modele,
                vd.nom_ville AS ville_depart_nom, 
                va.nom_ville AS ville_arrivee_nom
            FROM covoiturages c
            JOIN utilisateurs u 
                ON c.id_utilisateur = u.id_utilisateur
            LEFT JOIN vehicules v 
                ON v.id_vehicule = c.id_vehicule
            JOIN villes vd 
                ON c.ville_depart = vd.id_ville
            JOIN villes va 
                ON c.ville_arrivee = va.id_ville
            ORDER BY c.date_depart ASC, c.heure_depart ASC
        ");

            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return array_map([$this, 'hydrateCovoiturage'], $rows);

        } catch (PDOException $e) {
            $this->lastError = $e->getMessage();
            return [];
        }
    }


    /**
     * Récupérer un covoiturage par son ID
     */
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

    /**
     * Supprimer un covoiturage
     */
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

    /**
     * Mettre à jour le statut d’un covoiturage
     */
    public function updateStatutCovoiturage(int $id, string $statut): bool
    {
        try {
            $stmt = $this->conn->prepare("UPDATE covoiturages SET statut = :statut WHERE id_covoiturage = :id");
            return $stmt->execute([':statut' => $statut, ':id' => $id]);
        } catch (PDOException $e) {
            $this->lastError = $e->getMessage();
            return false;
        }
    }

    // ────────────────────────────────
    // 🔹 Recherche et filtrage
    // ────────────────────────────────

    /**
     * Filtrer par covoiturage écologique
     */
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

    /**
     * Recherche souple avec critères facultatifs
     */
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

    /**
     * Recherche stricte (départ, arrivée, date)
     */
    /**
     * Recherche flexible de covoiturages
     * @param string|null $depart Ville de départ (partiel possible)
     * @param string|null $arrivee Ville d'arrivée (partiel possible)
     * @param string|null $date Date de départ (format 'YYYY-MM-DD')
     * @return array
     */
    /**
     * Recherche flexible de covoiturages
     * @param string|null $depart Ville de départ (partiel possible)
     * @param string|null $arrivee Ville d'arrivée (partiel possible)
     * @param string|null $date Date de départ (format 'YYYY-MM-DD')
     * @return Entity\Covoiturage[]
     */
    public function rechercherCovoiturages(?string $depart, ?string $arrivee, ?string $date): array
    {
        $sql = "
        SELECT 
            c.*,
            u.pseudo,
            u.photo,
            m.nom_marque AS vehicule_marque,
            v.modele AS vehicule_modele,
            vd.nom_ville AS ville_depart_nom,
            va.nom_ville AS ville_arrivee_nom,
            (SELECT AVG(note)
             FROM avis
             WHERE id_receveur = u.id_utilisateur
            ) AS note_conducteur
        FROM covoiturages c
        JOIN utilisateurs u ON c.id_utilisateur = u.id_utilisateur
        LEFT JOIN vehicules v ON v.id_vehicule = c.id_vehicule
        LEFT JOIN marques m ON m.id_marque = v.id_marque
        JOIN villes vd ON c.ville_depart = vd.id_ville
        JOIN villes va ON c.ville_arrivee = va.id_ville
        WHERE 1=1
    ";

        $params = [];

        if (!empty($depart)) {
            $sql .= " AND vd.nom_ville LIKE :depart";
            $params[':depart'] = "%$depart%";
        }
        if (!empty($arrivee)) {
            $sql .= " AND va.nom_ville LIKE :arrivee";
            $params[':arrivee'] = "%$arrivee%";
        }
        if (!empty($date)) {
            $sql .= " AND c.date_depart = :date";
            $params[':date'] = $date;
        }

        $stmt = $this->conn->prepare($sql);

        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }

        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $covoits = [];
        foreach ($rows as $row) {
            // Hydrate l'objet Covoiturage
            $covoiturage = $this->hydrateCovoiturage($row);

            // Ne pas formater la date ici, garder la date brute SQL (YYYY-MM-DD)
            $covoits[] = $covoiturage;
        }

        return $covoits;
    }

    // ────────────────────────────────
    // 🔹 Méthodes utilisateur spécifiques
    // ────────────────────────────────

    /**
     * Récupération des covoiturages d’un utilisateur
     */
    public function getCovoituragesByUtilisateur(int $userId): array
    {
        $sql = "
            SELECT c.*,
                   u.pseudo,
                   u.photo,
                   vd.nom_ville AS ville_depart_nom,
                   va.nom_ville AS ville_arrivee_nom,
                   (
                       SELECT AVG(note)
                       FROM avis
                       WHERE id_receveur = u.id_utilisateur
                   ) AS note_conducteur
            FROM covoiturages c
            JOIN utilisateurs u ON c.id_utilisateur = u.id_utilisateur
            JOIN villes vd ON c.ville_depart = vd.id_ville
            JOIN villes va ON c.ville_arrivee = va.id_ville
            WHERE c.id_utilisateur = :userId
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':userId', $userId, PDO::PARAM_INT);
        $stmt->execute();

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $covoits = [];
        foreach ($rows as $row) {
            $covoits[] = $this->hydrateCovoiturage($row);
        }

        return $covoits;
    }

    // ────────────────────────────────
    // 🔹 Gestion d’erreurs
    // ────────────────────────────────

    /**
     * Récupérer la dernière erreur
     */
    public function getLastError(): ?string
    {
        return $this->lastError;
    }

    public function getNoteMoyenneConducteur(int $idConducteur): ?float
    {
        $stmt = $this->conn->prepare("SELECT AVG(note) AS moyenne FROM avis WHERE id_receveur = :id");
        $stmt->execute(['id' => $idConducteur]);
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);

        return $result && $result['moyenne'] !== null ? (float)$result['moyenne'] : null;
    }

}
