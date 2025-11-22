<?php
namespace Controller\Villes;
use JetBrains\PhpStorm\NoReturn;
use Repository\VillesRepository;

class VillesController
{
    public function show(): void
    {
        require __DIR__ . '/../../View/villes/villes.php';
    }
    #[NoReturn]
    public function autoCompletion() {
        $term = $_GET['term'] ?? '';
        $repo = new VillesRepository();
        $villes = $repo->searchVille($term); // Appelle la nouvelle méthode

        header('Content-Type: application/json');
        echo json_encode($villes);
        exit;
    }


}