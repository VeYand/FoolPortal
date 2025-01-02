<?php
declare(strict_types=1);

namespace App\User\Domain\Service\Event;

use App\Common\Uuid\UuidInterface;

interface GroupDeletedEventInterface
{
	/**
	 * @return UuidInterface[]
	 */
	public function getGroupIds(): array;
}