<?php
declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20241109152704 extends AbstractMigration
{
	public function getDescription(): string
	{
		return 'Создание таблицы для пользователей';
	}

	public function up(Schema $schema): void
	{
		$this->addSql('CREATE TABLE user (
				user_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\',
				first_name VARCHAR(255) NOT NULL,
				last_name VARCHAR(255) NOT NULL,
				patronymic VARCHAR(255) DEFAULT NULL,
				role INT NOT NULL,
				image_path VARCHAR(255) DEFAULT NULL,
				email VARCHAR(255) NOT NULL,
				password VARCHAR(255) NOT NULL,
				PRIMARY KEY(user_id)
			) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB',
		);
	}

	public function down(Schema $schema): void
	{
		$this->addSql('DROP TABLE user');
	}
}
