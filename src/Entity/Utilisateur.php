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
        string $date_creation = ''
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
    public function setDateCreation(?string $date_creation): void { $this->date_creation = $date_creation; }

    /**
     * Validation des champs
     */
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
