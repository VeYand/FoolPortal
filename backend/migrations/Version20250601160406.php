<?php
declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250601160406 extends AbstractMigration
{
	public function getDescription(): string
	{
		return 'Добавление индекса по name для таблицы с предметами';
	}

	public function up(Schema $schema): void
	{
		$this->addSql('CREATE UNIQUE INDEX SUBJECT_NAME_IDX ON subject (name)');
	}

	public function down(Schema $schema): void
	{
		$this->addSql('DROP INDEX SUBJECT_NAME_IDX ON subject');
	}
}