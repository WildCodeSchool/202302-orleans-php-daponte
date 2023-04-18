<?php

namespace App\Controller;

use App\Model\TireManager;

class AdminTireController extends AbstractController
{
    public function index(): string
    {
        $tireManager = new TireManager();
        $tires = $tireManager->selectAll();

        return $this->twig->render('Admin/Tire/index.html.twig', [
            'tires' => $tires,
        ]);
    }

    public function create(): string
    {
        $errors = $tire = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // nettoyage
            $tire = array_map('trim', $_POST);
            // validation
            if (empty($tire['name'])) {
                $errors[] = 'Le champ nom est obligatoire';
            }
            $maxLength = 255;
            if (mb_strlen($tire['name']) > $maxLength) {
                $errors[] = 'Le champ nom doit faire moins de ' . $maxLength . ' caractères';
            }

            if (empty($tire['price'])) {
                $errors[] = 'Le champ prix est obligatoire';
            }

            if (!is_numeric($tire['price']) || $tire['price'] < 0) {
                $errors[] = 'Le champ prix doit être un nombre positif';
            }

            if (empty($errors)) {
                // insertion
                $tireManager = new TireManager();
                $tireManager->insert($tire);

                // redirection
                header('Location: /admin/pneus');
            }
        }

        return $this->twig->render('Admin/Tire/create.html.twig', [
            'errors' => $errors,
            'tire' => $tire,
        ]);
    }
}
