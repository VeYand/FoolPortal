<?php
declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20241109235552 extends AbstractMigration
{
	public function getDescription(): string
	{
		return 'Добавление таблицы для хранения локаций';
	}

	public function up(Schema $schema): void
	{
		$this->addSql('CREATE TABLE location (
        		location_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid_binary)\',
        		name VARCHAR(255) NOT NULL,
        		PRIMARY KEY(location_id)
			) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB',
		);
	}

	public function down(Schema $schema): void
	{
		$this->addSql('DROP TABLE location');
	}
}
