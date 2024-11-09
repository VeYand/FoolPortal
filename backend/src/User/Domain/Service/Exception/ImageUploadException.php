<?php
declare(strict_types=1);

namespace App\User\Domain\Service\Exception;

use App\Common\Exception\DomainException;

class ImageUploadException extends DomainException
{
	private const string DEFAULT_MESSAGE = 'Cannot upload image';

	public function __construct(string $message = self::DEFAULT_MESSAGE, int $code = 400)
	{
		parent::__construct($message, $code);
	}
}