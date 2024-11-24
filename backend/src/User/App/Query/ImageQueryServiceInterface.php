<?php
declare(strict_types=1);

namespace App\User\App\Query;

interface ImageQueryServiceInterface
{
	public function getImageUrl(?string $imagePath): ?string;
}