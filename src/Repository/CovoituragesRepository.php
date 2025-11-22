<?php

namespace Repository;

use Config\Database;
use Entity\Marque;
use Entity\Utilisateur;
use Entity\Vehicule;
use PDO;
use PDOException;
use Entity\Covoiturage;
use DateTime;


class CovoituragesRepository
{
    private ?PDO $conn = null;
    private ?string $lastError = null;
    private CreditsRepository $creditsRepo;

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
        $this->creditsRepo = new CreditsRepository($this->conn);
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

        // --- Infos basiques ---
        $covoiturage->setIdCovoiturage((int)($row['id_covoiturage'] ?? 0));
        $covoiturage->setIdUtilisateur((int)($row['id_utilisateur'] ?? 0));
        $covoiturage->setVilleDepart($row['ville_depart'] ?? '');
        $covoiturage->setVilleArrivee($row['ville_arrivee'] ?? '');
        $covoiturage->setVilleDepartNom($row['ville_depart_nom'] ?? '');
        $covoiturage->setVilleArriveeNom($row['ville_arrivee_nom'] ?? '');

        // --- Date + heure ---
        $covoiturage->setDateDepart($row['date_depart'] ?? '—');
        $covoiturage->setHeureDepart($row['heure_depart'] ?? '00:00:00');

        // Durée et heure d'arrivée
        $dureeMinutes = isset($row['duree_minutes']) ? (int)$row['duree_minutes'] : 0;
        $covoiturage->setDureeMinutes($dureeMinutes);
        $covoiturage->setHeureArrivee($this->calculerHeureArrivee(
            $covoiturage->getHeureDepart(),
            $dureeMinutes
        ));

        // Distance
        $covoiturage->setDistanceKm(isset($row['distance_km']) ? (float)$row['distance_km'] : 0.0);

        // --- Places / prix ---
        $covoiturage->setNbPlaces(isset($row['nb_places']) ? (int)$row['nb_places'] : 0);
        $covoiturage->setPrix(isset($row['prix']) ? (float)$row['prix'] : 0.0);

        // --- Conducteur ---
        $utilisateur = new Utilisateur();
        $utilisateur->setIdUtilisateur((int)($row['id_utilisateur'] ?? 0));
        $utilisateur->setPseudo($row['pseudo'] ?? '');
        $utilisateur->setPhoto($row['photo'] ?? '');
        $utilisateur->setNote(isset($row['note_conducteur']) ? (float)$row['note_conducteur'] : null);
        $covoiturage->setConducteur($utilisateur);

        // --- Véhicule (nom + modèle seulement) ---
        if (!empty($row['id_vehicule'])) {
            $vehicule = new Vehicule();

            $vehicule->setIdVehicule((int)$row['id_vehicule']);
            $vehicule->setIdUtilisateur((int)($row['id_utilisateur'] ?? 0));
            $vehicule->setModele($row['vehicule_modele'] ?? '—');
            $vehicule->setCouleur($row['couleur'] ?? '—');
            $vehicule->setEnergie($row['energie'] ?? '—');
            $vehicule->setImmatriculation($row['immatriculation'] ?? '—');
            $vehicule->setDatePremiereImmatriculation($row['date_premiere_immatriculation'] ?? null);
            $vehicule->setNbPlaces(isset($row['nb_places_vehicule']) ? (int)$row['nb_places_vehicule'] : null);

            // Lier le véhicule au covoiturage
            $covoiturage->setVehicule($vehicule);
            $covoiturage->setIdVehicule((int)$row['id_vehicule']);

            // Stocker directement le nom de la marque pour l'affichage
            $covoiturage->setVehiculeNom($row['vehicule_marque'] ?? '—');
        } else {
            // Si aucun véhicule, on met des valeurs par défaut
            $covoiturage->setVehiculeNom('—');
            $covoiturage->setVehiculeModele('—');
        }

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
    public function findAll(): ?Covoiturage
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
    public function findById(int $id): ?Covoiturage
    {
        try {
            $stmt = $this->conn->prepare("
                               SELECT 
                        c.*,
                        u.id_utilisateur,
                        u.pseudo,
                        u.photo,
                    
                        -- Moyenne des notes reçues
                        (
                            SELECT COALESCE(AVG(a1.note), 0)
                            FROM avis a1
                            WHERE a1.id_receveur = u.id_utilisateur
                        ) AS note_utilisateur,
                    
                        -- Avis reçus (liste)
                        (
                            SELECT JSON_ARRAYAGG(
                                JSON_OBJECT(
                                    'id_avis', a2.id_avis,
                                    'note', a2.note,
                                    'commentaire', a2.commentaire,
                                    'id_emetteur', a2.id_emetteur
                                )
                            )
                            FROM avis a2
                            WHERE a2.id_receveur = u.id_utilisateur
                        ) AS avis_recus,
                    
                        -- Avis envoyés (liste)
                        (
                            SELECT JSON_ARRAYAGG(
                                JSON_OBJECT(
                                    'id_avis', a3.id_avis,
                                    'note', a3.note,
                                    'commentaire', a3.commentaire,
                                    'id_receveur', a3.id_receveur
                                )
                            )
                            FROM avis a3
                            WHERE a3.id_emetteur = u.id_utilisateur
                        ) AS avis_envoyes,
                    
                        v.id_vehicule,
                        m.nom_marque AS vehicule_nom,
                        v.modele AS vehicule_modele,
                        vd.nom_ville AS ville_depart_nom,
                        va.nom_ville AS ville_arrivee_nom
                    
                    FROM covoiturages c
                    LEFT JOIN utilisateurs u ON c.id_utilisateur = u.id_utilisateur
                    LEFT JOIN vehicules v ON c.id_vehicule = v.id_vehicule
                    LEFT JOIN marques m ON v.id_marque = m.id_marque
                    LEFT JOIN villes vd ON c.ville_depart = vd.id_ville
                    LEFT JOIN villes va ON c.ville_arrivee = va.id_ville
                    
                    WHERE c.id_covoiturage = :id;


        ");
            $stmt->execute([':id' => $id]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$row) return null;

            // Utiliser hydrateCovoiturage pour que tout soit correctement rempli
            return $this->hydrateCovoiturage($row);

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


    // ────────────────────────────────
    // 🔹 Recherche et filtrage
    // ────────────────────────────────

    /**
     * Filtrer par covoiturage écologique
     */
    public function findByEcologique(int $eco = 1): array
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
            $stmt->execute([':ecologique' => $eco]);
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

    public function findCovoiturageByDepartArriveeDate(?string $depart, ?string $arrivee, ?string $date): array
    {
        // Normalisation PHP identique à SQL (translittération ASCII)
        $normalize = function ($str) {
            $str = strtolower(iconv('UTF-8', 'ASCII//TRANSLIT', $str));
            return $str;
        };

        $departNorm = $depart ? $normalize($depart) : null;
        $arriveeNorm = $arrivee ? $normalize($arrivee) : null;

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

        // Normalisation SQL fiable (enlève TOUS les accents)
        $sqlNormalizeDepart = "LOWER(CONVERT(vd.nom_ville USING ASCII))";
        $sqlNormalizeArrivee = "LOWER(CONVERT(va.nom_ville USING ASCII))";

        if (!empty($departNorm)) {
            $sql .= " AND $sqlNormalizeDepart LIKE :depart";
            $params[':depart'] = '%' . $departNorm . '%';
        }

        if (!empty($arriveeNorm)) {
            $sql .= " AND $sqlNormalizeArrivee LIKE :arrivee";
            $params[':arrivee'] = '%' . $arriveeNorm . '%';
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

        $resultats = [];
        foreach ($rows as $row) {
            $resultats[] = $this->hydrateCovoiturage($row);
        }

        return $resultats;
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
        SELECT 
            c.*,
            u.pseudo,
            u.photo,
            v.modele AS vehicule_modele,
            m.nom_marque AS vehicule_marque,
            vd.nom_ville AS ville_depart_nom,
            va.nom_ville AS ville_arrivee_nom,
            (
                SELECT AVG(note)
                FROM avis
                WHERE id_receveur = u.id_utilisateur
            ) AS note_conducteur
        FROM covoiturages c
        JOIN utilisateurs u ON c.id_utilisateur = u.id_utilisateur
        LEFT JOIN vehicules v ON v.id_vehicule = c.id_vehicule
        LEFT JOIN marques m ON m.id_marque = v.id_marque
        JOIN villes vd ON c.ville_depart = vd.id_ville
        JOIN villes va ON c.ville_arrivee = va.id_ville
        WHERE c.id_utilisateur = :userId
        ORDER BY c.date_depart ASC, c.heure_depart ASC
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


    //===================MISE A JOUR NBPLACES ET STATUT COVOITURAGE==================//
    /**
     * Met à jour le nombre de places et ajuste le statut automatiquement
     *
     * @param int $covoiturageId ID du covoiturage
     * @param int $nbPlaces Nombre de places restantes
     * @param string|null $statut Nouveau statut du covoiturage (optionnel)
     * @return bool Succès ou échec
     */
    public function updatePlacesAndStatut(int $covoiturageId, int $nbPlaces, ?string $statut = null): bool
    {
        try {
            // Si nbPlaces = 0, on force le statut à "complet"
            if ($nbPlaces <= 0) {
                $statut = 'complet';
                $nbPlaces = 0; // sécurité
            } elseif ($statut === null) {
                // Si le statut n'est pas fourni et qu'il reste des places
                $statut = 'disponible';
            }

            $stmt = $this->conn->prepare("
            UPDATE covoiturages
            SET nb_places = :nbPlaces, statut = :statut
            WHERE id_covoiturage = :id
        ");

            $success = $stmt->execute([
                ':nbPlaces' => $nbPlaces,
                ':statut'   => $statut,
                ':id'       => $covoiturageId
            ]);

            if (!$success) {
                $this->lastError = implode(" | ", $stmt->errorInfo());
            }

            return $success;

        } catch (\PDOException $e) {
            $this->lastError = $e->getMessage();
            error_log("Erreur updatePlacesAndStatut : " . $e->getMessage());
            return false;
        }
    }


    public function update(Covoiturage $covoiturage): bool
    {
        $sql = "UPDATE covoiturages SET 
        ville_depart = :ville_depart,
        ville_arrivee = :ville_arrivee,
        date_depart = :date_depart,
        heure_depart = :heure_depart,
        duree_minutes = :duree_minutes,
        prix = :prix,
        nb_places = :nb_places,
        description = :description
        WHERE id_covoiturage = :id";

        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':ville_depart' => $covoiturage->getVilleDepart(),
            ':ville_arrivee' => $covoiturage->getVilleArrivee(),
            ':date_depart' => $covoiturage->getDateDepart(),
            ':heure_depart' => $covoiturage->getHeureDepart(),
            ':duree_minutes' => $covoiturage->getDureeMinutes(),
            ':prix' => $covoiturage->getPrix(),
            ':nb_places' => $covoiturage->getNbPlaces(),
            ':description' => $covoiturage->getDescription(),
            ':id' => $covoiturage->getIdCovoiturage()
        ]);
    }



}
