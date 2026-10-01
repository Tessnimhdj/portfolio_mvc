<?php
namespace components\Skills\Models;

use Services\Storage\JsonStorage;

class SkillModel {
    private $storage;
    private $file = 'skills.json';

    public function __construct() {
        $this->storage = new JsonStorage();
    }

    public function getAll(): array {
        $skills = $this->storage->read($this->file);
        return array_reverse($skills);
    }

    public function find(int $id): ?array {
        return $this->storage->find($this->file, $id);
    }

    public function create(array $data): array {
        return $this->storage->append($this->file, $data);
    }

    public function update(int $id, array $data): bool {
        return $this->storage->update($this->file, $id, $data);
    }

    public function delete(int $id): bool {
        return $this->storage->delete($this->file, $id);
    }
}
