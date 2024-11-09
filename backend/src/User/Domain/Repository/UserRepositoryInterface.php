<?php
declare(strict_types=1);

namespace App\User\Domain\Repository;

use App\User\Domain\Model\User;

interface UserRepositoryInterface extends UserReadRepositoryInterface
{
    public function store(User $user): string;

    public function delete(User $user): void;
}