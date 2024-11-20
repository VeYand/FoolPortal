<?php
declare(strict_types=1);

namespace App\Common\Logger;

interface LoggerInterface
{
	public function logError(string $message): void;
}