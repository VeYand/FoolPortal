<?php
declare(strict_types=1);

namespace App\User\Domain\Repository;

use App\Common\Uuid\UuidInterface;
use App\User\Domain\Model\Group;

interface GroupRepositoryInterface extends GroupReadRepositoryInterface
{
	public function store(Group $group): UuidInterface;

	public function delete(Group $group): void;
}