<?php

namespace components\Cv\Controllers;

use Services\Security\AuthSession;
use components\Cv\Models\CvModel;

class CvController
{
    private function uploadDir(): string
    {
        return __DIR__ . '/../../../Public/uploads/cv/';
    }

    private function removeStoredFile(?array $cv): void
    {
        if (empty($cv['file_name'])) {
            return;
        }

        $path = $this->uploadDir() . $cv['file_name'];
        if (is_file($path)) {
            unlink($path);
        }
    }

    public function index()
    {
        AuthSession::requireAuth();

        $cvModel = new CvModel();
        $cv = $cvModel->get();

        require __DIR__ . '/../Views/index.php';
    }

    public function store()
    {
        AuthSession::requireAuth();

        $cvModel = new CvModel();
        $cv = $cvModel->get();
        $errors = [];

        if (!isset($_FILES['cv_file']) || $_FILES['cv_file']['error'] !== UPLOAD_ERR_OK) {
            $errors[] = 'ملف PDF مطلوب';
        } else {
            $maxSize = 5 * 1024 * 1024;
            $tmpPath = $_FILES['cv_file']['tmp_name'];
            $originalName = $_FILES['cv_file']['name'];
            $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
            $header = file_get_contents($tmpPath, false, null, 0, 4);

            if ($extension !== 'pdf' || $header !== '%PDF') {
                $errors[] = 'يجب أن يكون الملف بصيغة PDF';
            } elseif ($_FILES['cv_file']['size'] > $maxSize) {
                $errors[] = 'حجم الملف يجب ألا يتجاوز 5 ميجابايت';
            } else {
                $uploadDir = $this->uploadDir();
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }

                $fileName = uniqid('cv_') . '.pdf';
                $uploadPath = $uploadDir . $fileName;

                if (move_uploaded_file($tmpPath, $uploadPath)) {
                    $this->removeStoredFile($cv);
                    $cvModel->save([
                        'file_name' => $fileName,
                        'original_name' => $originalName,
                        'file_url' => BASE_URL . '/uploads/cv/' . $fileName,
                    ]);

                    header('Location: ' . BASE_URL . '/Cv/index');
                    exit;
                }

                $errors[] = 'فشل رفع الملف';
            }
        }

        require __DIR__ . '/../Views/index.php';
    }

    public function delete()
    {
        AuthSession::requireAuth();

        $cvModel = new CvModel();
        $cv = $cvModel->get();
        $this->removeStoredFile($cv);
        $cvModel->clear();

        header('Location: ' . BASE_URL . '/Cv/index');
        exit;
    }
}
