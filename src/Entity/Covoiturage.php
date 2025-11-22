<?php

namespace Entity;
use DateTime;

class Covoiturage

{
    // ============================
    // INFOS PRINCIPALES DU TRAJET
    // ============================
    private ?int $id_covoiturage = null;
    private ?int $id_utilisateur = null;
    private ?int $id_vehicule = null;
    private ?string $ville_depart = null;
    private ?string $ville_arrivee = null;
    private ?string $date_depart = null;
    private ?string $heure_depart = null;
    private ?string $heure_arrivee = null;
    private ?int $duree_minutes = null;
    private ?float $distance_km = null;
    private ?float $prix = null;
    private ?int $nb_places = null;
    private ?bool $ecologique = false;
    private ?string $statut = 'en attente';
    private ?string $description = null;

    // ============================
    // CONDUCTEUR / UTILISATEUR
    // ============================
    private ?Utilisateur $conducteur = null;
    private ?string $pseudo = null;
    private ?string $photo = null;
    private ?float $note = null;



    // ============================
    // VEHICULE
    // ============================
    private ?Vehicule $vehicule = null;

    // Tableau d'objets véhicules liés
    private ?string $vehiculeNom = null;
    private ?string $vehiculeModele = null;
    private ?string $vehiculeEnergie =null;
    private bool $vehiculeEcologique = false;

    // ============================
    // OPTIONS
    // ============================
    private ?bool $fumeur = null;
    private ?bool $animaux = null;

    // ============================
    // TRANSACTION
    // ============================
    private ?Transaction $transaction = null;

    // ============================
    // VILLES POUR AFFICHAGE
    // ============================
    private ?string $ville_depart_nom = null;
    private ?string $ville_arrivee_nom = null;

    private ?string $dateDepartFormatee = null;

    // ============================
    // CONSTRUCTEUR
    // ============================
    public function __construct(

        ?int $id_utilisateur = null,
        ?int $id_vehicule = null,
        ?string $ville_depart = null,
        ?string $ville_arrivee = null,
        ?string $date_depart = null,
        ?string $heure_depart = null,
        ?int $duree_minutes = null,
        ?float $distance_km = null,
        ?float $prix = null,
        ?int $nb_places = null,
        ?bool $ecologique = false,
        ?string $statut = 'en attente',
        ?string $pseudo = null,
        ?string $photo = null,
        ?float $note = null,
        ?Transaction $transaction = null,
        ?Utilisateur $conducteur = null,
        ?string $description = null,
        ?string $vehiculeEnergie = null,
        bool $vehiculeEcologique = false,

    ) {
        // Infos principales
        $this->id_utilisateur = $id_utilisateur;
        $this->id_vehicule = $id_vehicule;
        $this->ville_depart = $ville_depart;
        $this->ville_arrivee = $ville_arrivee;
        $this->date_depart = $date_depart;
        $this->heure_depart = $heure_depart;
        $this->duree_minutes = $duree_minutes;
        $this->distance_km = $distance_km;
        $this->prix = $prix;
        $this->nb_places = $nb_places;
        $this->ecologique = $ecologique;
        $this->statut = $statut;
        $this->description = $description;

        //Vehicule
        $this->vehiculeEnergie = $vehiculeEnergie;
        $this->vehiculeEcologique = $vehiculeEcologique;

        // Conducteur
        $this->pseudo = $pseudo;
        $this->photo = $photo;
        $this->note = $note;
        $this->conducteur = $conducteur;

        // Transaction
        $this->transaction = $transaction;

        // Calcul automatique de l'heure d'arrivée si possible
        $this->heure_arrivee = $this->calculerHeureArrivee();

    }

    // ============================
    // GETTERS & SETTERS TRAJET
    // ============================
    public function getIdCovoiturage(): ?int { return $this->id_covoiturage; }
    public function setIdCovoiturage(?int $id): void { $this->id_covoiturage = $id; }

    public function getIdUtilisateur(): ?int { return $this->id_utilisateur; }
    public function setIdUtilisateur(?int $id): void { $this->id_utilisateur = $id; }

    public function getIdVehicule(): ?int { return $this->id_vehicule; }
    public function setIdVehicule(?int $id): void { $this->id_vehicule = $id; }

    public function getVilleDepart(): ?string { return $this->ville_depart; }
    public function setVilleDepart(?string $ville): void { $this->ville_depart = $ville; }

    public function getVilleArrivee(): ?string { return $this->ville_arrivee; }
    public function setVilleArrivee(?string $ville): void { $this->ville_arrivee = $ville; }

    public function getDateDepart(): ?string { return $this->date_depart; }
    public function setDateDepart(?string $date): void { $this->date_depart = $date; }

    public function getHeureDepart(): ?string { return $this->heure_depart; }
    public function setHeureDepart(?string $heure): void {
        $this->heure_depart = $heure;
        $this->heure_arrivee = $this->calculerHeureArrivee();
    }

    public function getHeureArrivee(): ?string { return $this->heure_arrivee; }
    public function setHeureArrivee(?string $heure): void { $this->heure_arrivee = $heure; }

    public function getDureeMinutes(): ?int { return $this->duree_minutes; }
    public function setDureeMinutes(?int $minutes): void {
        $this->duree_minutes = $minutes;
        $this->heure_arrivee = $this->calculerHeureArrivee();
    }

    public function getDistanceKm(): ?float { return $this->distance_km; }
    public function setDistanceKm(?float $km): void { $this->distance_km = $km; }

    public function getPrix(): ?float { return $this->prix; }
    public function setPrix(?float $prix): void { $this->prix = $prix; }

    public function getNbPlaces(): ?int { return $this->nb_places; }
    public function setNbPlaces(?int $nb): void { $this->nb_places = $nb; }

    public function isEcologique(): ?bool { return $this->ecologique; }
    public function setEcologique(?bool $eco): void { $this->ecologique = $eco; }

    public function getStatut(): ?string { return $this->statut; }
    public function setStatut(?string $statut): void { $this->statut = $statut; }

    public function getDescription(): ?string { return $this->description; }
    public function setDescription(?string $desc): void { $this->description = $desc; }

    // ============================
    // GETTERS & SETTERS CONDUCTEUR
    // ============================
    public function getConducteur(): ?Utilisateur { return $this->conducteur; }
    public function setConducteur(?Utilisateur $u): void { $this->conducteur = $u; }

    public function getPseudo(): ?string { return $this->pseudo; }
    public function setPseudo(?string $pseudo): void { $this->pseudo = $pseudo; }

    public function getPhoto(): ?string { return $this->photo; }
    public function setPhoto(?string $photo): void { $this->photo = $photo; }

    public function getNote(): ?float { return $this->note; }
    public function setNote(?float $note): void { $this->note = $note; }

    // ============================
    // GETTERS & SETTERS VEHICULE
    // ============================
    public function setVehicule(?Vehicule $vehicule): void {
        $this->vehicule = $vehicule;
    }

    public function getVehicule(): ?Vehicule {
        return $this->vehicule;
    }

    public function getVehiculeNom(): ?string { return $this->vehiculeNom; }
    public function setVehiculeNom(?string $nom): void { $this->vehiculeNom = $nom; }

    public function getVehiculeModele(): ?string { return $this->vehiculeModele; }
    public function setVehiculeModele(?string $modele): void { $this->vehiculeModele = $modele; }

    public function getVehiculeEnergie(): ?string

    {
       return $this->vehiculeEnergie;
    }

    // ============================
    // GETTERS & SETTERS OPTIONS
    // ============================
    public function getFumeur(): ?bool { return $this->fumeur; }
    public function setFumeur(?bool $val): void { $this->fumeur = $val; }

    public function getAnimaux(): ?bool { return $this->animaux; }
    public function setAnimaux(?bool $val): void { $this->animaux = $val; }

    public function getDateDepartFormatee(): ?string {
        if ($this->date_depart === null) return null;
        $dt = DateTime::createFromFormat('Y-m-d', $this->date_depart);
        return $dt ? $dt->format('d/m/Y') : null;
    }


    public function setDateDepartFormatee(string $date): void {
        $this->dateDepartFormatee = $date;
    }

    // ============================
    // GETTERS & SETTERS TRANSACTION
    // ============================
    public function getTransaction(): ?Transaction { return $this->transaction; }
    public function setTransaction(?Transaction $transaction): void { $this->transaction = $transaction; }

    public function getModePaiement(): ?string { return $this->transaction?->getType(); }

    // ============================
    // GETTERS & SETTERS VILLES AFFICHAGE
    // ============================
    public function getVilleDepartNom(): ?string { return $this->ville_depart_nom; }
    public function setVilleDepartNom(?string $nom): void { $this->ville_depart_nom = $nom; }

    public function getVilleArriveeNom(): ?string { return $this->ville_arrivee_nom; }
    public function setVilleArriveeNom(?string $nom): void { $this->ville_arrivee_nom = $nom; }

    // ============================
    // MÉTHODES UTILES
    // ============================

    /**
     * Calcule automatiquement l'heure d'arrivée à partir de l'heure de départ et de la durée
     */
    public function calculerHeureArrivee(): string {
        if (empty($this->heure_depart) || empty($this->duree_minutes) || $this->duree_minutes <= 0) {
            return '00:00:00';
        }
        try {
            $depart = new \DateTime($this->heure_depart);
            $depart->modify("+" . $this->duree_minutes . " minutes");
            return $depart->format("H:i:s");
        } catch (\Exception $e) {
            return '00:00:00';
        }
    }

    /**
     * Validation simple des informations essentielles du covoiturage
     */
    public function validate(): bool {
        if (empty($this->ville_depart) || empty($this->ville_arrivee)) return false;
        if ($this->prix !== null && $this->prix < 0) return false;
        if ($this->nb_places !== null && $this->nb_places <= 0) return false;
        return true;
    }
}
