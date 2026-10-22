<?php
declare(strict_types=1);

namespace App\Http\Trait;

trait TraitementJson
{
    
    protected function getJsonData(): array
    {
        $brut = file_get_contents('php://input');
        $data = ($brut === false || $brut === '') ? [] : json_decode($brut, true);

        return is_array($data) ? $data : [];
    }

    protected function setJsonData(array $data, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    protected function sendErrorJson(string $message, int $status = 400): void
    {
        $this->setJsonData(['erreur' => $message], $status);
    }
}