<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251019192804 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE api_logs (id INT AUTO_INCREMENT NOT NULL, logged_at DATETIME NOT NULL, type VARCHAR(50) NOT NULL, message LONGTEXT NOT NULL, context JSON DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE civil_state (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) DEFAULT NULL, audi_user INT DEFAULT NULL, audi_date DATETIME DEFAULT NULL, audi_action VARCHAR(1) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE countries (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) DEFAULT NULL, audi_user INT DEFAULT NULL, audi_date DATETIME DEFAULT NULL, audi_action VARCHAR(1) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE districts (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, api_districts_id VARCHAR(11) NOT NULL, api_states_id VARCHAR(255) NOT NULL, audi_user INT DEFAULT NULL, audi_date DATETIME DEFAULT NULL, audi_action VARCHAR(1) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE enjoys (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) DEFAULT NULL, audi_user INT DEFAULT NULL, audi_date DATETIME DEFAULT NULL, audi_action VARCHAR(1) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE experiences (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) DEFAULT NULL, audi_user INT DEFAULT NULL, audi_date DATETIME DEFAULT NULL, audi_action VARCHAR(1) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE family (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, description VARCHAR(255) DEFAULT NULL, audi_user INT DEFAULT NULL, audi_date DATETIME DEFAULT NULL, audi_action VARCHAR(1) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE gender (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) DEFAULT NULL, audi_user INT DEFAULT NULL, audi_date DATETIME DEFAULT NULL, audi_action VARCHAR(1) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE interests (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) DEFAULT NULL, audi_user INT DEFAULT NULL, audi_date DATETIME DEFAULT NULL, audi_action VARCHAR(1) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE life_stages (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(100) NOT NULL, audi_user INT DEFAULT NULL, audi_date DATETIME DEFAULT NULL, audi_action VARCHAR(1) DEFAULT NULL, UNIQUE INDEX UNIQ_E0349EC1989D9B62 (slug), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE localities (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, api_localities_id VARCHAR(11) NOT NULL, api_districts_id VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE members (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, lastname VARCHAR(100) NOT NULL, birthdate DATE NOT NULL, dni_document VARCHAR(20) NOT NULL, address VARCHAR(255) NOT NULL, email VARCHAR(100) DEFAULT NULL, phone VARCHAR(50) DEFAULT NULL, path_photo VARCHAR(255) DEFAULT NULL, name_profession VARCHAR(100) DEFAULT NULL, artistic_skills VARCHAR(255) DEFAULT NULL, country_id INT DEFAULT NULL, state_id INT DEFAULT NULL, district_id INT DEFAULT NULL, localities_id INT DEFAULT NULL, boss_family TINYINT(1) DEFAULT 0 NOT NULL, quantity_sons INT DEFAULT NULL, celebracion VARCHAR(20) DEFAULT NULL, name_guia VARCHAR(100) DEFAULT NULL, name_group VARCHAR(100) DEFAULT NULL, grupo VARCHAR(10) DEFAULT NULL, participate_gp VARCHAR(10) DEFAULT NULL, audi_user INT DEFAULT NULL, audi_date DATETIME DEFAULT NULL, audi_action VARCHAR(1) DEFAULT NULL, gender_id INT NOT NULL, civil_state_id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE members_experiences (id INT AUTO_INCREMENT NOT NULL, members_id INT DEFAULT NULL, experiences_id INT DEFAULT NULL, audi_user INT DEFAULT NULL, audi_date DATETIME DEFAULT NULL, audi_action VARCHAR(1) DEFAULT NULL, INDEX IDX_80C8D008BD01F5ED (members_id), INDEX IDX_80C8D008423DE140 (experiences_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE members_family (id INT AUTO_INCREMENT NOT NULL, members_id INT DEFAULT NULL, related_member_id INT DEFAULT NULL, family_id INT NOT NULL, asist_church VARCHAR(2) DEFAULT NULL, coexists VARCHAR(2) DEFAULT NULL, audi_user INT DEFAULT NULL, audi_date DATETIME DEFAULT NULL, audi_action VARCHAR(1) DEFAULT NULL, INDEX IDX_4DF80655BD01F5ED (members_id), INDEX IDX_4DF80655DD568FE5 (related_member_id), INDEX IDX_4DF80655C35E566A (family_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE members_interests (id INT AUTO_INCREMENT NOT NULL, members_id INT DEFAULT NULL, interests_id INT DEFAULT NULL, audi_user INT DEFAULT NULL, audi_date DATETIME DEFAULT NULL, audi_action VARCHAR(1) DEFAULT NULL, INDEX IDX_E2E0A70EBD01F5ED (members_id), INDEX IDX_E2E0A70E734F135E (interests_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE members_life_stages (id INT AUTO_INCREMENT NOT NULL, members_id INT DEFAULT NULL, life_stages_id INT DEFAULT NULL, audi_user INT DEFAULT NULL, audi_date DATETIME DEFAULT NULL, audi_action VARCHAR(1) DEFAULT NULL, INDEX IDX_E2FE40B9BD01F5ED (members_id), INDEX IDX_E2FE40B9385A6A86 (life_stages_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE members_needs (id INT AUTO_INCREMENT NOT NULL, members_id INT DEFAULT NULL, needs_id INT DEFAULT NULL, audi_user INT DEFAULT NULL, audi_date DATETIME DEFAULT NULL, audi_action VARCHAR(1) DEFAULT NULL, INDEX IDX_CCAA83A9BD01F5ED (members_id), INDEX IDX_CCAA83A9ADCC5296 (needs_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE members_services (id INT AUTO_INCREMENT NOT NULL, members_id INT DEFAULT NULL, services_id INT DEFAULT NULL, audi_user INT DEFAULT NULL, audi_date DATETIME DEFAULT NULL, audi_action VARCHAR(1) DEFAULT NULL, INDEX IDX_48BB9192BD01F5ED (members_id), INDEX IDX_48BB9192AEF5A6C1 (services_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE members_social_media (id INT AUTO_INCREMENT NOT NULL, members_id INT DEFAULT NULL, social_media_id INT DEFAULT NULL, other_socialmedia VARCHAR(255) DEFAULT NULL, audi_user INT DEFAULT NULL, audi_date DATETIME DEFAULT NULL, audi_action VARCHAR(1) DEFAULT NULL, INDEX IDX_7E60264EBD01F5ED (members_id), INDEX IDX_7E60264E64AE4959 (social_media_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE members_voluntary (id INT AUTO_INCREMENT NOT NULL, members_id INT DEFAULT NULL, voluntary_id INT DEFAULT NULL, service TINYINT(1) DEFAULT 0, audi_user INT DEFAULT NULL, audi_date DATETIME DEFAULT NULL, audi_action VARCHAR(1) DEFAULT NULL, INDEX IDX_EE4C89FDBD01F5ED (members_id), INDEX IDX_EE4C89FD10AA3834 (voluntary_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE needs (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) DEFAULT NULL, audi_user INT DEFAULT NULL, audi_date DATETIME DEFAULT NULL, audi_action VARCHAR(1) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE role (id BINARY(16) NOT NULL COMMENT '(DC2Type:uuid)', nombre VARCHAR(100) NOT NULL, slug VARCHAR(100) NOT NULL, UNIQUE INDEX UNIQ_57698A6A3A909126 (nombre), UNIQUE INDEX UNIQ_57698A6A989D9B62 (slug), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE services (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) DEFAULT NULL, audi_user INT DEFAULT NULL, audi_date DATETIME DEFAULT NULL, audi_action VARCHAR(1) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE social_media (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) DEFAULT NULL, audi_user INT DEFAULT NULL, audi_date DATETIME DEFAULT NULL, audi_action VARCHAR(1) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE states (id INT AUTO_INCREMENT NOT NULL, countries_id INT NOT NULL, name VARCHAR(100) NOT NULL, api_states_id VARCHAR(11) NOT NULL, audi_user INT DEFAULT NULL, audi_date DATETIME DEFAULT NULL, audi_action VARCHAR(1) DEFAULT NULL, INDEX IDX_31C2774DAEBAE514 (countries_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE usuario_panel (id BINARY(16) NOT NULL COMMENT '(DC2Type:uuid)', email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, nombre VARCHAR(510) DEFAULT NULL, audit_id INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE usuario_panel_role (usuario_panel_id BINARY(16) NOT NULL COMMENT '(DC2Type:uuid)', role_id BINARY(16) NOT NULL COMMENT '(DC2Type:uuid)', INDEX IDX_25C212D87AE39BF (usuario_panel_id), INDEX IDX_25C212DD60322AC (role_id), PRIMARY KEY(usuario_panel_id, role_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE voluntary (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) DEFAULT NULL, audi_user INT DEFAULT NULL, audi_date DATETIME DEFAULT NULL, audi_action VARCHAR(1) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', available_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', delivered_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE members_experiences ADD CONSTRAINT FK_80C8D008BD01F5ED FOREIGN KEY (members_id) REFERENCES members (id) ON DELETE SET NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE members_experiences ADD CONSTRAINT FK_80C8D008423DE140 FOREIGN KEY (experiences_id) REFERENCES experiences (id) ON DELETE SET NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE members_family ADD CONSTRAINT FK_4DF80655BD01F5ED FOREIGN KEY (members_id) REFERENCES members (id) ON DELETE SET NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE members_family ADD CONSTRAINT FK_4DF80655DD568FE5 FOREIGN KEY (related_member_id) REFERENCES members (id) ON DELETE SET NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE members_family ADD CONSTRAINT FK_4DF80655C35E566A FOREIGN KEY (family_id) REFERENCES family (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE members_interests ADD CONSTRAINT FK_E2E0A70EBD01F5ED FOREIGN KEY (members_id) REFERENCES members (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE members_interests ADD CONSTRAINT FK_E2E0A70E734F135E FOREIGN KEY (interests_id) REFERENCES interests (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE members_life_stages ADD CONSTRAINT FK_E2FE40B9BD01F5ED FOREIGN KEY (members_id) REFERENCES members (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE members_life_stages ADD CONSTRAINT FK_E2FE40B9385A6A86 FOREIGN KEY (life_stages_id) REFERENCES life_stages (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE members_needs ADD CONSTRAINT FK_CCAA83A9BD01F5ED FOREIGN KEY (members_id) REFERENCES members (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE members_needs ADD CONSTRAINT FK_CCAA83A9ADCC5296 FOREIGN KEY (needs_id) REFERENCES needs (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE members_services ADD CONSTRAINT FK_48BB9192BD01F5ED FOREIGN KEY (members_id) REFERENCES members (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE members_services ADD CONSTRAINT FK_48BB9192AEF5A6C1 FOREIGN KEY (services_id) REFERENCES services (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE members_social_media ADD CONSTRAINT FK_7E60264EBD01F5ED FOREIGN KEY (members_id) REFERENCES members (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE members_social_media ADD CONSTRAINT FK_7E60264E64AE4959 FOREIGN KEY (social_media_id) REFERENCES social_media (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE members_voluntary ADD CONSTRAINT FK_EE4C89FDBD01F5ED FOREIGN KEY (members_id) REFERENCES members (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE members_voluntary ADD CONSTRAINT FK_EE4C89FD10AA3834 FOREIGN KEY (voluntary_id) REFERENCES voluntary (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE states ADD CONSTRAINT FK_31C2774DAEBAE514 FOREIGN KEY (countries_id) REFERENCES countries (id)
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
            ALTER TABLE members_experiences DROP FOREIGN KEY FK_80C8D008BD01F5ED
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE members_experiences DROP FOREIGN KEY FK_80C8D008423DE140
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE members_family DROP FOREIGN KEY FK_4DF80655BD01F5ED
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE members_family DROP FOREIGN KEY FK_4DF80655DD568FE5
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE members_family DROP FOREIGN KEY FK_4DF80655C35E566A
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE members_interests DROP FOREIGN KEY FK_E2E0A70EBD01F5ED
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE members_interests DROP FOREIGN KEY FK_E2E0A70E734F135E
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE members_life_stages DROP FOREIGN KEY FK_E2FE40B9BD01F5ED
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE members_life_stages DROP FOREIGN KEY FK_E2FE40B9385A6A86
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE members_needs DROP FOREIGN KEY FK_CCAA83A9BD01F5ED
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE members_needs DROP FOREIGN KEY FK_CCAA83A9ADCC5296
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE members_services DROP FOREIGN KEY FK_48BB9192BD01F5ED
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE members_services DROP FOREIGN KEY FK_48BB9192AEF5A6C1
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE members_social_media DROP FOREIGN KEY FK_7E60264EBD01F5ED
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE members_social_media DROP FOREIGN KEY FK_7E60264E64AE4959
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE members_voluntary DROP FOREIGN KEY FK_EE4C89FDBD01F5ED
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE members_voluntary DROP FOREIGN KEY FK_EE4C89FD10AA3834
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE states DROP FOREIGN KEY FK_31C2774DAEBAE514
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE usuario_panel_role DROP FOREIGN KEY FK_25C212D87AE39BF
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE usuario_panel_role DROP FOREIGN KEY FK_25C212DD60322AC
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE api_logs
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE civil_state
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE countries
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE districts
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE enjoys
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE experiences
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE family
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE gender
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE interests
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE life_stages
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE localities
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE members
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE members_experiences
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE members_family
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE members_interests
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE members_life_stages
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE members_needs
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE members_services
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE members_social_media
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE members_voluntary
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE needs
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE role
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE services
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE social_media
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE states
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE usuario_panel
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE usuario_panel_role
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE voluntary
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE messenger_messages
        SQL);
    }
}
