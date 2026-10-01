<?php
namespace Services\Storage;

class JsonStorage {
    private $basePath;
    
    public function __construct() {
        $this->basePath = __DIR__ . '/';
        $this->ensureDirectory();
    }
    
    private function ensureDirectory() {
        if (!is_dir($this->basePath)) {
            mkdir($this->basePath, 0777, true);
        }
    }
    
    public function read(string $file): array {
        $path = $this->basePath . $file;
        if (!file_exists($path)) {
            return [];
        }
        $content = file_get_contents($path);
        return json_decode($content, true) ?? [];
    }
    
    public function write(string $file, array $data): bool {
        $path = $this->basePath . $file;
        $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        return file_put_contents($path, $json) !== false;
    }
    
    public function append(string $file, array $item): array {
        $data = $this->read($file);
        $item['id'] = $this->generateId($data);
        $item['created_at'] = date('Y-m-d H:i:s');
        $data[] = $item;
        $this->write($file, $data);
        return $item;
    }
    
    public function find(string $file, int $id): ?array {
        $data = $this->read($file);
        foreach ($data as $item) {
            if ($item['id'] == $id) {
                return $item;
            }
        }
        return null;
    }
    
    public function update(string $file, int $id, array $updates): bool {
        $data = $this->read($file);
        foreach ($data as &$item) {
            if ($item['id'] == $id) {
                $item = array_merge($item, $updates);
                $item['updated_at'] = date('Y-m-d H:i:s');
                return $this->write($file, $data);
            }
        }
        return false;
    }
    
    public function delete(string $file, int $id): bool {
        $data = $this->read($file);
        $data = array_filter($data, fn($item) => $item['id'] != $id);
        $data = array_values($data);
        return $this->write($file, $data);
    }
    
    private function generateId(array $data): int {
        if (empty($data)) return 1;
        $ids = array_column($data, 'id');
        return max($ids) + 1;
    }
}
