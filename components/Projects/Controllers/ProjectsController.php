<?php

namespace components\Projects\Controllers;

use Services\Security\AuthSession;
use components\Projects\Models\ProjectModel;

class ProjectsController
{
    public function index()
    {
        AuthSession::requireAuth();

        $projectModel = new ProjectModel();
        $projects = $projectModel->getAll();

        require __DIR__ . '/../Views/index.php';
    }

    public function show()
    {
        $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
        $projectModel = new ProjectModel();
        $project = $projectModel->find($id);

        if (!$project) {
            http_response_code(404);
            echo "Project not found";
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
        $technologies = trim($_POST['technologies'] ?? '');
        $demoUrl = trim($_POST['demo_url'] ?? '');
        $githubUrl = trim($_POST['github_url'] ?? '');

        if (empty($title)) {
            $errors[] = 'العنوان مطلوب';
        }
        if (empty($description)) {
            $errors[] = 'الوصف المختصر مطلوب';
        }

        $coverImage = '';
        if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] === UPLOAD_ERR_OK) {
            $allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
            $maxSize = 2 * 1024 * 1024; // 2MB
            $fileType = $_FILES['cover_image']['type'];
            $fileSize = $_FILES['cover_image']['size'];

            if (!in_array($fileType, $allowedTypes)) {
                $errors[] = 'نوع الصورة يجب أن يكون JPG أو PNG أو WEBP';
            } elseif ($fileSize > $maxSize) {
                $errors[] = 'حجم الصورة يجب ألا يتجاوز 2 ميجابايت';
            } else {
                $extension = pathinfo($_FILES['cover_image']['name'], PATHINFO_EXTENSION);
                $fileName = uniqid('project_') . '.' . $extension;
                $uploadDir = __DIR__ . '/../../../Public/uploads/projects/';
                $uploadPath = $uploadDir . $fileName;

                if (move_uploaded_file($_FILES['cover_image']['tmp_name'], $uploadPath)) {
                    $coverImage = BASE_URL . '/uploads/projects/' . $fileName;
                } else {
                    $errors[] = 'فشل رفع الصورة';
                }
            }
        } else {
            $errors[] = 'صورة الغلاف مطلوبة';
        }

        if (!empty($errors)) {
            $old = $_POST;
            require __DIR__ . '/../Views/create.php';
            return;
        }

        $projectModel = new \components\Projects\Models\ProjectModel();
        $projectModel->create([
            'title' => $title,
            'description' => $description,
            'details' => $details,
            'cover_image' => $coverImage,
            'technologies' => array_map('trim', explode(',', $technologies)),
            'demo_url' => $demoUrl,
            'github_url' => $githubUrl,
        ]);

        header('Location: ' . BASE_URL . '/Projects/index');
        exit;
    }

    public function edit()
    {
        AuthSession::requireAuth();
        $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
        $projectModel = new ProjectModel();
        $project = $projectModel->find($id);

        if (!$project) {
            http_response_code(404);
            echo "Project not found";
            exit;
        }

        require __DIR__ . '/../Views/edit.php';
    }

    public function update()
    {
        AuthSession::requireAuth();
        $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
        $projectModel = new ProjectModel();
        $project = $projectModel->find($id);

        if (!$project) {
            http_response_code(404);
            echo "Project not found";
            exit;
        }

        $errors = [];
        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $details = trim($_POST['details'] ?? '');
        $technologies = trim($_POST['technologies'] ?? '');
        $demoUrl = trim($_POST['demo_url'] ?? '');
        $githubUrl = trim($_POST['github_url'] ?? '');

        if (empty($title)) {
            $errors[] = 'العنوان مطلوب';
        }
        if (empty($description)) {
            $errors[] = 'الوصف المختصر مطلوب';
        }

        $coverImage = $project['cover_image'] ?? '';
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
                $fileName = uniqid('project_') . '.' . $extension;
                $uploadDir = __DIR__ . '/../../../Public/uploads/projects/';
                $uploadPath = $uploadDir . $fileName;

                if (move_uploaded_file($_FILES['cover_image']['tmp_name'], $uploadPath)) {
                    $coverImage = BASE_URL . '/uploads/projects/' . $fileName;
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

        $projectModel->update($id, [
            'title' => $title,
            'description' => $description,
            'details' => $details,
            'cover_image' => $coverImage,
            'technologies' => array_map('trim', explode(',', $technologies)),
            'demo_url' => $demoUrl,
            'github_url' => $githubUrl,
        ]);

        header('Location: ' . BASE_URL . '/Projects/index');
        exit;
    }

    public function delete()
    {
        AuthSession::requireAuth();
        $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
        $projectModel = new ProjectModel();
        $projectModel->delete($id);

        header('Location: ' . BASE_URL . '/Projects/index');
        exit;
    }
}
