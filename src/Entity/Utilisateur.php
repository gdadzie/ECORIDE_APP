<?php
namespace Entity;


use Config\Database;

require_once __DIR__ . '/../../Config/Database.php';

class Utilisateur
{
    private ?int $id_utilisateur = null; // null par défaut, sera rempli après insertion
    private ?string $nom;
    private ?string $prenom;
    private string $pseudo;
    private string $email;
    private ?string $telephone;
    private string $mdp;
    private ?string $role;
    private ?string $type_covoiturage;
    private ?int $actif;
    private ?string $photo;
    private ?string $date_creation;


    /**
     * Constructeur flexible : tous les paramètres sont optionnels
     */
    public function __construct(
        string $nom = '',
        string $prenom = '',
        string $pseudo = '',
        string $email = '',
        string $telephone = '',
        string $mdp = '',
        string $role = 'user',
        string $type_covoiturage = 'passager',

        int $actif = 1,
        string $photo = '',
        string $date_creation = ''
    ) {
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->pseudo = $pseudo;
        $this->email = $email;
        $this->telephone = $telephone;
        $this->mdp = $mdp;
        $this->role = $role;
        $this->type_covoiturage = $type_covoiturage;

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
    public function getPseudo(): ?string { return $this->pseudo; }
    public function getEmail(): ?string { return $this->email; }
    public function getMdp(): ?string { return $this->mdp; }
    public function getTelephone(): ?string { return $this->telephone; }
    public function getRole(): ?string { return $this->role; }
    public function getTypeCovoiturage(): ?string { return $this->type_covoiturage; }

    public function getActif(): ?bool { return $this->actif; }
    public function getPhoto(): ?string { return $this->photo; }
    public function getDateCreation(): ?string { return $this->date_creation; }

    // -----------------------
    // Setters
    // -----------------------

    public function setIdUtilisateur($id_utilisateur): void { $this->id_utilisateur = $id_utilisateur; }
    public function setNom($nom): void { $this->nom = $nom; }
    public function setPrenom($prenom): void { $this->prenom = $prenom; }
    public function setPseudo($pseudo): void { $this->pseudo = $pseudo; }
    public function setEmail($email): void { $this->email = $email; }
    public function setMdp($mdp): void { $this->mdp = $mdp; }
    public function setTelephone($telephone): void { $this->telephone = $telephone; }
    public function setRole($role): void { $this->role = $role; }
    public function setTypeCovoiturage($type_covoiturage): void { $this->type_covoiturage = $type_covoiturage; }
    public function setActif($actif): void { $this->actif = $actif; }
    public function setPhoto($photo): void { $this->photo = $photo; }
    public function setDateCreation($date_creation): void { $this->date_creation = $date_creation; }
    public function validate(): void
    {
        $this->email = trim($this->email);
        $this->email = filter_var($this->email, FILTER_SANITIZE_EMAIL);

        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException("Email invalide.");
        }

        if (!preg_match('/^[a-zA-Z0-9_]{3,20}$/', $this->pseudo)) {
            throw new \InvalidArgumentException("Le pseudo doit contenir entre 3 et 20 caractères alphanumériques.");
        }

        if (empty($this->mdp) || strlen($this->mdp) < 6) {
            throw new \InvalidArgumentException("Le mot de passe doit contenir au moins 6 caractères.");
        }
    }
}

