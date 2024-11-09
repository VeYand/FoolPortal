<?php
declare(strict_types=1);

namespace App\Common\Uuid;

use Symfony\Component\Uid\Uuid;

class UuidProvider implements UuidProviderInterface
{
    public function generate(): string
    {
        return Uuid::v7()->toBinary();
    }
}