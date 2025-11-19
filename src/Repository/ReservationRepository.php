<?php
namespace Repository;

use Entity\Reservation;
use PDO;

class ReservationRepository
{
    private PDO $conn;

    public function __construct(PDO $conn)
    {
        $this->conn = $conn;
    }

    public function getConn(): PDO
    {
        return $this->conn;
    }

    // Enregistre une nouvelle réservation
    public function save(Reservation $reservation): bool
    {
        $stmt = $this->conn->prepare("
            INSERT INTO reservation (id_utilisateur, id_covoiturage, date_reservation, statut, confirmation)
            VALUES (:id_utilisateur, :id_covoiturage, :date_reservation, :statut, :confirmation)
        ");
        return $stmt->execute([
            'id_utilisateur' => $reservation->getIdUtilisateur(),
            'id_covoiturage' => $reservation->getIdCovoiturage(),
            'date_reservation' => $reservation->getDateReservation(),
            'statut' => $reservation->getStatut(),
            'confirmation' => $reservation->getConfirmation(),
        ]);
    }
}
