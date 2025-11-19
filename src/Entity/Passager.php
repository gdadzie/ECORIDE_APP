<?php

namespace Entity;

class Passager
{
    private ?int $id_utilisateur = null;
    private float $note_moyenne_passager = 0.00;

    public function __construct(float $note_moyenne_passager = 0.00)
    {
        $this->note_moyenne_passager = $note_moyenne_passager;
    }

    public function getIdUtilisateur(): ?int { return $this->id_utilisateur; }
    public function getNoteMoyennePassager(): float { return $this->note_moyenne_passager; }
    public function setIdUtilisateur(?int $id_utilisateur): void { $this->id_utilisateur = $id_utilisateur; }
    public function setNoteMoyennePassager(float $note_moyenne_passager): void { $this->note_moyenne_passager = $note_moyenne_passager; }
}