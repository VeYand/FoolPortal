<?php
declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20241109154521 extends AbstractMigration
{
	public function getDescription(): string
	{
		return 'Создание таблицы пользовательских групп';
	}

	public function up(Schema $schema): void
	{
		$this->addSql('CREATE TABLE `group` (
        		group_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid_binary)\',
        		name VARCHAR(255) NOT NULL,
        		PRIMARY KEY(group_id)
        	) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB',
		);
	}

	public function down(Schema $schema): void
	{
		$this->addSql('DROP TABLE `group`');
	}
}
