<?php
namespace Controller\Credits;

use Entity\Covoiturage;
use Repository\CreditsRepository;
use Entity\Utilisateur;
use PDO;


class CreditsController
{
    private CreditsRepository $creditRepo;

    public function __construct(PDO $conn)
    {
        $this->creditRepo = new CreditsRepository($conn);
    }

    // Récupère le nombre de crédits pour un utilisateur
    public function getCredits(Utilisateur $user): int
    {
        return $this->creditRepo->getCreditsByUtilisateur($user);
    }

    // Met à jour le nombre de crédits
    public function updateCredits(Utilisateur $user, int $nouveauNombre): bool
    {
        return $this->creditRepo->updateCredits($user, $nouveauNombre);
    }



}
