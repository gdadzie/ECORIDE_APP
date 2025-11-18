<?php

namespace Repository;

use Entity\Reservation;
use Repository\CovoituragesRepository;
use PDO;

class ReservationRepository
{
    private PDO $conn;

    public function __construct(PDO $conn)
    {
        $this->conn = $conn;
    }

    // Enregistre une nouvelle réservation
    public function save(Reservation $reservation): bool
    {
        $stmt = $this->conn->prepare("
            INSERT INTO reservation (id_utilisateur, id_covoiturage, date_reservation)
            VALUES (:id_utilisateur, :id_covoiturage, :date)
        ");
        return $stmt->execute([
            'id_utilisateur' => $reservation->getUtilisateur()->getIdUtilisateur(),
            'id_covoiturage' => $reservation->getCovoiturage()->getIdCovoiturage(),
            'date' => $reservation->getDate()->format('Y-m-d H:i:s'),
        ]);
    }
}
