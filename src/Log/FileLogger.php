<?php
declare(strict_types=1);

namespace App\Log;

use App\Config\Traits\traitementEnv;

final class FileLogger
{
    use traitementEnv;

    private string $path;

    public function __construct() {
        $this->path = dirname(__DIR__, 2).self::getConfig("Log")["LOG_PATH"];
    }

    public function error(string $message): void
    {
        $this->write('ERROR', $message);
    }

    public function info(string $message): void 
    {
        $this->write('INFO', $message);
    }

    private function write(string $level, string $message): void
    {
        $dir = dirname($this->path);
        if (!is_dir($dir)) {
            mkdir($dir, 0775, true);
        }

        $line = sprintf("[%s] %s: %s%s", date('Y-m-d H:i:s'), $level, $message, PHP_EOL);
        file_put_contents($this->path, $line, FILE_APPEND | LOCK_EX);
    }
}