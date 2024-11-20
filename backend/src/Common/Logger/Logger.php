<?php
declare(strict_types=1);

namespace App\Common\Logger;

use Psr\Log\LoggerInterface as PsrLoggerInterface;

readonly class Logger implements LoggerInterface
{
	public function __construct(
		private PsrLoggerInterface $logger,
	)
	{
	}

	public function logError(string $message): void
	{
		$this->logger->error($message);
	}
}