<?php
namespace Controller\Utilisateurs;

class ProfilRoleController
{
    public function profilPassager(): void
    {
        require __DIR__ . '/../View/utilisateurs/passagers/profil_passager.php';
    }

    public function profilConducteur(): void
    {
        require __DIR__ . '/../View/utilisateurs/conducteur/profil_conducteur.php';
    }
}
