<?php
namespace Service;

use Repository\ReservationRepository;
use Entity\Reservation;
use Entity\Utilisateur;
use Entity\Covoiturage;
use DateTime;

class ReservationService
{
    private ReservationRepository $reservationRepo;

    public function __construct(ReservationRepository $reservationRepo)
    {
        $this->reservationRepo = $reservationRepo;
    }

    /**
     * Créer une réservation pour un covoiturage
     */
    public function createReservation(Utilisateur $user, Covoiturage $covoiturage, float $montant): bool
    {
        $reservation = new Reservation();
        $reservation->setIdUtilisateur($user->getIdUtilisateur());
        $reservation->setIdCovoiturage($covoiturage->getIdCovoiturage());
        $reservation->setDateReservation((new DateTime())->format('Y-m-d H:i:s'));
        $reservation->setStatut('en cours');
        $reservation->setConfirmation($montant);

        return $this->reservationRepo->save($reservation);
    }

    /**
     * Récupérer toutes les réservations d’un utilisateur
     */
    public function getReservationsByUser(Utilisateur $user): array
    {
        return $this->reservationRepo->getByUserId($user->getIdUtilisateur());
    }
}
