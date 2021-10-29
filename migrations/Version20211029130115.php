<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211029130115 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE concert (id INT AUTO_INCREMENT NOT NULL, stage_id INT DEFAULT NULL, artist_id INT DEFAULT NULL, time DATETIME NOT NULL, INDEX IDX_D57C02D22298D193 (stage_id), UNIQUE INDEX UNIQ_D57C02D2B7970CF8 (artist_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE day (id INT AUTO_INCREMENT NOT NULL, event_id INT NOT NULL, date DATETIME NOT NULL, name VARCHAR(255) DEFAULT NULL, INDEX IDX_E5A0299071F7E88B (event_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE stage (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE stage_day (stage_id INT NOT NULL, day_id INT NOT NULL, INDEX IDX_E7501B2C2298D193 (stage_id), INDEX IDX_E7501B2C9C24126 (day_id), PRIMARY KEY(stage_id, day_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE concert ADD CONSTRAINT FK_D57C02D22298D193 FOREIGN KEY (stage_id) REFERENCES stage (id)');
        $this->addSql('ALTER TABLE concert ADD CONSTRAINT FK_D57C02D2B7970CF8 FOREIGN KEY (artist_id) REFERENCES artist (id)');
        $this->addSql('ALTER TABLE day ADD CONSTRAINT FK_E5A0299071F7E88B FOREIGN KEY (event_id) REFERENCES event (id)');
        $this->addSql('ALTER TABLE stage_day ADD CONSTRAINT FK_E7501B2C2298D193 FOREIGN KEY (stage_id) REFERENCES stage (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE stage_day ADD CONSTRAINT FK_E7501B2C9C24126 FOREIGN KEY (day_id) REFERENCES day (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE event ADD name VARCHAR(255) NOT NULL, DROP time, DROP stage');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE stage_day DROP FOREIGN KEY FK_E7501B2C9C24126');
        $this->addSql('ALTER TABLE concert DROP FOREIGN KEY FK_D57C02D22298D193');
        $this->addSql('ALTER TABLE stage_day DROP FOREIGN KEY FK_E7501B2C2298D193');
        $this->addSql('DROP TABLE concert');
        $this->addSql('DROP TABLE day');
        $this->addSql('DROP TABLE stage');
        $this->addSql('DROP TABLE stage_day');
        $this->addSql('ALTER TABLE event ADD time DATETIME NOT NULL, ADD stage VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, DROP name');
    }
}
