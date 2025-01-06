<?php
declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250601160407 extends AbstractMigration
{
	public function getDescription(): string
	{
		return 'Добавление индекса по name для таблицы с локациями';
	}

	public function up(Schema $schema): void
	{
		$this->addSql('CREATE UNIQUE INDEX LOCATION_NAME_IDX ON location (name)');
	}

	public function down(Schema $schema): void
	{
		$this->addSql('DROP INDEX LOCATION_NAME_IDX ON location');
	}
}