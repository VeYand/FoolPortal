<?php
declare(strict_types=1);

namespace App\Common\Uuid;

readonly class UuidUtils
{
	/**
	 * @param UuidInterface[] $uuids
	 * @return string[]
	 */
	public static function convertToBinaryList(array $uuids): array
	{
		return array_map(static fn(UuidInterface $uuid) => $uuid->toBytes(), $uuids);
	}
}