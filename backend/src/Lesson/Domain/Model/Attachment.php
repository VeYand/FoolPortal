<?php
declare(strict_types=1);

namespace App\Lesson\Domain\Model;

class Attachment
{
	private int $type;

	public function __construct(
		private readonly string $attachmentId,
		private string          $name,
		private ?string         $description,
		private readonly string $path,
		AttachmentType          $type,
	)
	{
		$this->type = $type->value;
	}

	public function getAttachmentId(): string
	{
		return $this->attachmentId;
	}

	public function getName(): string
	{
		return $this->name;
	}

	public function setName(string $name): void
	{
		$this->name = $name;
	}

	public function getDescription(): ?string
	{
		return $this->description;
	}

	public function setDescription(?string $description): void
	{
		$this->description = $description;
	}

	public function getPath(): string
	{
		return $this->path;
	}

	public function getType(): int
	{
		return $this->type;
	}

	public function setType(AttachmentType $type): void
	{
		$this->type = $type->value;
	}
}