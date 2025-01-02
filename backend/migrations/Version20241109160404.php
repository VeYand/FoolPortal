<?php
declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20241109160404 extends AbstractMigration
{
	public function getDescription(): string
	{
		return 'Добавление таблицы для связи пользователей с группами';
	}

	public function up(Schema $schema): void
	{
		$this->addSql('CREATE TABLE group_member (
        		group_member_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid_binary)\',
        		group_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid_binary)\',
        		user_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid_binary)\',
        		PRIMARY KEY(group_member_id)
        	) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB',
		);

		$this->addSql('ALTER TABLE group_member ADD CONSTRAINT FK_GROUP_MEMBER_GROUP_ID FOREIGN KEY (group_id) REFERENCES `group` (group_id)');
		$this->addSql('ALTER TABLE group_member ADD CONSTRAINT FK_GROUP_MEMBER_USER_ID FOREIGN KEY (user_id) REFERENCES `user` (user_id)');
	}

	public function down(Schema $schema): void
	{
		$this->addSql('ALTER TABLE group_member DROP FOREIGN KEY FK_GROUP_MEMBER_GROUP_ID');
		$this->addSql('ALTER TABLE group_member DROP FOREIGN KEY FK_GROUP_MEMBER_USER_ID');
		$this->addSql('DROP TABLE group_member');
	}
}
