<?php
declare(strict_types=1);

namespace App\Common\Exception;

class DomainException extends \Exception
{
	/** @var array<string|int, string|int> */
	private array $data;

	/**
	 * @param array<string|int, string|int> $data
	 */
	public function __construct(string $message, int $code = 500, array $data = [], ThrowableInterface $previous = null)
	{
		parent::__construct($message, $code, $previous);

		$this->data = $data;
	}

	public function getData(): array
	{
		return $this->data;
	}
}