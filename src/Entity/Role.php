<?php

namespace Entity;

class Role
{
    private ?int $id_role = null;
    private string $nom_role = '';

    public function __construct(string $nom_role = '')
    {
        $this->nom_role = $nom_role;
    }

    public function getIdRole(): ?int { return $this->id_role; }
    public function getNomRole(): string { return $this->nom_role; }
    public function setIdRole(?int $id_role): void { $this->id_role = $id_role; }
    public function setNomRole(string $nom_role): void { $this->nom_role = $nom_role; }
}