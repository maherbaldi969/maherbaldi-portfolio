<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260325204458 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE contact_messages CHANGE message message LONGTEXT NOT NULL, CHANGE is_read is_read TINYINT NOT NULL, CHANGE created_at created_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE educations CHANGE display_order display_order INT NOT NULL, CHANGE is_published is_published TINYINT NOT NULL, CHANGE created_at created_at DATETIME NOT NULL, CHANGE updated_at updated_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE experiences CHANGE summary summary LONGTEXT NOT NULL, CHANGE display_order display_order INT NOT NULL, CHANGE is_featured is_featured TINYINT NOT NULL, CHANGE is_published is_published TINYINT NOT NULL, CHANGE created_at created_at DATETIME NOT NULL, CHANGE updated_at updated_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE projects ADD display_order INT NOT NULL, CHANGE slug slug VARCHAR(191) NOT NULL, CHANGE full_description full_description LONGTEXT NOT NULL, CHANGE is_published is_published TINYINT NOT NULL, CHANGE created_at created_at DATETIME NOT NULL, CHANGE updated_at updated_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE projects RENAME INDEX slug TO UNIQ_5C93B3A4989D9B62');
        $this->addSql('ALTER TABLE site_settings CHANGE updated_at updated_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE skills CHANGE display_order display_order INT NOT NULL, CHANGE is_published is_published TINYINT NOT NULL, CHANGE show_in_bars show_in_bars TINYINT NOT NULL, CHANGE show_in_tags show_in_tags TINYINT NOT NULL, CHANGE created_at created_at DATETIME NOT NULL, CHANGE updated_at updated_at DATETIME NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE contact_messages CHANGE message message TEXT NOT NULL, CHANGE is_read is_read TINYINT DEFAULT 0 NOT NULL, CHANGE created_at created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL');
        $this->addSql('ALTER TABLE educations CHANGE display_order display_order INT DEFAULT 0 NOT NULL, CHANGE is_published is_published TINYINT DEFAULT 1 NOT NULL, CHANGE created_at created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, CHANGE updated_at updated_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL');
        $this->addSql('ALTER TABLE experiences CHANGE summary summary TEXT NOT NULL, CHANGE display_order display_order INT DEFAULT 0 NOT NULL, CHANGE is_featured is_featured TINYINT DEFAULT 0 NOT NULL, CHANGE is_published is_published TINYINT DEFAULT 1 NOT NULL, CHANGE created_at created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, CHANGE updated_at updated_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL');
        $this->addSql('ALTER TABLE projects DROP display_order, CHANGE slug slug VARCHAR(191) NOT NULL, CHANGE full_description full_description TEXT NOT NULL, CHANGE is_published is_published TINYINT DEFAULT 0 NOT NULL, CHANGE created_at created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, CHANGE updated_at updated_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL');
        $this->addSql('ALTER TABLE projects RENAME INDEX uniq_5c93b3a4989d9b62 TO slug');
        $this->addSql('ALTER TABLE site_settings CHANGE updated_at updated_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL');
        $this->addSql('ALTER TABLE skills CHANGE display_order display_order INT DEFAULT 0 NOT NULL, CHANGE is_published is_published TINYINT DEFAULT 1 NOT NULL, CHANGE show_in_bars show_in_bars TINYINT DEFAULT 1 NOT NULL, CHANGE show_in_tags show_in_tags TINYINT DEFAULT 1 NOT NULL, CHANGE created_at created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, CHANGE updated_at updated_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL');
    }
}
