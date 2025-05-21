<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250521000111 extends AbstractMigration
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
            DROP TABLE members_enjoys
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE migrations
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE civil_state CHANGE id id INT AUTO_INCREMENT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE countries CHANGE id id INT AUTO_INCREMENT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE districts CHANGE id id INT AUTO_INCREMENT NOT NULL, CHANGE name name VARCHAR(100) NOT NULL, CHANGE api_districts_id api_districts_id VARCHAR(11) NOT NULL, CHANGE api_states_id api_states_id VARCHAR(255) NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE enjoys CHANGE id id INT AUTO_INCREMENT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE experiences CHANGE id id INT AUTO_INCREMENT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE family CHANGE id id INT AUTO_INCREMENT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE gender CHANGE id id INT AUTO_INCREMENT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE interests CHANGE id id INT AUTO_INCREMENT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE life_stages CHANGE id id INT AUTO_INCREMENT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE life_stages RENAME INDEX slug TO UNIQ_E0349EC1989D9B62
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE localities DROP audi_user, DROP audi_date, DROP audi_action, CHANGE id id INT AUTO_INCREMENT NOT NULL, CHANGE name name VARCHAR(100) NOT NULL, CHANGE api_localities_id api_localities_id VARCHAR(11) NOT NULL, CHANGE api_districts_id api_districts_id VARCHAR(255) NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE members CHANGE id id INT AUTO_INCREMENT NOT NULL, CHANGE birthdate birthdate DATE NOT NULL, CHANGE gender_id gender_id INT NOT NULL, CHANGE civil_state_id civil_state_id INT NOT NULL, CHANGE country_id country_id INT DEFAULT NULL, CHANGE state_id state_id INT DEFAULT NULL, CHANGE district_id district_id INT DEFAULT NULL, CHANGE localities_id localities_id INT DEFAULT NULL, CHANGE celebracion celebracion VARCHAR(20) DEFAULT NULL, CHANGE grupo grupo VARCHAR(10) DEFAULT NULL, CHANGE participate_gp participate_gp VARCHAR(10) DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE members_experiences CHANGE id id INT AUTO_INCREMENT NOT NULL, CHANGE members_id members_id INT DEFAULT NULL, CHANGE experiences_id experiences_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE members_experiences ADD CONSTRAINT FK_80C8D008BD01F5ED FOREIGN KEY (members_id) REFERENCES members (id) ON DELETE SET NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE members_experiences ADD CONSTRAINT FK_80C8D008423DE140 FOREIGN KEY (experiences_id) REFERENCES experiences (id) ON DELETE SET NULL
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_80C8D008BD01F5ED ON members_experiences (members_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_80C8D008423DE140 ON members_experiences (experiences_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE members_family CHANGE id id INT AUTO_INCREMENT NOT NULL, CHANGE members_id members_id INT DEFAULT NULL, CHANGE related_member_id related_member_id INT DEFAULT NULL, CHANGE family_id family_id INT NOT NULL
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
            CREATE INDEX IDX_4DF80655BD01F5ED ON members_family (members_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_4DF80655DD568FE5 ON members_family (related_member_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_4DF80655C35E566A ON members_family (family_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE members_interests CHANGE id id INT AUTO_INCREMENT NOT NULL, CHANGE members_id members_id INT DEFAULT NULL, CHANGE interests_id interests_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE members_interests ADD CONSTRAINT FK_E2E0A70EBD01F5ED FOREIGN KEY (members_id) REFERENCES members (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE members_interests ADD CONSTRAINT FK_E2E0A70E734F135E FOREIGN KEY (interests_id) REFERENCES interests (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_E2E0A70EBD01F5ED ON members_interests (members_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_E2E0A70E734F135E ON members_interests (interests_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE members_life_stages CHANGE id id INT AUTO_INCREMENT NOT NULL, CHANGE members_id members_id INT DEFAULT NULL, CHANGE life_stages_id life_stages_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE members_life_stages ADD CONSTRAINT FK_E2FE40B9BD01F5ED FOREIGN KEY (members_id) REFERENCES members (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE members_life_stages ADD CONSTRAINT FK_E2FE40B9385A6A86 FOREIGN KEY (life_stages_id) REFERENCES life_stages (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_E2FE40B9BD01F5ED ON members_life_stages (members_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_E2FE40B9385A6A86 ON members_life_stages (life_stages_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE members_needs CHANGE id id INT AUTO_INCREMENT NOT NULL, CHANGE members_id members_id INT DEFAULT NULL, CHANGE needs_id needs_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE members_needs ADD CONSTRAINT FK_CCAA83A9BD01F5ED FOREIGN KEY (members_id) REFERENCES members (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE members_needs ADD CONSTRAINT FK_CCAA83A9ADCC5296 FOREIGN KEY (needs_id) REFERENCES needs (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_CCAA83A9BD01F5ED ON members_needs (members_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_CCAA83A9ADCC5296 ON members_needs (needs_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE members_services CHANGE id id INT AUTO_INCREMENT NOT NULL, CHANGE members_id members_id INT DEFAULT NULL, CHANGE services_id services_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE members_services ADD CONSTRAINT FK_48BB9192BD01F5ED FOREIGN KEY (members_id) REFERENCES members (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE members_services ADD CONSTRAINT FK_48BB9192AEF5A6C1 FOREIGN KEY (services_id) REFERENCES services (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_48BB9192BD01F5ED ON members_services (members_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_48BB9192AEF5A6C1 ON members_services (services_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE members_social_media CHANGE id id INT AUTO_INCREMENT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE members_social_media ADD CONSTRAINT FK_7E60264EBD01F5ED FOREIGN KEY (members_id) REFERENCES members (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE members_social_media ADD CONSTRAINT FK_7E60264E64AE4959 FOREIGN KEY (social_media_id) REFERENCES social_media (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_7E60264EBD01F5ED ON members_social_media (members_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_7E60264E64AE4959 ON members_social_media (social_media_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE members_voluntary CHANGE id id INT AUTO_INCREMENT NOT NULL, CHANGE members_id members_id INT DEFAULT NULL, CHANGE voluntary_id voluntary_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE members_voluntary ADD CONSTRAINT FK_EE4C89FDBD01F5ED FOREIGN KEY (members_id) REFERENCES members (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE members_voluntary ADD CONSTRAINT FK_EE4C89FD10AA3834 FOREIGN KEY (voluntary_id) REFERENCES voluntary (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_EE4C89FDBD01F5ED ON members_voluntary (members_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_EE4C89FD10AA3834 ON members_voluntary (voluntary_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE needs CHANGE id id INT AUTO_INCREMENT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE services CHANGE id id INT AUTO_INCREMENT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE social_media CHANGE id id INT AUTO_INCREMENT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE states CHANGE id id INT AUTO_INCREMENT NOT NULL, CHANGE name name VARCHAR(100) NOT NULL, CHANGE api_states_id api_states_id VARCHAR(11) NOT NULL, CHANGE countries_id countries_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE states ADD CONSTRAINT FK_31C2774DAEBAE514 FOREIGN KEY (countries_id) REFERENCES countries (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_31C2774DAEBAE514 ON states (countries_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE voluntary CHANGE id id INT AUTO_INCREMENT NOT NULL
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE members_enjoys (id INT UNSIGNED AUTO_INCREMENT NOT NULL, members_id INT UNSIGNED DEFAULT NULL, enjoys_id INT UNSIGNED DEFAULT NULL, audi_user INT DEFAULT NULL, audi_date DATETIME DEFAULT NULL, audi_action VARCHAR(1) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_general_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = '' 
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE migrations (id BIGINT UNSIGNED AUTO_INCREMENT NOT NULL, version VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, class VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, `group` VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, namespace VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, time INT NOT NULL, batch INT UNSIGNED NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = '' 
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE api_logs
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE civil_state CHANGE id id INT UNSIGNED AUTO_INCREMENT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE countries CHANGE id id INT UNSIGNED AUTO_INCREMENT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE districts CHANGE id id INT UNSIGNED AUTO_INCREMENT NOT NULL, CHANGE name name VARCHAR(100) DEFAULT NULL, CHANGE api_districts_id api_districts_id VARCHAR(11) DEFAULT NULL, CHANGE api_states_id api_states_id VARCHAR(11) DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE enjoys CHANGE id id INT UNSIGNED AUTO_INCREMENT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE experiences CHANGE id id INT UNSIGNED AUTO_INCREMENT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE family CHANGE id id INT UNSIGNED AUTO_INCREMENT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE gender CHANGE id id INT UNSIGNED AUTO_INCREMENT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE interests CHANGE id id INT UNSIGNED AUTO_INCREMENT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE life_stages CHANGE id id INT UNSIGNED AUTO_INCREMENT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE life_stages RENAME INDEX uniq_e0349ec1989d9b62 TO slug
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE localities ADD audi_user INT DEFAULT NULL, ADD audi_date DATETIME DEFAULT NULL, ADD audi_action VARCHAR(1) DEFAULT NULL, CHANGE id id INT UNSIGNED AUTO_INCREMENT NOT NULL, CHANGE name name VARCHAR(100) DEFAULT NULL, CHANGE api_localities_id api_localities_id VARCHAR(11) DEFAULT NULL, CHANGE api_districts_id api_districts_id VARCHAR(11) DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE members CHANGE id id INT UNSIGNED AUTO_INCREMENT NOT NULL, CHANGE birthdate birthdate DATE DEFAULT NULL, CHANGE country_id country_id INT UNSIGNED DEFAULT NULL, CHANGE state_id state_id INT UNSIGNED DEFAULT NULL, CHANGE district_id district_id INT UNSIGNED DEFAULT NULL, CHANGE localities_id localities_id INT UNSIGNED DEFAULT NULL, CHANGE celebracion celebracion VARCHAR(100) DEFAULT NULL, CHANGE grupo grupo VARCHAR(2) DEFAULT NULL, CHANGE participate_gp participate_gp VARCHAR(2) DEFAULT NULL, CHANGE gender_id gender_id INT UNSIGNED NOT NULL, CHANGE civil_state_id civil_state_id INT UNSIGNED NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE members_experiences DROP FOREIGN KEY FK_80C8D008BD01F5ED
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE members_experiences DROP FOREIGN KEY FK_80C8D008423DE140
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_80C8D008BD01F5ED ON members_experiences
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_80C8D008423DE140 ON members_experiences
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE members_experiences CHANGE id id INT UNSIGNED AUTO_INCREMENT NOT NULL, CHANGE members_id members_id INT UNSIGNED DEFAULT NULL, CHANGE experiences_id experiences_id INT UNSIGNED DEFAULT NULL
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
            DROP INDEX IDX_4DF80655BD01F5ED ON members_family
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_4DF80655DD568FE5 ON members_family
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_4DF80655C35E566A ON members_family
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE members_family CHANGE id id INT UNSIGNED AUTO_INCREMENT NOT NULL, CHANGE members_id members_id INT UNSIGNED NOT NULL, CHANGE related_member_id related_member_id INT UNSIGNED NOT NULL, CHANGE family_id family_id INT UNSIGNED NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE members_interests DROP FOREIGN KEY FK_E2E0A70EBD01F5ED
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE members_interests DROP FOREIGN KEY FK_E2E0A70E734F135E
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_E2E0A70EBD01F5ED ON members_interests
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_E2E0A70E734F135E ON members_interests
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE members_interests CHANGE id id INT UNSIGNED AUTO_INCREMENT NOT NULL, CHANGE members_id members_id INT UNSIGNED DEFAULT NULL, CHANGE interests_id interests_id INT UNSIGNED DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE members_life_stages DROP FOREIGN KEY FK_E2FE40B9BD01F5ED
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE members_life_stages DROP FOREIGN KEY FK_E2FE40B9385A6A86
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_E2FE40B9BD01F5ED ON members_life_stages
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_E2FE40B9385A6A86 ON members_life_stages
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE members_life_stages CHANGE id id INT UNSIGNED AUTO_INCREMENT NOT NULL, CHANGE members_id members_id INT UNSIGNED DEFAULT NULL, CHANGE life_stages_id life_stages_id INT UNSIGNED DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE members_needs DROP FOREIGN KEY FK_CCAA83A9BD01F5ED
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE members_needs DROP FOREIGN KEY FK_CCAA83A9ADCC5296
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_CCAA83A9BD01F5ED ON members_needs
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_CCAA83A9ADCC5296 ON members_needs
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE members_needs CHANGE id id INT UNSIGNED AUTO_INCREMENT NOT NULL, CHANGE members_id members_id INT UNSIGNED DEFAULT NULL, CHANGE needs_id needs_id INT UNSIGNED DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE members_services DROP FOREIGN KEY FK_48BB9192BD01F5ED
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE members_services DROP FOREIGN KEY FK_48BB9192AEF5A6C1
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_48BB9192BD01F5ED ON members_services
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_48BB9192AEF5A6C1 ON members_services
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE members_services CHANGE id id INT UNSIGNED AUTO_INCREMENT NOT NULL, CHANGE members_id members_id INT UNSIGNED DEFAULT NULL, CHANGE services_id services_id INT UNSIGNED DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE members_social_media DROP FOREIGN KEY FK_7E60264EBD01F5ED
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE members_social_media DROP FOREIGN KEY FK_7E60264E64AE4959
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_7E60264EBD01F5ED ON members_social_media
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_7E60264E64AE4959 ON members_social_media
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE members_social_media CHANGE id id INT UNSIGNED AUTO_INCREMENT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE members_voluntary DROP FOREIGN KEY FK_EE4C89FDBD01F5ED
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE members_voluntary DROP FOREIGN KEY FK_EE4C89FD10AA3834
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_EE4C89FDBD01F5ED ON members_voluntary
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_EE4C89FD10AA3834 ON members_voluntary
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE members_voluntary CHANGE id id INT UNSIGNED AUTO_INCREMENT NOT NULL, CHANGE members_id members_id INT UNSIGNED DEFAULT NULL, CHANGE voluntary_id voluntary_id INT UNSIGNED DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE needs CHANGE id id INT UNSIGNED AUTO_INCREMENT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE services CHANGE id id INT UNSIGNED AUTO_INCREMENT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE social_media CHANGE id id INT UNSIGNED AUTO_INCREMENT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE states DROP FOREIGN KEY FK_31C2774DAEBAE514
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_31C2774DAEBAE514 ON states
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE states CHANGE id id INT UNSIGNED AUTO_INCREMENT NOT NULL, CHANGE countries_id countries_id INT DEFAULT NULL, CHANGE name name VARCHAR(100) DEFAULT NULL, CHANGE api_states_id api_states_id VARCHAR(11) DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE voluntary CHANGE id id INT UNSIGNED AUTO_INCREMENT NOT NULL
        SQL);
    }
}
