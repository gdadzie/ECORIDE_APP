<?php


namespace Entity;
use PDO;
use PDOException;

class Voiture
{
    private PDO $conn;
    private string $table = 'voiture';
    public function __construct(PDO $conn){
        $this->conn = $conn;
    }

    // ---------------- CREATE ----------------
    public function createVoiture(
        string $id_utilisateur,
        string $id_marque,
        string $modele,
        string $couleur,
        string $energie,
        string $immatriculation,
        string $date_premiere_immatriculation,
        int $nb_places,
    ):bool{
        try {
            $stmt= $this->conn->prepare("
            INSERT INTO $this->table($id_utilisateur,$id_marque,$modele,$couleur,$energie,$immatriculation,$date_premiere_immatriculation,$nb_places)
            VALUES (:$id_utilisateur, :$id_marque, :$modele, :couleur, :energie, :immatriculation, :date_premiere_immatriculation, :nb_places)
            
            ");
            return $stmt->execute([
                ':id_utilisateur' => $id_utilisateur,
                ':id_marque' => $id_marque,
                ':modele' => $modele,
                ':couleur' => $couleur,
                ':energie' => $energie,
                ':immatriculation' => $immatriculation,
                ':date_premiere_immatriculation' => $date_premiere_immatriculation,
                ':nb_places' => $nb_places
            ]);

        }catch (PDOException $e){
            error_log("Erreur création voiture: " . $e->getMessage());
            return false;
        }

    }

    // ---------------- READ BY ID ----------------
    public function readVoiture(int $id_voiture): ?array
    {
        try {
            $sql= "SELECT * FROM $this->table WHERE id_voiture = :id_voiture";
            $stmt= $this->conn->prepare($sql);
            $stmt->bindParam(':id_voiture', $id_voiture, PDO::PARAM_INT);
            $stmt->execute();

            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ?? null;
        }catch (PDOException $e){
            error_log("Erreur lecture voiture : " . $e->getMessage());
            return null;
        }

    }

    // ---------------- UPDATE ----------------
 public function updateVoiture(int $id_voiture,array $data): bool
 {
     try {
         $sql = "UPDATE $this->table 
                SET id_voiture = :id_voiture,
                    id_marque = :id_marque,
                    modele = :modele,
                    couleur = :couleur,
                    energie = :energie,
                    immatriculation = :immatriculation,
                    date_premiere_immatriculation = :date_premiere_immatriculation
                    WHERE id_voiture = :id_voiture";
         $stmt = $this->conn->prepare($sql);
         $stmt->bindParam(':id_voiture', $id_voiture, PDO::PARAM_INT);
         $stmt->bindParam(':id_marque', $data['id_marque'], PDO::PARAM_INT);
         $stmt->bindParam(':modele', $data['modele'], PDO::PARAM_STR);
         $stmt->bindParam(':couleur', $data['couleur'], PDO::PARAM_STR);
         $stmt->bindParam(':energie', $data['energie'], PDO::PARAM_STR);
         $stmt->bindParam(':immatriculation', $data['immatriculation'], PDO::PARAM_STR);
         $stmt->bindParam(':date_premiere_immatriculation', $data['date_premiere_immatriculation'], PDO::PARAM_STR);
         $stmt->bindParam(':nb_places', $data['nb_places'], PDO::PARAM_INT);
         return $stmt->execute();
     } catch (PDOException $e) {
         error_log("Erreur mise à jour voiture : " . $e->getMessage());
         return false;
     }
 }
     public function deleteVoiture(int $id_voiture): bool
     {

         try {
             $sql = " DELETE FROM $this->table WHERE id_voiture = :id_voiture";
             $stmt = $this->conn->prepare($sql);
             $stmt->bindParam(':id_voiture', $id_voiture, PDO::PARAM_INT);
             $stmt->execute();
             return true;
         }catch (PDOException $e){
             error_log("Erreur suppression voiture : " . $e->getMessage());
             return false;
         }

 }

}