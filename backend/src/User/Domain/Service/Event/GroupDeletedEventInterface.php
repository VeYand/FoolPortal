<?php
declare(strict_types=1);

namespace App\User\Domain\Service\Event;

interface GroupDeletedEventInterface
{
	public function getGroupId(): string;
}