<?php
declare(strict_types=1);

namespace App\User\Domain\Repository;

use App\Common\Uuid\UuidInterface;
use App\User\Domain\Model\Group;

interface GroupReadRepositoryInterface
{
	public function find(UuidInterface $groupId): ?Group;

	/**
	 * @return Group[]
	 */
	public function findAll(): array;
}