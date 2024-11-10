<?php
declare(strict_types=1);

namespace App\Common\Provider;

interface EnvironmentProviderInterface
{
	public function getUserImageUploadDirectory(): string;

	public function getAttachmentUploadDirectory(): string;
}