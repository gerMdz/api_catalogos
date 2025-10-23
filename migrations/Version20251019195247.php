<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251019195247 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE members_enjoys (id INT AUTO_INCREMENT NOT NULL, members_id INT DEFAULT NULL, enjoys_id INT DEFAULT NULL, audi_user INT DEFAULT NULL, audi_date DATETIME DEFAULT NULL, audi_action VARCHAR(1) DEFAULT NULL, INDEX IDX_706A82BBD01F5ED (members_id), INDEX IDX_706A82BF7B05D06 (enjoys_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE members_enjoys ADD CONSTRAINT FK_706A82BBD01F5ED FOREIGN KEY (members_id) REFERENCES members (id) ON DELETE SET NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE members_enjoys ADD CONSTRAINT FK_706A82BF7B05D06 FOREIGN KEY (enjoys_id) REFERENCES enjoys (id) ON DELETE SET NULL
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE members_enjoys DROP FOREIGN KEY FK_706A82BBD01F5ED
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE members_enjoys DROP FOREIGN KEY FK_706A82BF7B05D06
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE members_enjoys
        SQL);
    }
}
