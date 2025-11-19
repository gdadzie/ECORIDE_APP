<?php

namespace Entity;

class Conducteur
{
    private ?int $id_utilisateur = null;
    private ?float $note_moyenne_conducteur = null;

    public function __construct(?float $note_moyenne_conducteur = null)
    {
        $this->note_moyenne_conducteur = $note_moyenne_conducteur;
    }

    public function getIdUtilisateur(): ?int { return $this->id_utilisateur; }
    public function getNoteMoyenneConducteur(): ?float { return $this->note_moyenne_conducteur; }
    public function setIdUtilisateur(?int $id_utilisateur): void { $this->id_utilisateur = $id_utilisateur; }
    public function setNoteMoyenneConducteur(?float $note_moyenne_conducteur): void { $this->note_moyenne_conducteur = $note_moyenne_conducteur; }
}