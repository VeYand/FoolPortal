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
        		lesson_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid_binary)\',
        		date DATE NOT NULL,
        		start_time INT NOT NULL,
        		duration INT NOT NULL,
        		course_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid_binary)\',
        		location_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid_binary)\',
        		description LONGTEXT DEFAULT NULL,
        		PRIMARY KEY(lesson_id)
			) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB',
		);

		$this->addSql('ALTER TABLE lesson ADD CONSTRAINT FK_LESSON_LOCATION_ID FOREIGN KEY (location_id) REFERENCES `location` (location_id)');
	}

	public function down(Schema $schema): void
	{
		$this->addSql('ALTER TABLE lesson DROP FOREIGN KEY FK_LESSON_LOCATION_ID');
		$this->addSql('DROP TABLE lesson');
	}
}
