<?php
declare(strict_types=1);

namespace App\User\Domain\Service\Event;

interface EventPublisherInterface
{
	public function publish(GroupDeletedEventInterface $event): void;
}