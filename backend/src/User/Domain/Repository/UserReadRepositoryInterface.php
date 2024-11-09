<?php
declare(strict_types=1);

namespace App\User\Domain\Repository;

use App\User\Domain\Model\User;

interface UserReadRepositoryInterface
{
    public function find(string $userId): ?User;
}