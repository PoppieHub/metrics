<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210601181810 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE date (id INT AUTO_INCREMENT NOT NULL, connection_to_url VARCHAR(255) NOT NULL, disconnection_from_url VARCHAR(255) NOT NULL, url VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE date_website (date_id INT NOT NULL, website_id INT NOT NULL, INDEX IDX_7E885A1AB897366B (date_id), INDEX IDX_7E885A1A18F45C82 (website_id), PRIMARY KEY(date_id, website_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE date_user (date_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_AD4D5FBDB897366B (date_id), INDEX IDX_AD4D5FBDA76ED395 (user_id), PRIMARY KEY(date_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, website_id INT DEFAULT NULL, ip VARCHAR(255) NOT NULL, browser VARCHAR(255) NOT NULL, version VARCHAR(255) NOT NULL, os VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, INDEX IDX_8D93D64918F45C82 (website_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE website (id INT AUTO_INCREMENT NOT NULL, url_website VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, page_view VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE date_website ADD CONSTRAINT FK_7E885A1AB897366B FOREIGN KEY (date_id) REFERENCES date (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE date_website ADD CONSTRAINT FK_7E885A1A18F45C82 FOREIGN KEY (website_id) REFERENCES website (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE date_user ADD CONSTRAINT FK_AD4D5FBDB897366B FOREIGN KEY (date_id) REFERENCES date (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE date_user ADD CONSTRAINT FK_AD4D5FBDA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64918F45C82 FOREIGN KEY (website_id) REFERENCES website (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE date_website DROP FOREIGN KEY FK_7E885A1AB897366B');
        $this->addSql('ALTER TABLE date_user DROP FOREIGN KEY FK_AD4D5FBDB897366B');
        $this->addSql('ALTER TABLE date_user DROP FOREIGN KEY FK_AD4D5FBDA76ED395');
        $this->addSql('ALTER TABLE date_website DROP FOREIGN KEY FK_7E885A1A18F45C82');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D64918F45C82');
        $this->addSql('DROP TABLE date');
        $this->addSql('DROP TABLE date_website');
        $this->addSql('DROP TABLE date_user');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE website');
    }
}
