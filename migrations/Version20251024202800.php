<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Adds the nullable category_id (UUID) foreign key to members -> categories(id).
 */
final class Version20251024202800 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add members.category_id (UUID) nullable with FK to categories(id) ON DELETE SET NULL';
    }

    public function up(Schema $schema): void
    {
        // MySQL platform assumed based on environment configuration
        $this->addSql(<<<'SQL'
            ALTER TABLE members
            ADD category_id BINARY(16) DEFAULT NULL COMMENT '(DC2Type:uuid)'
        SQL);

        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_MEMBERS_CATEGORY_ID ON members (category_id)
        SQL);

        $this->addSql(<<<'SQL'
            ALTER TABLE members
            ADD CONSTRAINT FK_MEMBERS_CATEGORY FOREIGN KEY (category_id)
            REFERENCES categories (id) ON DELETE SET NULL
        SQL);
    }

    public function down(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
            ALTER TABLE members DROP FOREIGN KEY FK_MEMBERS_CATEGORY
        SQL);

        $this->addSql(<<<'SQL'
            DROP INDEX IDX_MEMBERS_CATEGORY_ID ON members
        SQL);

        $this->addSql(<<<'SQL'
            ALTER TABLE members DROP category_id
        SQL);
    }
}
