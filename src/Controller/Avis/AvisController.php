<?php
namespace Controller\Avis;

use JetBrains\PhpStorm\NoReturn;
use Repository\AvisRepository;
use Entity\Avis;

class AvisController
{
    private AvisRepository $avisRepo;

    public function __construct(AvisRepository $repo)
    {
        $this->avisRepo = $repo;
    }

    /**
     * ➤ Ajouter un avis
     */
    #[NoReturn]
    public function ajouter(): void
    {
        $avis = new Avis(
            $_POST['id_covoiturage'],
            $_SESSION['user_id'], // émetteur
            $_POST['id_receveur'], // conducteur
            $_POST['note'] ?? null,
            $_POST['commentaire'] ?? null
        );

        $id = $this->avisRepo->create($avis);

        header("Location: index.php?entity=covoiturages&action=detail_covoiturage&id=" . $_POST['id_covoiturage']);
        exit;
    }

    /**
     * ➤ Modifier un avis
     */
    #[NoReturn]
    public function modifier(): void
    {
        $avis = new Avis(0,0,0); // valeurs non utilisées
        $avis->setIdAvis($_POST['id_avis']);
        $avis->setNote($_POST['note']);
        $avis->setCommentaire($_POST['commentaire']);

        $this->avisRepo->update($avis);

        header("Location: index.php?entity=covoiturages&action=detail_covoiturage&id=" . $_POST['id_covoiturage']);
        exit;
    }

    /**
     * ➤ Récupérer la note moyenne d’un conducteur
     */
    public function noteMoyenne(int $idUtilisateur): ?float
    {
        return $this->avisRepo->getMoyenneUtilisateur($idUtilisateur);
    }

    public function getNbAvisByUtilisateur(int $idUtilisateur): int
    {

        return $this->avisRepo->getNbAvisByUtilisateur($idUtilisateur);


    }

    function showAvis(): void
    {

        require_once __DIR__ .'/../../View/utilisateurs/avis.php';
    }
}
