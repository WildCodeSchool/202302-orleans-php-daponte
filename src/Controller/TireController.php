<?php

namespace App\Controller;

use App\Model\TireManager;

class TireController extends AbstractController
{
    public function index(): string
    {
        $search = trim($_GET['search'] ?? '');
        $tireManager = new TireManager();
        $tires = $tireManager->findTire($search);

        return $this->twig->render('Tire/index.html.twig', [
            'tires' => $tires,
        ]);
    }
}
