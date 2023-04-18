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

    private function validate(array $tire): array
    {
        $errors = [];

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

        return $errors;
    }

    public function update(int $id): string
    {
        $tireManager = new TireManager();
        $tire = $tireManager->selectOneById($id);

        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // nettoyage
            $tire = array_map('trim', $_POST);
            // validation
            $errors = $this->validate($tire);

            if (empty($errors)) {
                // insertion
                $tireManager = new TireManager();
                $tire['id'] = $id;
                $tireManager->update($tire);

                // redirection
                header('Location: /admin/pneus');
            }
        }


        return $this->twig->render('Admin/Tire/update.html.twig', [
            'tire' => $tire,
        ]);
    }

    public function create(): string
    {
        $errors = $tire = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // nettoyage
            $tire = array_map('trim', $_POST);
            // validation
            $errors = $this->validate($tire);

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
