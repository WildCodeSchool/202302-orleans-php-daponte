<?php

namespace App\Controller;

use App\Model\TireManager;

class TireController extends AbstractController
{
    public function index(): string
    {
        $tireManager = new TireManager();
        $tires = $tireManager->selectTires();

        return $this->twig->render('Tire/index.html.twig', [
            'tires' => $tires,
        ]);
    }
}
