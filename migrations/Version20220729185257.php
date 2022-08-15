<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220729185257 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE fead_back ADD produit_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE fead_back ADD CONSTRAINT FK_19160E73F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id)');
        $this->addSql('CREATE INDEX IDX_19160E73F347EFB ON fead_back (produit_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE fead_back DROP FOREIGN KEY FK_19160E73F347EFB');
        $this->addSql('DROP INDEX IDX_19160E73F347EFB ON fead_back');
        $this->addSql('ALTER TABLE fead_back DROP produit_id');
    }
}