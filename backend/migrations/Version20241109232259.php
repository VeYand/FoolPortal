<?php
declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20241109232259 extends AbstractMigration
{
	public function getDescription(): string
	{
		return 'Добавление таблицы для связи teacher_subject с предметом';
	}

	public function up(Schema $schema): void
	{
		$this->addSql('CREATE TABLE course (
				course_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\',
				teacher_subject_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\',
				group_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\',
				PRIMARY KEY(course_id)
			) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB',
		);

		$this->addSql('ALTER TABLE course ADD CONSTRAINT FK_COURSE_TEACHER_SUBJECT_ID FOREIGN KEY (teacher_subject_id) REFERENCES `teacher_subject` (teacher_subject_id)');
	}

	public function down(Schema $schema): void
	{
		$this->addSql('ALTER TABLE course DROP FOREIGN KEY FK_COURSE_TEACHER_SUBJECT_ID');
		$this->addSql('DROP TABLE course');
	}
}
