<?php
namespace Controller\Utilisateurs;

use Repository\UtilisateursRepository;

class AvatarController
{
    private UtilisateursRepository $repo;

    public function __construct(UtilisateursRepository $repo)
    {
        $this->repo = $repo;
    }

    public function updateAvatar(): void
    {
        session_start();
        $userId = $_SESSION['user_id'] ?? null;
        if (!$userId) header('Location: index.php?entity=accueil&action=se_connecter');

        $utilisateur = $this->repo->findById($userId);
        $message = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_FILES['photo']['tmp_name'])) {
            try {
                $anciennePhoto = $utilisateur->getPhoto();
                $this->repo->uploadPhotoUtilisateur($utilisateur, $_FILES['photo']);

                // Supprime ancienne photo si ce n’est pas l’avatar par défaut
                if ($anciennePhoto && basename($anciennePhoto) !== 'default-avatar.jpg') {
                    $ancienChemin = __DIR__ . '/../../public/' . ltrim($anciennePhoto, '/');
                    if (file_exists($ancienChemin)) unlink($ancienChemin);
                }

                $_SESSION['user']->setPhoto($utilisateur->getPhoto());
                $message = "✅ Photo de profil mise à jour !";
            } catch (\Exception $e) {
                $message = "❌ " . $e->getMessage();
            }
        }

        require __DIR__ . '/../View/utilisateurs/mise_a_jour_avatar.php';
    }
}
