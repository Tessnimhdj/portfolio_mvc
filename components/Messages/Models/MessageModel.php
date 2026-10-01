<?php
namespace components\Messages\Models;

use Services\Storage\JsonStorage;

class MessageModel {
    private $storage;
    private $file = 'messages.json';
    
    public function __construct() {
        $this->storage = new JsonStorage();
    }
    
    public function getAll(): array {
        $messages = $this->storage->read($this->file);
        return array_reverse($messages);
    }
    
    public function getUnreadCount(): int {
        $messages = $this->storage->read($this->file);
        return count(array_filter($messages, fn($m) => ($m['status'] ?? 'unread') === 'unread'));
    }

    public function find(int $id): ?array {
        return $this->storage->find($this->file, $id);
    }

    public function create(array $data): array {
        $data['status'] = $data['status'] ?? 'unread';
        return $this->storage->append($this->file, $data);
    }

    public function markAsRead(int $id): bool {
        return $this->storage->update($this->file, $id, ['status' => 'read']);
    }

    public function delete(int $id): bool {
        return $this->storage->delete($this->file, $id);
    }
}