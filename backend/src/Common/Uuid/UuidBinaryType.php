<?php
declare(strict_types=1);

namespace App\Common\Uuid;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\BinaryType;

class UuidBinaryType extends BinaryType
{
	public const string NAME = 'uuid_binary';

	public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
	{
		return 'BINARY(16)';
	}

	public function convertToPHPValue($value, AbstractPlatform $platform)
	{
		if ($value === null)
		{
			return null;
		}
		return RamseyUuid::fromBytes($value);
	}

	public function convertToDatabaseValue($value, AbstractPlatform $platform)
	{
		if ($value === null)
		{
			return null;
		}
		return $value instanceof RamseyUuid ? $value->toBytes() : RamseyUuid::fromString($value)->toBytes();
	}

	public function getName(): string
	{
		return self::NAME;
	}
}