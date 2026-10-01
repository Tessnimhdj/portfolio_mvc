<?php
namespace components\Cv\Models;

use Services\Storage\JsonStorage;

class CvModel {
    private $storage;
    private $file = 'cv.json';

    public function __construct() {
        $this->storage = new JsonStorage();
    }

    public function get(): ?array {
        $data = $this->storage->read($this->file);
        if (empty($data['file_url'])) {
            return null;
        }
        return $data;
    }

    public function save(array $data): bool {
        $data['uploaded_at'] = date('Y-m-d H:i:s');
        return $this->storage->write($this->file, $data);
    }

    public function clear(): bool {
        return $this->storage->write($this->file, []);
    }
}
