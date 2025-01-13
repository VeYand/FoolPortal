<?php
declare (strict_types=1);

namespace App\User\App\Query\Spec;

use App\Common\Uuid\UuidInterface;
use App\User\Domain\Model\UserRole;

readonly class ListUsersSpec
{
	/**
	 * @param UuidInterface[]|null $groupIds
	 * @param UserRole[]|null $roles
	 */
	public function __construct(
		public ?array  $groupIds = null,
		public ?string $orderField = null,
		public ?bool   $ascOrder = null,
		public ?int    $page = null,
		public ?int    $limit = null,
		public ?string $sortField = null,
		public ?array  $roles = null,
	)
	{
	}
}