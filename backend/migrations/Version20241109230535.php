<?php
declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20241109230535 extends AbstractMigration
{
	public function getDescription(): string
	{
		return 'Создание таблицы для связи преподавателя с предметами';
	}

	public function up(Schema $schema): void
	{
		$this->addSql('CREATE TABLE teacher_subject (
        		teacher_subject_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\',
        		teacher_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\',
        		subject_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\',
        		PRIMARY KEY(teacher_subject_id)
			) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB',
		);

		$this->addSql('ALTER TABLE teacher_subject ADD CONSTRAINT FK_TEACHER_SUBJECT_SUBJECT_ID FOREIGN KEY (subject_id) REFERENCES `subject` (subject_id)');
	}

	public function down(Schema $schema): void
	{
		$this->addSql('ALTER TABLE teacher_subject DROP FOREIGN KEY FK_TEACHER_SUBJECT_SUBJECT_ID');
		$this->addSql('DROP TABLE teacher_subject');
	}
}
