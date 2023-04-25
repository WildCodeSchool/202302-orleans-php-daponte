<?php

namespace App\Controller;

use App\Model\CategoryManager;
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

    private function validate(array $tire, array $categories): array
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

        $categoryIds = array_column($categories, 'id');

        if (!in_array($tire['category'], $categoryIds)) {
            $errors[] = 'La categorie est invalide';
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

        $categoryManager = new CategoryManager();
        $categories = $categoryManager->selectAll('name');

        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // nettoyage
            $tire = array_map('trim', $_POST);
            // validation
            $errors = $this->validate($tire, $categories);

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
            'categories' => $categories,
        ]);
    }

    public function create(): string
    {
        $errors = $tire = [];

        $categoryManager = new CategoryManager();
        $categories = $categoryManager->selectAll('name');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // nettoyage
            $tire = array_map('trim', $_POST);
            // validation
            $errors = $this->validate($tire, $categories);

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
            'categories' => $categories,
        ]);
    }

    public function delete(int $id): void
    {
        // si je suis en post
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // delete en bdd
            $tireManager = new TireManager();
            $tireManager->delete($id);

            // redirec admin/pneus
            header('Location: /admin/pneus');
        }
    }
}
