<?php
declare(strict_types=1);

namespace App\Tests\Unit\Common;

use App\Common\Event\EventPublisherInterface;

class MockEventPublisher implements EventPublisherInterface
{
    public function publish(object $event): void
    {
    }
}