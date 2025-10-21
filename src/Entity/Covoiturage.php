<?php

namespace Entity;

use Config\Database;
use Exception;
use PDO;
use PDOException;

require_once __DIR__ . '/../../Config/Database.php';

class Covoiturage
{
   private $id_utilisateur;
    private $id_covoiturage;

    private $id_vehicule;
    private $ville_depart;
    private $ville_arrivee;
    private $date_depart;
    private $date_arrivee;
    private $heure_depart;
    private $heure_arrivee;
    private $prix;
   private $nb_places;
   private $ecologique;
   private $statut;


   public function __construct(int $id_utilisateur, int $id_covoiturage, string $ville_depart, string $ville_arrivee, string $date_depart, string $date_arrivee, string $heure_depart, string $heure_arrivee, float $prix, float $nb_places, float $ecologique, string $statut)
   {
       $this->id_utilisateur = $id_utilisateur;
       $this->id_covoiturage = $id_covoiturage;
       $this->ville_depart = $ville_depart;
       $this->ville_arrivee = $ville_arrivee;
       $this->date_depart = $date_depart;
       $this->date_arrivee = $date_arrivee;
       $this->heure_depart = $heure_depart;
       $this->heure_arrivee = $heure_arrivee;
       $this->prix = $prix;
       $this->nb_places = $nb_places;
       $this->ecologique = $ecologique;
       $this->statut = $statut;

   }

   public function getIdUtilisateur()
   {
       return $this->id_utilisateur;
   }

   public function getIdCovoiturage()
   {
       return $this->id_covoiturage;
   }

   public function getIdVehicule()
   {
       return $this->id_vehicule;
   }

   public function getVilleDepart()
   {
       return $this->ville_depart;
   }

   public function getVilleArrivee()
   {
       return $this->ville_arrivee;
   }

   public function getDateDepart()
   {
       return $this->date_depart;
   }

   public function getDateArrivee()
   {
       return $this->date_arrivee;
   }

   public function getHeureDepart()
   {
       return $this->heure_depart;
   }

   public function getHeureArrivee()
   {
       return $this->heure_arrivee;
   }
   public function getPrix()
   {
       return $this->prix;
   }

   public function getNbPlaces()
   {
       return $this->nb_places;
   }

   public function getEcologique()
   {
       return $this->ecologique;
   }

   public function getStatut()
   {
       return $this->statut;
   }

   //Setters
    public function setIdUtilisateur(int $id_utilisateur):void
    {
        $this->id_utilisateur = $id_utilisateur;
    }

    public function setIdCovoiturage(int $id_covoiturage):void
    {
        $this->id_covoiturage = $id_covoiturage;
    }

    public function setIdVehicule(int $id_vehicule):void
    {
        $this->id_vehicule = $id_vehicule;
    }

    public function setVilleDepart(string $ville_depart):void
    {
        $this->ville_depart = $ville_depart;
    }

    public function setVilleArrivee(string $ville_arrivee):void
    {
        $this->ville_arrivee = $ville_arrivee;
    }

    public function setDateDepart(string $date_depart):void
    {
        $this->date_depart = $date_depart;
    }

    public function setDateArrivee(string $date_arrivee):void
    {
        $this->date_arrivee = $date_arrivee;
    }

    public function setHeureDepart(string $heure_depart):void
    {
        $this->heure_depart = $heure_depart;
    }

    public function setHeureArrivee(string $heure_arrivee):void
    {
        $this->heure_arrivee = $heure_arrivee;
    }

    public function setPrix(string $prix):void
    {
        $this->prix = $prix;
    }

    public function setNbPlaces(int $nb_places):void
    {
        $this->nb_places = $nb_places;
    }

    public function setEcologique(bool $ecologique):void
    {
        $this->ecologique = $ecologique;
    }

    public function setStatut(string $statut):void
    {
        $this->statut = $statut;
    }


}
