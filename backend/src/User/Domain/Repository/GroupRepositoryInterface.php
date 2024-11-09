<?php
declare(strict_types=1);

namespace App\User\Domain\Repository;

use App\User\Domain\Model\Group;

interface GroupRepositoryInterface extends GroupReadRepositoryInterface
{
	public function store(Group $group): string;

	public function delete(Group $group): void;
}