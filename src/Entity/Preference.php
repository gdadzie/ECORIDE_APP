<?php

namespace Entity;

class Preference
{
    private ?int $id_preference = null;
    private ?int $id_utilisateur = null;
    private ?bool $fumeur = null;
    private ?bool $annimaux = null;

    public function __construct(?int $id_utilisateur = null, ?bool $fumeur = null, ?bool $annimaux = null)
    {
        $this->id_utilisateur = $id_utilisateur;
        $this->fumeur = $fumeur;
        $this->annimaux = $annimaux;
    }

    public function getIdPreference(): ?int { return $this->id_preference; }
    public function getIdUtilisateur(): ?int { return $this->id_utilisateur; }
    public function isFumeur(): ?bool { return $this->fumeur; }
    public function isAnnimaux(): ?bool { return $this->annimaux; }
    public function setIdPreference(?int $id_preference): void { $this->id_preference = $id_preference; }
    public function setIdUtilisateur(?int $id_utilisateur): void { $this->id_utilisateur = $id_utilisateur; }
    public function setFumeur(?bool $fumeur): void { $this->fumeur = $fumeur; }
    public function setAnnimaux(?bool $annimaux): void { $this->annimaux = $annimaux; }
}