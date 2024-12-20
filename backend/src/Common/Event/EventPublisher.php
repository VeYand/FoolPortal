<?php
declare(strict_types=1);

namespace App\Common\Event;

use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

readonly class EventPublisher implements EventPublisherInterface
{
	public function __construct(
		private EventDispatcherInterface $eventDispatcher,
	)
	{
	}

	public function publish(object $event): void
	{
		$this->eventDispatcher->dispatch($event);
	}
}