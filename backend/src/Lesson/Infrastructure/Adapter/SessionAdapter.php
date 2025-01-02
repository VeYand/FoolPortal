<?php
declare(strict_types=1);

namespace App\Lesson\Infrastructure\Adapter;

use App\Lesson\App\Adapter\SessionAdapterInterface;
use App\Session\Api\SessionApiInterface;

readonly class SessionAdapter implements SessionAdapterInterface
{
	public function __construct(
		private SessionApiInterface $sessionApi,
	)
	{
	}

	public function getCurrentUserId(): string
	{
		return $this->sessionApi->getCurrentUser()->id;
	}
}