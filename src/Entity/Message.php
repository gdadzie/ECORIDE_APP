<?php

namespace Entity;

class Message
{
    private ?int $id_message = null;
    private ?int $expediteur = null;
    private ?int $destinataire = null;
    private ?string $contenu = null;
    private ?string $date_envoi = null;

    public function __construct(?int $expediteur = null, ?int $destinataire = null, ?string $contenu = null, ?string $date_envoi = null)
    {
        $this->expediteur = $expediteur;
        $this->destinataire = $destinataire;
        $this->contenu = $contenu;
        $this->date_envoi = $date_envoi ?: date('Y-m-d H:i:s');
    }

    public function getIdMessage(): ?int { return $this->id_message; }
    public function getExpediteur(): ?int { return $this->expediteur; }
    public function getDestinataire(): ?int { return $this->destinataire; }
    public function getContenu(): ?string { return $this->contenu; }
    public function getDateEnvoi(): ?string { return $this->date_envoi; }
    public function setIdMessage(?int $id_message): void { $this->id_message = $id_message; }
    public function setExpediteur(?int $expediteur): void { $this->expediteur = $expediteur; }
    public function setDestinataire(?int $destinataire): void { $this->destinataire = $destinataire; }
    public function setContenu(?string $contenu): void { $this->contenu = $contenu; }
    public function setDateEnvoi(?string $date_envoi): void { $this->date_envoi = $date_envoi; }
}