<?php

namespace Repository;

use Config\Database;
use Entity\Vehicule;
use PDO;
use PDOException;


class VehiculesRepository
{
    private PDO $conn;

    public function __construct()
    {
        $this->conn = Database::getConnection();
    }

    public function create(Vehicule $data): bool
    {
        try {
            $stmt = $this->conn->prepare('
            INSERT INTO vehicules
                (id_utilisateur, id_marque, modele, couleur, energie, immatriculation, date_premiere_immatriculation, nb_places)
            VALUES
                (:id_utilisateur, :id_marque, :modele, :couleur, :energie, :immatriculation, :date_premiere_immatriculation, :nb_places)
        ');

            return $stmt->execute([
                ':id_utilisateur' => $data->getIdUtilisateur(),
                ':id_marque' => $data->getIdMarque(),
                ':modele' => $data->getModele(),
                ':couleur' => $data->getCouleur(),
                ':energie' => $data->getEnergie(),
                ':immatriculation' => $data->getImmatriculation(),
                ':date_premiere_immatriculation' => $data->getDatePremiereImmatriculation(),
                ':nb_places' => $data->getNbPlaces()
            ]);

        } catch (\PDOException $e) {
            error_log('Erreur création véhicule : ' . $e->getMessage());
            return false;
        }
    }

    // Récupérer tous les véhicules d'un utilisateur
    public function getVehiculesByUtilisateur(int $userId): array
    {
        try {
            $stmt = $this->conn->prepare('SELECT * FROM vehicules WHERE id_utilisateur = :id_utilisateur');
            $stmt->execute([':id_utilisateur' => $userId]);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $vehicules = [];
            foreach ($rows as $row) {
                $vehicule = new Vehicule(
                    $row['id_utilisateur'],
                    $row['id_marque'],
                    $row['modele'],
                    $row['couleur'],
                    $row['energie'],
                    $row['immatriculation'],
                    $row['date_premiere_immatriculation'],
                    $row['nb_places']
                );
                $vehicule->setIdVehicule((int)$row['id_vehicule']);
                $vehicules[] = $vehicule;
            }

            return $vehicules;

        } catch (PDOException $e) {
            error_log('Erreur getVehiculesByUtilisateur : ' . $e->getMessage());
            return [];
        }
    }

    // Ajouter un véhicule

    public function getAllMarques(): array
    {
        $stmt = $this->conn->query('SELECT * FROM marques ORDER BY nom_marque ASC');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
