<?php
declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20241110212635 extends AbstractMigration
{
	public function getDescription(): string
	{
		return 'Добавление таблицы для связи lesson с вложением';
	}

	public function up(Schema $schema): void
	{
		$this->addSql('CREATE TABLE lesson_attachment (
        		lesson_attachment_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\',
        		lesson_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\',
        		attachment_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\',
        		PRIMARY KEY(lesson_attachment_id)
			) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB',
		);

		$this->addSql('ALTER TABLE lesson_attachment ADD CONSTRAINT FK_LESSON_ATTACHMENT_LESSON_ID FOREIGN KEY (lesson_id) REFERENCES `lesson` (lesson_id)');
		$this->addSql('ALTER TABLE lesson_attachment ADD CONSTRAINT FK_LESSON_ATTACHMENT_ATTACHMENT_ID FOREIGN KEY (attachment_id) REFERENCES `attachment` (attachment_id)');
	}

	public function down(Schema $schema): void
	{
		$this->addSql('DROP TABLE lesson_attachment');
	}
}
