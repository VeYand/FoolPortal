<?php
declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20241109160405 extends AbstractMigration
{
	public function getDescription(): string
	{
		return 'Добавление индекса по email для таблицы с пользователями';
	}

	public function up(Schema $schema): void
	{
		$this->addSql('CREATE UNIQUE INDEX IDX_USER_EMAIL ON user (email)');
	}

	public function down(Schema $schema): void
	{
		$this->addSql('DROP INDEX IDX_USER_EMAIL ON user');
	}
}