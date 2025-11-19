<?php

namespace Entity;

class Marque
{
    private ?int $id_marque = null;
    private string $nom_marque = '';

    public function __construct(string $nom_marque = '')
    {
        $this->nom_marque = $nom_marque;
    }

    public function getIdMarque(): ?int { return $this->id_marque; }
    public function getNomMarque(): string { return $this->nom_marque; }
    public function setIdMarque(?int $id_marque): void { $this->id_marque = $id_marque; }
    public function setNomMarque(string $nom_marque): void { $this->nom_marque = $nom_marque; }
}