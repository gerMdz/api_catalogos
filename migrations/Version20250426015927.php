<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250426015927 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE role (id BINARY(16) NOT NULL COMMENT '(DC2Type:uuid)', nombre VARCHAR(100) NOT NULL, slug VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE usuario_panel (id BINARY(16) NOT NULL COMMENT '(DC2Type:uuid)', email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE usuario_panel_role (usuario_panel_id BINARY(16) NOT NULL COMMENT '(DC2Type:uuid)', role_id BINARY(16) NOT NULL COMMENT '(DC2Type:uuid)', INDEX IDX_25C212D87AE39BF (usuario_panel_id), INDEX IDX_25C212DD60322AC (role_id), PRIMARY KEY(usuario_panel_id, role_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE usuario_panel_role ADD CONSTRAINT FK_25C212D87AE39BF FOREIGN KEY (usuario_panel_id) REFERENCES usuario_panel (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE usuario_panel_role ADD CONSTRAINT FK_25C212DD60322AC FOREIGN KEY (role_id) REFERENCES role (id) ON DELETE CASCADE
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE usuario_panel_role DROP FOREIGN KEY FK_25C212D87AE39BF
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE usuario_panel_role DROP FOREIGN KEY FK_25C212DD60322AC
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE role
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE usuario_panel
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE usuario_panel_role
        SQL);
    }
}
