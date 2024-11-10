<?php
declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20241110204246 extends AbstractMigration
{
	public function getDescription(): string
	{
		return 'Создание таблицы с вложениями';
	}

	public function up(Schema $schema): void
	{
		$this->addSql('CREATE TABLE attachment (
        		attachment_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\',
        		name VARCHAR(255) NOT NULL,
        		description VARCHAR(255) DEFAULT NULL,
        		path VARCHAR(255) NOT NULL,
        		extension VARCHAR(255) NOT NULL,
        		PRIMARY KEY(attachment_id)
			) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB',
		);
	}

	public function down(Schema $schema): void
	{
		$this->addSql('DROP TABLE attachment');
	}
}
