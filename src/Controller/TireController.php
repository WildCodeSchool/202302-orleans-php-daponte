<?php

namespace App\Controller;

use App\Model\CategoryManager;
use App\Model\TireManager;

class TireController extends AbstractController
{
    public function index(): string
    {
        $search = array_map('trim', $_GET);

        $tireManager = new TireManager();
        $tires = $tireManager->findTire($search);

        $categoryManager = new CategoryManager();
        $categories = $categoryManager->selectAll();

        return $this->twig->render('Tire/index.html.twig', [
            'tires' => $tires,
            'categories' => $categories,
            'search' => $search,
        ]);
    }
}
