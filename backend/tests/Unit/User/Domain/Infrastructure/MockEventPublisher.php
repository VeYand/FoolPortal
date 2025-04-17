<?php
declare(strict_types=1);

namespace App\Tests\Unit\User\Domain\Infrastructure;

use App\Common\Event\EventPublisherInterface;

class MockEventPublisher implements EventPublisherInterface
{
	private array $events = [];

	public function publish(object $event): void
	{
		$this->events[] = $event;
	}

	public function getEvents(): array
	{
		return $this->events;
	}
}