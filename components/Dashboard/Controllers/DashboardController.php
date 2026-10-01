<?php

namespace components\Dashboard\Controllers;

use Services\Security\AuthSession;
use components\Messages\Models\MessageModel;
use components\Projects\Models\ProjectModel;
use components\Skills\Models\SkillModel;
use components\Cv\Models\CvModel;

class DashboardController
{

    public function index()
    {
        AuthSession::requireAuth();

        $messageModel = new MessageModel();
        $projectModel = new ProjectModel();
        $skillModel = new SkillModel();
        $cvModel = new CvModel();

        $messages = $messageModel->getAll();
        $projects = $projectModel->getAll();
        $skills = $skillModel->getAll();
        $cv = $cvModel->get();

        $stats = [
            'total_messages' => count($messages),
            'unread_messages' => $messageModel->getUnreadCount(),
            'total_projects' => count($projects),
            'total_skills' => count($skills),
            'has_cv' => !empty($cv),
            'last_message' => $messages[0] ?? null,
            'recent_messages' => array_slice($messages, 0, 5)
        ];

        $admin = AuthSession::getAdmin();

        require __DIR__ . '/../Views/index.php';
    }
}
