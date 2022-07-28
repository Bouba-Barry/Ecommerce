<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220728121234 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE produit_attribut (produit_id INT NOT NULL, attribut_id INT NOT NULL, INDEX IDX_53A7DA4EF347EFB (produit_id), INDEX IDX_53A7DA4E51383AF3 (attribut_id), PRIMARY KEY(produit_id, attribut_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE produit_attribut ADD CONSTRAINT FK_53A7DA4EF347EFB FOREIGN KEY (produit_id) REFERENCES produit (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE produit_attribut ADD CONSTRAINT FK_53A7DA4E51383AF3 FOREIGN KEY (attribut_id) REFERENCES attribut (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE produit_attribut');
    }
}