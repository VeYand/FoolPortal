<?php
declare(strict_types=1);

namespace App\User\Domain\Service\Exception;

use App\Common\Exception\DomainException;

class UserNotFoundException extends DomainException
{
	private const string DEFAULT_MESSAGE = 'User not found';

	public function __construct(string $message = self::DEFAULT_MESSAGE, int $code = 404)
	{
		parent::__construct($message, $code);
	}
}