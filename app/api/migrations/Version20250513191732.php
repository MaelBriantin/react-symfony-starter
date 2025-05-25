<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250513191732 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create user table with uuid, email, roles, and password fields';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
            CREATE TABLE user (
              id BINARY(16) NOT NULL COMMENT '(DC2Type:uuid_vo)',
              email VARCHAR(180) NOT NULL COMMENT '(DC2Type:email_vo)',
              roles JSON NOT NULL,
              password VARCHAR(255) NOT NULL COMMENT '(DC2Type:password_vo)',
              UNIQUE INDEX UNIQ_8D93D649E7927C74 (email),
              PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
    }

    public function down(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
            DROP TABLE user
        SQL);
    }
}
