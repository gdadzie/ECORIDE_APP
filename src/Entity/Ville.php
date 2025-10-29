<?php
namespace Entity;

use Config\Database;

require_once __DIR__ . '/../../Config/Database.php';

class Ville
{
    private ?int $id_ville = null; // null par défaut, sera rempli après insertion
    private string $nom_ville;

    /**
     * Constructeur flexible : tous les paramètres sont optionnels
     */
    public function __construct(string $nom_ville = '')
    {
        $this->nom_ville = $nom_ville;
    }

    // -----------------------
    // Getters
    // -----------------------
    public function getIdVille(): ?int
    {
        return $this->id_ville;
    }

    public function getNomVille(): string
    {
        return $this->nom_ville;
    }

    // -----------------------
    // Setters
    // -----------------------
    public function setIdVille(int $id_ville): void
    {
        $this->id_ville = $id_ville;
    }

    public function setNomVille(string $nom_ville): void
    {
        $this->nom_ville = $nom_ville;
    }

    // -----------------------
    // Méthodes utilitaires
    // -----------------------
    public function validate(): void
    {
        $this->nom_ville = trim($this->nom_ville);

        if (empty($this->nom_ville)) {
            throw new \InvalidArgumentException("Le nom de la ville ne peut pas être vide.");
        }

        if (strlen($this->nom_ville) > 250) {
            throw new \InvalidArgumentException("Le nom de la ville ne peut pas dépasser 250 caractères.");
        }
    }
}
