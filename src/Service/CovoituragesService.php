<?php
namespace Service;

use Entity\Covoiturage;
use Repository\CovoituragesRepository;

class CovoituragesService
{
    private CovoituragesRepository $repo;

    public function __construct(CovoituragesRepository $repo)
    {
        $this->repo = $repo;
    }

    // Création d’un covoiturage
    public function creerCovoiturage(Covoiturage $covoiturage): bool
    {
        return $this->repo->create($covoiturage);
    }

    // Récupérer un covoiturage par ID
    public function getCovoiturageById(int $id): ?array
    {
        return $this->repo->getCovoiturageById($id);
    }

    // Récupérer tous les covoiturages
    public function getAll(): array
    {
        return $this->repo->getAllCovoiturages();
    }

    // Récupérer covoiturages par utilisateur
    public function getByUtilisateur(int $userId): array
    {
        return $this->repo->getCovoituragesByUtilisateur($userId);
    }

    // Filtrer covoiturages écologiques
    public function filterEcologique(int $ecologique = 1): array
    {
        return $this->repo->filterByEcologique($ecologique);
    }

    // Recherche souple
    public function rechercherSouple(string $villeDepart, string $villeArrivee, ?string $dateDepart = null): array
    {
        return $this->repo->rechercherCovoituragesSouples($villeDepart, $villeArrivee, $dateDepart);
    }

    // Réserver un covoiturage
    public function reserver(int $idCovoiturage): bool
    {
        $covoiturage = $this->repo->getCovoiturageById($idCovoiturage);
        if (!$covoiturage || (int)$covoiturage['nb_places'] <= 0) {
            return false;
        }

        return $this->repo->updateStatutCovoiturage($idCovoiturage, 'réservé');
    }
}
