<?php
namespace Entity;

class Credit
{
    private ?int $id_credit = null;
    private ?int $id_utilisateur = null;
    private float $montant = 0.0;

    public function __construct(?int $id_utilisateur = null, float $montant = 0.0)
    {
        $this->id_utilisateur = $id_utilisateur;
        $this->montant = $montant;
    }

    // Getters
    public function getIdCredit(): ?int { return $this->id_credit; }
    public function getIdUtilisateur(): ?int { return $this->id_utilisateur; }
    public function getMontant(): float { return $this->montant; }

    // Setters
    public function setIdCredit(?int $id_credit): void { $this->id_credit = $id_credit; }
    public function setIdUtilisateur(?int $id_utilisateur): void { $this->id_utilisateur = $id_utilisateur; }
    public function setMontant(float $montant): void { $this->montant = $montant; }
}
