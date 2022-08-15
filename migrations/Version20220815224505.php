<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220815224505 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE attribut ADD deletedAt DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE categorie ADD deletedAt DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE commande ADD ville_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67DA73F0036 FOREIGN KEY (ville_id) REFERENCES ville (id)');
        $this->addSql('CREATE INDEX IDX_6EEAA67DA73F0036 ON commande (ville_id)');
        $this->addSql('ALTER TABLE image DROP qte_stock, CHANGE url url VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE produit ADD deletedAt DATETIME DEFAULT NULL, ADD type VARCHAR(255) NOT NULL, CHANGE description_detaille description_detaille LONGTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE quantite CHANGE qte_stock qte_stock BIGINT DEFAULT NULL');
        $this->addSql('ALTER TABLE reduction ADD deletedAt DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE sous_categorie ADD deletedAt DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD deletedAt DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE variation ADD deletedAt DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE attribut DROP deletedAt');
        $this->addSql('ALTER TABLE categorie DROP deletedAt');
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67DA73F0036');
        $this->addSql('DROP INDEX IDX_6EEAA67DA73F0036 ON commande');
        $this->addSql('ALTER TABLE commande DROP ville_id');
        $this->addSql('ALTER TABLE image ADD qte_stock BIGINT NOT NULL, CHANGE url url VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE produit DROP deletedAt, DROP type, CHANGE description_detaille description_detaille VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE quantite CHANGE qte_stock qte_stock BIGINT NOT NULL');
        $this->addSql('ALTER TABLE reduction DROP deletedAt');
        $this->addSql('ALTER TABLE sous_categorie DROP deletedAt');
        $this->addSql('ALTER TABLE `user` DROP deletedAt');
        $this->addSql('ALTER TABLE variation DROP deletedAt');
    }
}