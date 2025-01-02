<?php
declare(strict_types=1);

namespace App;

use App\Common\Uuid\UuidBinaryType;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Doctrine\DBAL\Types\Type;

if (!Type::hasType('uuid_binary'))
{
	Type::addType('uuid_binary', UuidBinaryType::class);
}

class Kernel extends BaseKernel
{
	use MicroKernelTrait;
}
