<?php

namespace components\Skills\Controllers;

use Services\Security\AuthSession;
use components\Skills\Models\SkillModel;

class SkillsController
{
    public function index()
    {
        AuthSession::requireAuth();

        $skillModel = new SkillModel();
        $skills = $skillModel->getAll();

        require __DIR__ . '/../Views/index.php';
    }

    public function show()
    {
        $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
        $skillModel = new SkillModel();
        $skill = $skillModel->find($id);

        if (!$skill) {
            http_response_code(404);
            echo "Skill not found";
            exit;
        }

        require __DIR__ . '/../Views/show.php';
    }

    public function create()
    {
        AuthSession::requireAuth();
        require __DIR__ . '/../Views/create.php';
    }

    public function store()
    {
        AuthSession::requireAuth();

        $errors = [];
        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $details = trim($_POST['details'] ?? '');
        $category = trim($_POST['category'] ?? '');

        if (empty($title)) {
            $errors[] = 'اسم المهارة مطلوب';
        }
        if (empty($description)) {
            $errors[] = 'الوصف المختصر مطلوب';
        }

        $coverImage = '';
        if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] === UPLOAD_ERR_OK) {
            $allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
            $maxSize = 2 * 1024 * 1024;
            $fileType = $_FILES['cover_image']['type'];
            $fileSize = $_FILES['cover_image']['size'];

            if (!in_array($fileType, $allowedTypes)) {
                $errors[] = 'نوع الصورة يجب أن يكون JPG أو PNG أو WEBP';
            } elseif ($fileSize > $maxSize) {
                $errors[] = 'حجم الصورة يجب ألا يتجاوز 2 ميجابايت';
            } else {
                $extension = pathinfo($_FILES['cover_image']['name'], PATHINFO_EXTENSION);
                $fileName = uniqid('skill_') . '.' . $extension;
                $uploadDir = __DIR__ . '/../../../Public/uploads/skills/';
                $uploadPath = $uploadDir . $fileName;

                if (move_uploaded_file($_FILES['cover_image']['tmp_name'], $uploadPath)) {
                    $coverImage = BASE_URL . '/uploads/skills/' . $fileName;
                } else {
                    $errors[] = 'فشل رفع الصورة';
                }
            }
        }

        if (!empty($errors)) {
            $old = $_POST;
            require __DIR__ . '/../Views/create.php';
            return;
        }

        $skillModel = new SkillModel();
        $skillModel->create([
            'title' => $title,
            'description' => $description,
            'details' => $details,
            'category' => $category,
            'cover_image' => $coverImage,
        ]);

        header('Location: ' . BASE_URL . '/Skills/index');
        exit;
    }

    public function edit()
    {
        AuthSession::requireAuth();
        $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
        $skillModel = new SkillModel();
        $skill = $skillModel->find($id);

        if (!$skill) {
            http_response_code(404);
            echo "Skill not found";
            exit;
        }

        require __DIR__ . '/../Views/edit.php';
    }

    public function update()
    {
        AuthSession::requireAuth();
        $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
        $skillModel = new SkillModel();
        $skill = $skillModel->find($id);

        if (!$skill) {
            http_response_code(404);
            echo "Skill not found";
            exit;
        }

        $errors = [];
        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $details = trim($_POST['details'] ?? '');
        $category = trim($_POST['category'] ?? '');

        if (empty($title)) {
            $errors[] = 'اسم المهارة مطلوب';
        }
        if (empty($description)) {
            $errors[] = 'الوصف المختصر مطلوب';
        }

        $coverImage = $skill['cover_image'] ?? '';
        if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] === UPLOAD_ERR_OK) {
            $allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
            $maxSize = 2 * 1024 * 1024;
            $fileType = $_FILES['cover_image']['type'];
            $fileSize = $_FILES['cover_image']['size'];

            if (!in_array($fileType, $allowedTypes)) {
                $errors[] = 'نوع الصورة يجب أن يكون JPG أو PNG أو WEBP';
            } elseif ($fileSize > $maxSize) {
                $errors[] = 'حجم الصورة يجب ألا يتجاوز 2 ميجابايت';
            } else {
                $extension = pathinfo($_FILES['cover_image']['name'], PATHINFO_EXTENSION);
                $fileName = uniqid('skill_') . '.' . $extension;
                $uploadDir = __DIR__ . '/../../../Public/uploads/skills/';
                $uploadPath = $uploadDir . $fileName;

                if (move_uploaded_file($_FILES['cover_image']['tmp_name'], $uploadPath)) {
                    $coverImage = BASE_URL . '/uploads/skills/' . $fileName;
                } else {
                    $errors[] = 'فشل رفع الصورة';
                }
            }
        }

        if (!empty($errors)) {
            $old = $_POST;
            require __DIR__ . '/../Views/edit.php';
            return;
        }

        $skillModel->update($id, [
            'title' => $title,
            'description' => $description,
            'details' => $details,
            'category' => $category,
            'cover_image' => $coverImage,
        ]);

        header('Location: ' . BASE_URL . '/Skills/index');
        exit;
    }

    public function delete()
    {
        AuthSession::requireAuth();
        $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
        $skillModel = new SkillModel();
        $skillModel->delete($id);

        header('Location: ' . BASE_URL . '/Skills/index');
        exit;
    }
}
