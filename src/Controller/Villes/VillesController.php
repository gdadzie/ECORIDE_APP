<?php
namespace Controller\Villes;
class VillesController
{
    public function show(): void
    {
        require __DIR__ . '/../../View/villes/villes.php';
    }

}