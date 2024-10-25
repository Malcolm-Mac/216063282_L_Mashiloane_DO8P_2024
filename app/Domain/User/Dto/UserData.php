<?php

namespace App\Domain\User\Dto;

use Spatie\LaravelData\Data;

class UserData extends Data
{
    public function __construct(
        public string $name,
        public string $surname,
        public string $email,
        public ?string $password,
        public string $role,
        public ?array $campaign_id = []
    ) {
    }
}
