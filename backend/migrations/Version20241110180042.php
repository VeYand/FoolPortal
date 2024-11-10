<?php
declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20241110180042 extends AbstractMigration
{
	public function getDescription(): string
	{
		return 'Создание таблицы для уроков';
	}

	public function up(Schema $schema): void
	{
		$this->addSql('CREATE TABLE lesson (
        		lesson_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\',
        		date DATE NOT NULL,
        		start_time INT NOT NULL,
        		duration INT NOT NULL,
        		course_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\',
        		location_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\',
        		description LONGTEXT DEFAULT NULL,
        		PRIMARY KEY(lesson_id)
			) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB',
		);
	}

	public function down(Schema $schema): void
	{
		$this->addSql('DROP TABLE lesson');
	}
}
