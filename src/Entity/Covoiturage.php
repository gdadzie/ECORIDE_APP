<?php

namespace Entity;

class Covoiturage
{
    private int $id_covoiturage;
    private int $id_utilisateur;
    private int $id_vehicule;
    private int $ville_depart;
    private int $ville_arrivee;
    private string $date_depart;
    private string $heure_depart;
    private float $distance_km;
    private float $prix;
    private int $nb_places;
    private bool $ecologique;
    private string $statut;

    public function __construct(
        int $id_utilisateur,
        int $id_vehicule,
        int $ville_depart,
        int $ville_arrivee,
        string $date_depart,
        string $heure_depart,
        float $distance_km,
        int $nb_places,
        bool $ecologique,
        string $statut = 'prévu'
    ) {
        $this->id_utilisateur = $id_utilisateur;
        $this->id_vehicule = $id_vehicule;
        $this->ville_depart = $ville_depart;
        $this->ville_arrivee = $ville_arrivee;
        $this->date_depart = $date_depart;
        $this->heure_depart = $heure_depart;
        $this->distance_km = $distance_km;
        $this->nb_places = $nb_places;
        $this->ecologique = $ecologique;
        $this->statut = $statut;

        // Calcul automatique du prix côté PHP
        $this->prix = $this->calculPrix();
    }

    // --- Getters ---
    public function getIdCovoiturage(): ?int { return $this->id_covoiturage ?? null; }
    public function getIdUtilisateur(): int { return $this->id_utilisateur; }
    public function getIdVehicule(): int { return $this->id_vehicule; }
    public function getVilleDepart(): int { return $this->ville_depart; }
    public function getVilleArrivee(): int { return $this->ville_arrivee; }
    public function getDateDepart(): string { return $this->date_depart; }
    public function getHeureDepart(): string { return $this->heure_depart; }
    public function getDistanceKm(): float { return $this->distance_km; }
    public function getPrix(): float { return $this->prix; }
    public function getNbPlaces(): int { return $this->nb_places; }
    public function isEcologique(): bool { return $this->ecologique; }
    public function getStatut(): string { return $this->statut; }

    // --- Setters ---
    public function setIdCovoiturage(int $id): void { $this->id_covoiturage = $id; }
    public function setNbPlaces(int $nb): void { $this->nb_places = $nb; }
    public function setEcologique(bool $eco): void { $this->ecologique = $eco; }
    public function setStatut(string $statut): void { $this->statut = $statut; }

    // --- Calcul du prix ---
    private function calculPrix(): float
    {
        // Exemple : 0,5 €/km, +20% si non écologique
        $tarif_km = 0.5;
        $prix = $this->distance_km * $tarif_km;

        if (!$this->ecologique) {
            $prix *= 1.2;
        }

        return round($prix, 2);
    }
}
