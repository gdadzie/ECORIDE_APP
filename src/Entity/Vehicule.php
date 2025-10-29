<?php
namespace Entity;

class Vehicule
{
    private ?int $id_vehicule;
    private ?int $id_utilisateur;
    private ?int $id_marque;
    private ?string $nom_marque = null; // nouveau champ pour afficher le nom
    private ?string $modele;
    private ?string $couleur;
    private ?string $energie;
    private ?string $immatriculation;
    private ?string $date_premiere_immatriculation;
    private ?int $nb_places;

    public function __construct(
        ?int $id_utilisateur = null,
        ?int $id_marque = null,
        ?string $modele = null,
        ?string $couleur = null,
        ?string $energie = null,
        ?string $immatriculation = null,
        ?string $date_premiere_immatriculation = null,
        ?int $nb_places = null
    ) {
        $this->id_vehicule = null;
        $this->id_utilisateur = $id_utilisateur;
        $this->id_marque = $id_marque;
        $this->modele = $modele;
        $this->couleur = $couleur;
        $this->energie = $energie;
        $this->immatriculation = $immatriculation;
        $this->date_premiere_immatriculation = $date_premiere_immatriculation;
        $this->nb_places = $nb_places;
    }

    // GETTERS
    public function getIdVehicule(): ?int { return $this->id_vehicule; }
    public function getIdUtilisateur(): ?int { return $this->id_utilisateur; }
    public function getIdMarque(): ?int { return $this->id_marque; }
    public function getNomMarque(): ?string { return $this->nom_marque; }
    public function getModele(): ?string { return $this->modele; }
    public function getCouleur(): ?string { return $this->couleur; }
    public function getEnergie(): ?string { return $this->energie; }
    public function getImmatriculation(): ?string { return $this->immatriculation; }
    public function getDatePremiereImmatriculation(): ?string { return $this->date_premiere_immatriculation; }
    public function getNbPlaces(): ?int { return $this->nb_places; }

    // SETTERS
    public function setIdVehicule(int $id_vehicule): void { $this->id_vehicule = $id_vehicule; }
    public function setIdUtilisateur(int $id_utilisateur): void { $this->id_utilisateur = $id_utilisateur; }
    public function setIdMarque(int $id_marque): void { $this->id_marque = $id_marque; }
    public function setNomMarque(string $nom_marque): void { $this->nom_marque = $nom_marque; }
    public function setModele(string $modele): void { $this->modele = $modele; }
    public function setCouleur(string $couleur): void { $this->couleur = $couleur; }
    public function setEnergie(string $energie): void { $this->energie = $energie; }
    public function setImmatriculation(string $immatriculation): void { $this->immatriculation = $immatriculation; }
    public function setDatePremiereImmatriculation(string $date): void { $this->date_premiere_immatriculation = $date; }
    public function setNbPlaces(int $nb_places): void { $this->nb_places = $nb_places; }
}
