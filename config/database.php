<?php
// config/database.php
class JsonDB
{
    private $dir;

    public function __construct()
    {
        $this->dir = __DIR__ . '/../data/';
        if (!is_dir($this->dir)) mkdir($this->dir, 0755, true);
        foreach (['users.json', 'quizzes.json', 'responses.json'] as $f) {
            $p = $this->dir . $f;
            if (!file_exists($p)) file_put_contents($p, json_encode([]));
        }
    }

    public function read(string $file): array
    {
        $path = $this->dir . $file . '.json';
        $json = file_get_contents($path);
        return json_decode($json, true) ?: [];
    }

    public function write(string $file, array $data): void
    {
        $path = $this->dir . $file . '.json';
        file_put_contents($path, json_encode(array_values($data), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }
}
