<?php
namespace Entity;

use Config\Database;

require_once __DIR__ . '/../../Config/Database.php';

class Utilisateur
{
    private ?int $id_utilisateur = null; // Null par défaut
    private ?string $nom = null;
    private ?string $prenom = null;
    private string $pseudo = '';
    private string $email = '';
    private ?string $telephone = null;
    private string $mdp = '';
    private ?string $role = 'user';
    private ?string $type_utilisateur = 'passager'; // Par défaut "passager"
    private ?int $actif = 1;
    private ?string $photo = null;
    private ?string $date_creation = null;
    private ?float $note = null;
    private ?float $nb_avis = null;
    private ?bool $animaux = null;  // <-- ajoute cette ligne
    private ?bool $fumeur = null;
    private array $vehicules = [];
    private float $credit ;
    private bool $est_conducteur = false;
    private bool $est_passager = true;



    /**
     * Constructeur flexible
     */
    public function __construct(
        string $nom = '',
        string $prenom = '',
        string $pseudo = '',
        string $email = '',
        string $telephone = '',
        string $mdp = '',
        string $role = 'user',
        string $type_utilisateur = 'passager',
        int $actif = 1,
        string $photo = '',
        string $date_creation = '',


) {
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->pseudo = $pseudo;
        $this->email = $email;
        $this->telephone = $telephone;
        $this->mdp = $mdp;
        $this->role = $role;
        $this->type_utilisateur = $type_utilisateur;
        $this->actif = $actif;
        $this->photo = $photo;
        $this->date_creation = $date_creation ?: date('Y-m-d H:i:s');
    }

    // -----------------------
    // Getters
    // -----------------------
    public function getIdUtilisateur(): ?int { return $this->id_utilisateur; }
    public function getNom(): ?string { return $this->nom; }
    public function getPrenom(): ?string { return $this->prenom; }
    public function getPseudo(): string { return $this->pseudo; }
    public function getEmail(): string { return $this->email; }
    public function getMdp(): string { return $this->mdp; }
    public function getTelephone(): ?string { return $this->telephone; }
    public function getRole(): ?string { return $this->role; }
    public function getTypeUtilisateur(): ?string { return $this->type_utilisateur; }
    public function getActif(): ?int { return $this->actif; }
    public function getPhoto(): ?string { return $this->photo; }
    public function getDateCreation(): ?string { return $this->date_creation; }

    public function getNbAvis(): ?float { return $this->nb_avis; }

    public function getAnimaux(): ?bool { return $this->animaux; }
    public function getFumeur(): ?bool { return $this->fumeur; }


    // -----------------------
    // Setters
    // -----------------------
    public function setIdUtilisateur(?int $id_utilisateur): void { $this->id_utilisateur = $id_utilisateur; }
    public function setNom(?string $nom): void { $this->nom = $nom; }
    public function setPrenom(?string $prenom): void { $this->prenom = $prenom; }
    public function setPseudo(string $pseudo): void { $this->pseudo = $pseudo; }
    public function setEmail(string $email): void { $this->email = $email; }
    public function setMdp(string $mdp): void { $this->mdp = $mdp; }
    public function setTelephone(?string $telephone): void { $this->telephone = $telephone; }
    public function setRole(?string $role): void { $this->role = $role; }
    public function setTypeUtilisateur(?string $type_utilisateur): void { $this->type_utilisateur = $type_utilisateur; }
    public function setActif(?int $actif): void { $this->actif = $actif; }
    public function setPhoto(?string $photo): void { $this->photo = $photo; }
    public function isConducteur(): bool {
        return $this->est_conducteur;
    }

    public function isPassager(): bool {
        return $this->est_passager;
    }

    public function setEstConducteur(bool $val): void {
        $this->est_conducteur = $val;
    }

    public function setEstPassager(bool $val): void {
        $this->est_passager = $val;
    }
    public function setDateCreation(?string $date_creation): void { $this->date_creation = $date_creation; }
    public function getNote(): ?float { return $this->note; }
    public function setNote(?float $note): void { $this->note = $note; }

    public function setNbAvis(?float $nb_avis): void { $this->nb_avis = $nb_avis; }

    public function setAnimaux(?bool $animaux): void
    {
        $this->animaux = $animaux;
    }

    public function setFumeur(?bool $fumeur): void
    {
        $this->fumeur = $fumeur;
    }
    public function getVehicules(): array
    {
        return $this->vehicules;
    }

    public function setVehicules(array $vehicules): void
    {
        $this->vehicules = $vehicules;
    }

    public function getCredit (): ?float
    {
        return $this->credit;

    }

    public function setCredit (?float $credit): void
    {
        $this->credit = $credit;
    }


}
