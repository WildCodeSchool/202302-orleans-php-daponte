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
    private function validateUpload(array $files): array
    {
        $errors = [];
        if ($files['image']['error'] !== 0) {
            $errors[] = 'Problème avec l\'upload, veuillez réessayer';
        } else {
            $limitFileSize = '1000000';
            if ($files['image']['size'] > $limitFileSize) {
                $errors[] = 'Le fichier doit faire moins de ' . $limitFileSize / 1000000 . 'Mo';
            }

            $authorizedMimes = ['image/jpeg', 'image/png', 'image/webp'];
            if (!in_array(mime_content_type($files['image']['tmp_name']), $authorizedMimes)) {
                $errors[] = 'Le type de fichier est incorrect. Types autorisées : ' . implode(', ', $authorizedMimes);
            }
        }

        return $errors;
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
            $dataErrors = $this->validate($tire, $categories);
            $uploadErrors = $this->validateUpload($_FILES);

            $errors = array_merge($dataErrors, $uploadErrors);

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
                // nom du fichier uploadé
                $extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                $baseFilename = pathinfo($_FILES['image']['name'], PATHINFO_FILENAME);
                $imageName = uniqid($baseFilename, more_entropy: true) . '.' . $extension;

                $tire['image'] = $imageName;
                // insertion
                $tireManager = new TireManager();
                $tireManager->insert($tire);

                // move upload
                move_uploaded_file($_FILES['image']['tmp_name'], __DIR__ . '/../../public/uploads/'  . $imageName);
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
            $tire = $tireManager->selectOneById($id);

            if (isset($tire['image']) && file_exists(__DIR__ . '/../../public/uploads/' . $tire['image'])) {
                unlink(__DIR__ . '/../../public/uploads/' . $tire['image']);
            }

            $tireManager->delete($id);


            // redirec admin/pneus
            header('Location: /admin/pneus');
        }
    }
}
