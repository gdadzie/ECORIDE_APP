<?php

namespace Entity;

use Config\Database;
class Transaction
{
    private ?int $id_transaction = null; // Clé primaire auto-incrémentée
    private ?int $id_utilisateur = null;
    private float $montant = 20.00;
    private ?string $type = null; // 'Ajout', 'Paiement', 'Remboursement', 'Commission'

    /**
     * Constructeur flexible
     */
    public function __construct(
        ?int $id_utilisateur = null,
        float $montant = 20.00,
        ?string $type = null
    ) {
        $this->id_utilisateur = $id_utilisateur;
        $this->montant = $montant;
        $this->type = $type;
    }

    // -----------------------
    // Getters
    // -----------------------
    public function getIdTransaction(): ?int { return $this->id_transaction; }
    public function getIdUtilisateur(): ?int { return $this->id_utilisateur; }
    public function getMontant(): float { return $this->montant; }
    public function getType(): ?string { return $this->type; }

    // -----------------------
    // Setters
    // -----------------------
    public function setIdTransaction(?int $id_transaction): void { $this->id_transaction = $id_transaction; }
    public function setIdUtilisateur(?int $id_utilisateur): void { $this->id_utilisateur = $id_utilisateur; }
    public function setMontant(float $montant): void { $this->montant = $montant; }
    public function setType(?string $type): void { $this->type = $type; }
}