<?php
declare(strict_types=1);

namespace App\User\Infrastructure\Query;

use App\User\App\Query\ImageQueryServiceInterface;

class ImageQueryService implements ImageQueryServiceInterface
{
	public function getImageUrl(?string $imagePath): ?string
	{
		return $imagePath;
	}
}