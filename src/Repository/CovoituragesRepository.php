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

// Créer un covoiturage
public function create(Covoiturage $covoiturage): bool
{
try {
$stmt = $this->conn->prepare('
INSERT INTO covoiturages
(id_utilisateur, id_vehicule, ville_depart, ville_arrivee, date_depart,
heure_depart, distance_km, nb_places, ecologique, statut)
VALUES
(:id_utilisateur, :id_vehicule, :ville_depart, :ville_arrivee, :date_depart,
:heure_depart, :distance_km, :nb_places, :ecologique, :statut)
');

return $stmt->execute([
':id_utilisateur' => $covoiturage->getIdUtilisateur(),
':id_vehicule' => $covoiturage->getIdVehicule(),
':ville_depart' => $covoiturage->getVilleDepart(),
':ville_arrivee' => $covoiturage->getVilleArrivee(),
':date_depart' => $covoiturage->getDateDepart(),
':heure_depart' => $covoiturage->getHeureDepart(),
':distance_km' => $covoiturage->getDistanceKm(),
':nb_places' => $covoiturage->getNbPlaces(),
':ecologique' => $covoiturage->isEcologique(),
':statut' => $covoiturage->getStatut(),
]);
} catch (PDOException $e) {
$this->lastError = $e->getMessage();
error_log('Erreur lors de la création du covoiturage : ' . $e->getMessage());
return false;
}
}

// Récupérer covoiturages par utilisateur avec noms de villes
public function getCovoituragesByUtilisateur(int $userId): array
{
try {
$stmt = $this->conn->prepare('
SELECT c.*,
vd.nom_ville AS ville_depart_nom,
va.nom_ville AS ville_arrivee_nom
FROM covoiturages c
JOIN villes vd ON c.ville_depart = vd.id_ville
JOIN villes va ON c.ville_arrivee = va.id_ville
WHERE c.id_utilisateur = :user_id
ORDER BY c.date_depart DESC, c.heure_depart DESC
');
$stmt->execute([':user_id' => $userId]);
return $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
error_log('Erreur getCovoituragesByUtilisateur : ' . $e->getMessage());
return [];
}
}

// Filtrer par covoiturages écologiques
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

// Filtrer par prix maximum
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

// Filtrer par durée maximale
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

public function getLastError(): ?string
{
return $this->lastError;
}
}
