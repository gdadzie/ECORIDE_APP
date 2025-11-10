<?php
namespace Entity;

class Avis
{
    private ?int $id_avis = null;
    private ?int $id_covoiturage = null;
    private ?int $id_emetteur = null;
    private ?int $id_receveur = null;
    private ?int $note = null;
    private ?string $commentaire = null;
    private string $statut = 'en attente';
    private string $date_avis;

    public function __construct(
        ?int $id_covoiturage = null,
        ?int $id_emetteur = null,
        ?int $id_receveur = null,
        ?int $note = null,
        ?string $commentaire = null,
        string $statut = 'en attente',
        ?string $date_avis = null
    ) {
        $this->id_covoiturage = $id_covoiturage;
        $this->id_emetteur = $id_emetteur;
        $this->id_receveur = $id_receveur;
        $this->note = $note;
        $this->commentaire = $commentaire;
        $this->statut = $statut;
        $this->date_avis = $date_avis ?: date('Y-m-d H:i:s');
    }

    // Getters
    public function getIdAvis(): ?int { return $this->id_avis; }
    public function getIdCovoiturage(): ?int { return $this->id_covoiturage; }
    public function getIdEmetteur(): ?int { return $this->id_emetteur; }
    public function getIdReceveur(): ?int { return $this->id_receveur; }
    public function getNote(): ?int { return $this->note; }
    public function getCommentaire(): ?string { return $this->commentaire; }
    public function getStatut(): string { return $this->statut; }
    public function getDateAvis(): string { return $this->date_avis; }

    // Setters
    public function setIdAvis(int $id): void { $this->id_avis = $id; }
    public function setIdCovoiturage(int $id): void { $this->id_covoiturage = $id; }
    public function setIdEmetteur(int $id): void { $this->id_emetteur = $id; }
    public function setIdReceveur(int $id): void { $this->id_receveur = $id; }
    public function setNote(int $note): void { $this->note = $note; }
    public function setCommentaire(string $c): void { $this->commentaire = $c; }
    public function setStatut(string $s): void { $this->statut = $s; }
    public function setDateAvis(string $d): void { $this->date_avis = $d; }

    // Validation simple
    public function validate(): void
    {
        if ($this->note !== null && ($this->note < 1 || $this->note > 5)) {
            throw new \InvalidArgumentException("La note doit être comprise entre 1 et 5.");
        }
    }
}
