<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220901125408 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE lien (id INT AUTO_INCREMENT NOT NULL, slide_id INT DEFAULT NULL, image VARCHAR(1000) NOT NULL, url VARCHAR(1000) DEFAULT NULL, INDEX IDX_A532B4B5DD5AFB87 (slide_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE slide (id INT AUTO_INCREMENT NOT NULL, video VARCHAR(1000) DEFAULT NULL, etat VARCHAR(255) NOT NULL, choisi VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE lien ADD CONSTRAINT FK_A532B4B5DD5AFB87 FOREIGN KEY (slide_id) REFERENCES slide (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE lien DROP FOREIGN KEY FK_A532B4B5DD5AFB87');
        $this->addSql('DROP TABLE lien');
        $this->addSql('DROP TABLE slide');
    }
}