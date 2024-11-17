<?php
declare(strict_types=1);

namespace App\Common\Event;

interface EventPublisherInterface
{
	public function publish(object $event): void;
}