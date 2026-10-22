<?php
declare(strict_types=1);

namespace App\Model\Traits;

trait Journalisable
{
    private array $journal = [];

    public function log(string $message): void
    {
        $this->journal[] = date('H:i:s') . ' - ' . $message;
    }

    public function getJournal(): array
    {
        return $this->journal;
    }
}