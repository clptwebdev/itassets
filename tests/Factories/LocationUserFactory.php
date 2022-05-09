<?php

namespace Tests\Factories;

class LocationUserFactory {

    private int $userId = 1;
    private int $location_id = 2;

    public static function new(): self
    {
        return new self();
    }

    public function create(array $extra = []): array
    {
        return $extra + [
                'user_id' => $this->userId,
                'location_id' => $this->location_id,
            ];
    }

}
