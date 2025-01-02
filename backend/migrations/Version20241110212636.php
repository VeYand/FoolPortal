<?php
declare(strict_types=1);

namespace DoctrineMigrations;

use App\Common\Uuid\UuidProvider;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20241110212636 extends AbstractMigration
{
	public function getDescription(): string
	{
		return 'Добавление пользователя owner';
	}

	public function up(Schema $schema): void
	{
		$uuid = (new UuidProvider())->generate();
		$this->addSql('INSERT INTO user (user_id, first_name, last_name, role, email, password)
                    			VALUES (?, "Owner", "Test", 1, "owner@gmail.com", "5ea8be9d88eb68b1eb1662b014e0c99b")',
			[$uuid->toBytes()]
		);
	}

	public function down(Schema $schema): void
	{
	}
}
