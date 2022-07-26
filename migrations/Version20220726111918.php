<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220726111918 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE region (id INT AUTO_INCREMENT NOT NULL, nom_region VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ville (id INT AUTO_INCREMENT NOT NULL, region_id INT NOT NULL, nom_ville VARCHAR(255) NOT NULL, INDEX IDX_43C3D9C398260155 (region_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE ville ADD CONSTRAINT FK_43C3D9C398260155 FOREIGN KEY (region_id) REFERENCES region (id)');
        $this->addSql('ALTER TABLE commande_produit add qte_cmd Number,create_at DATETIME DEFAULT TIMESTAMP,update_at DATETIME DEFAULT TIMESTAMP');
        $this->addSql('ALTER TABLE panier DROP montant');
        $this->addSql('ALTER TABLE panier_produit DROP qte_produit, DROP create_at, DROP updated_at');
        $this->addSql('ALTER TABLE produit_reduction DROP create_at, DROP updated_at');
        $this->addSql('ALTER TABLE produit_variation DROP create_at, DROP updated_at');
        $this->addSql('ALTER TABLE user CHANGE telephone telephone VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ville DROP FOREIGN KEY FK_43C3D9C398260155');
        $this->addSql('DROP TABLE region');
        $this->addSql('DROP TABLE ville');
        $this->addSql('ALTER TABLE commande_produit ADD qte_cmd BIGINT NOT NULL, ADD create_at DATETIME DEFAULT NULL, ADD update_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE panier CHANGE montant montant DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE panier_produit ADD qte_produit BIGINT NOT NULL, ADD create_at DATETIME DEFAULT CURRENT_TIMESTAMP, ADD updated_at DATETIME DEFAULT CURRENT_TIMESTAMP');
        $this->addSql('ALTER TABLE produit_reduction ADD create_at DATETIME DEFAULT NULL, ADD updated_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE produit_variation ADD create_at DATETIME DEFAULT NULL, ADD updated_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE `user` CHANGE telephone telephone VARCHAR(255) DEFAULT NULL');
    }
}
