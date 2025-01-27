<?php

namespace App\Models;

class PhotoValidator {
    private array $file;
    private array $errors = [];
    private const ALLOWED_TYPES = ['image/jpeg', 'image/png', 'image/gif'];
    private const MAX_FILE_SIZE = 5242880; // 5MB in bytes

    public function __construct(array $file) {
        $this->file = $file;
        $this->validate();
    }

    private function validate(): void {
        if ($this->file['error'] === UPLOAD_ERR_INI_SIZE) {
            $this->errors[] = 'File size exceeds the maximum limit of 5MB.';
            return; //exit if file is larger than 5MB
        }

        if ($this->file['error'] !== UPLOAD_ERR_OK) {
            $this->errors[] = 'Envoie du fichier échoué. Erreur: ' . $this->file['error'];
            return; //exit if a general error occurs
        }

        if (empty($this->file['tmp_name']) || !file_exists($this->file['tmp_name'])) {
            $this->errors[] = 'Fichier invalid.';
            return; //exit if the file is not available
        }

        //use finfo to validate MIME type
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $this->file['tmp_name']);
        finfo_close($finfo);

        if (!in_array($mimeType, self::ALLOWED_TYPES)) {
            $this->errors[] = 'Invalid file type. Allowed types: JPG, JPEG, PNG, GIF.';
        }

        if ($this->file['size'] > self::MAX_FILE_SIZE) {
            $this->errors[] = 'File size exceeds the maximum limit of 5MB.';
        }
    }

    public function getErrors(): array {
        return $this->errors;
    }
    
    public static function getAllowedTypes(): array {
        return self::ALLOWED_TYPES;
    }

    public static function getMaxFileSize(): int {
        return self::MAX_FILE_SIZE;
    }
}
