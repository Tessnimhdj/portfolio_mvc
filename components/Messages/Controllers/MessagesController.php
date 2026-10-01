<?php

namespace components\Messages\Controllers;

use Services\Security\AuthSession;
use components\Messages\Models\MessageModel;

class MessagesController
{
    public function index()
    {
        AuthSession::requireAuth();

        $messageModel = new MessageModel();
        $messages = $messageModel->getAll();

        require __DIR__ . '/../Views/index.php';
    }

    public function show()
    {
        AuthSession::requireAuth();

        $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
        $messageModel = new MessageModel();
        $message = $messageModel->find($id);

        if (!$message) {
            http_response_code(404);
            echo "Message not found";
            exit;
        }

        if (($message['status'] ?? 'unread') === 'unread') {
            $messageModel->markAsRead($id);
            $message['status'] = 'read';
        }

        require __DIR__ . '/../Views/show.php';
    }

    public function delete()
    {
        AuthSession::requireAuth();

        $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
        $messageModel = new MessageModel();
        $messageModel->delete($id);

        header('Location: ' . BASE_URL . '/Messages/index');
        exit;
    }
}
