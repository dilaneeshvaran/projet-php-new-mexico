<?php

namespace App\Controllers;

use App\Repositories\PhotoRepository;

class PublicPhotoController {
    public function show(string $token) {
        $photo = (new PhotoRepository())->findByToken($token);

        if (!$photo) {
            // Gérer l'erreur 404
        }

        // Afficher la vue publique
    }
}