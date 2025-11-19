<?php
namespace Service;

use Entity\Credit;
use Repository\CreditsRepository;
use Entity\Utilisateur;

class CreditsService
{
    private CreditsRepository $creditsRepo;

    public function __construct(CreditsRepository $creditsRepo)
    {
        $this->creditsRepo = $creditsRepo;
    }

    /**
     * Récupère le nombre de crédits d’un utilisateur.
     * Retourne 0 si l’utilisateur n’a pas encore d’entrée crédits.
     */
    public function getCredits(Utilisateur $user): int
    {
        $result = $this->creditsRepo->getCreditsByUtilisateur($user);

        return $result ? (int)$result->credit : 0;
    }



    /**
     * Met à jour le montant des crédits d’un utilisateur.
     * Si l’utilisateur n’a pas encore de crédits, on lui crée une entrée.
     */
    public function updateCredits(Utilisateur $user, float $nouveauMontant): bool
    {
        $credits = $this->creditsRepo->getCreditsByUtilisateur($user->getIdUtilisateur());

        if (!$credits) {
            $credits = new Credit();
            $credits->setIdUtilisateur($user->getIdUtilisateur());
        }

        $credits->setMontant($nouveauMontant);
        return $this->creditsRepo->updateCredits($credits);
    }
}
