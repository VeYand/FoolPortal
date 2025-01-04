<?php
declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250401160406 extends AbstractMigration
{
	public function getDescription(): string
	{
		return 'Добавление индекса для поиска по времени для таблицы lesson';
	}

	public function up(Schema $schema): void
	{
		$this->addSql('CREATE INDEX LESSON_DATE_START_TIME_DURATION_IDX ON lesson (date, start_time, duration)');
	}

	public function down(Schema $schema): void
	{
		$this->addSql('DROP INDEX LESSON_DATE_START_TIME_DURATION_IDX ON lesson');
	}
}