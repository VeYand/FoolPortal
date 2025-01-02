<?php
declare(strict_types=1);

namespace App\Controller\Converter;

use App\Lesson\App\Query\Data\AttachmentData;
use OpenAPI\Server\Model\AttachmentInfo as ApiAttachmentInfo;

readonly class AttachmentModelConverter
{
	/**
	 * @param AttachmentData[] $attachment
	 * @return ApiAttachmentInfo[]
	 */
	public static function convertAppAttachmentsToApiAttachments(array $attachment): array
	{
		return array_map(
			static fn(AttachmentData $attachment) => self::convertAppAttachmentToApiAttachment($attachment),
			$attachment,
		);
	}

	public static function convertAppAttachmentToApiAttachment(AttachmentData $attachment): ApiAttachmentInfo
	{
		return new ApiAttachmentInfo([
			'attachmentId' => $attachment->attachmentId->toString(),
			'extension' => $attachment->extension,
			'name' => $attachment->name,
			'description' => $attachment->description,
		]);
	}
}