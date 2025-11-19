<?php
namespace Entity;

class Reservation
{
    private ?int $id_reservation = null;
    private ?int $id_covoiturage = null;
    private ?int $id_utilisateur = null;
    private string $date_reservation;
    private string $statut;
    private float $confirmation;

    public function __construct(
        ?int $id_covoiturage = null,
        ?int $id_utilisateur = null,
        ?string $date_reservation = null,
        string $statut = 'en attente',
        float $confirmation = 0.0
    ) {
        $this->id_covoiturage = $id_covoiturage;
        $this->id_utilisateur = $id_utilisateur;
        $this->date_reservation = $date_reservation ?: date('Y-m-d H:i:s');
        $this->statut = $statut;
        $this->confirmation = $confirmation;
    }

    // Getters
    public function getIdReservation(): ?int { return $this->id_reservation; }
    public function getIdCovoiturage(): ?int { return $this->id_covoiturage; }
    public function getIdUtilisateur(): ?int { return $this->id_utilisateur; }
    public function getDateReservation(): string { return $this->date_reservation; }
    public function getStatut(): string { return $this->statut; }
    public function getConfirmation(): float { return $this->confirmation; }

    // Setters
    public function setIdReservation(?int $id_reservation): void { $this->id_reservation = $id_reservation; }
    public function setIdCovoiturage(?int $id_covoiturage): void { $this->id_covoiturage = $id_covoiturage; }
    public function setIdUtilisateur(?int $id_utilisateur): void { $this->id_utilisateur = $id_utilisateur; }
    public function setDateReservation(string $date_reservation): void { $this->date_reservation = $date_reservation; }
    public function setStatut(string $statut): void { $this->statut = $statut; }
    public function setConfirmation(float $confirmation): void { $this->confirmation = $confirmation; }
}
