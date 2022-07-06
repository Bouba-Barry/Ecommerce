<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220706214331 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE attribut (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categorie (id INT AUTO_INCREMENT NOT NULL, titre VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commande (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, date_cmd DATETIME NOT NULL, adresse_livraison VARCHAR(255) DEFAULT NULL, method_payement VARCHAR(255) NOT NULL, INDEX IDX_6EEAA67DA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commande_produit (commande_id INT NOT NULL,qte_cmd BIGINT NOT NULL ,produit_id INT NOT NULL, INDEX IDX_DF1E9E8782EA2E54 (commande_id), INDEX IDX_DF1E9E87F347EFB (produit_id), PRIMARY KEY(commande_id, produit_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE produit (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, sous_categorie_id INT DEFAULT NULL, designation VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, ancien_prix DOUBLE PRECISION NOT NULL, nouveau_prix DOUBLE PRECISION DEFAULT NULL, image_produit VARCHAR(255) DEFAULT NULL, qte_stock BIGINT NOT NULL, INDEX IDX_29A5EC27A76ED395 (user_id), INDEX IDX_29A5EC27365BF48 (sous_categorie_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE produit_reduction (produit_id INT NOT NULL, reduction_id INT NOT NULL, INDEX IDX_35700A84F347EFB (produit_id), INDEX IDX_35700A84C03CB092 (reduction_id), PRIMARY KEY(produit_id, reduction_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE produit_variation (produit_id INT NOT NULL, variation_id INT NOT NULL, INDEX IDX_E60C6D06F347EFB (produit_id), INDEX IDX_E60C6D065182BFD8 (variation_id), PRIMARY KEY(produit_id, variation_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reduction (id INT AUTO_INCREMENT NOT NULL, designation VARCHAR(255) DEFAULT NULL, pourcentage VARCHAR(255) DEFAULT NULL, periode VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sous_categorie (id INT AUTO_INCREMENT NOT NULL, categorie_id INT DEFAULT NULL, titre VARCHAR(255) NOT NULL, INDEX IDX_52743D7BBCF5E72D (categorie_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE variation (id INT AUTO_INCREMENT NOT NULL, attribut_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, code VARCHAR(255) DEFAULT NULL, INDEX IDX_629B33EA51383AF3 (attribut_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67DA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE commande_produit ADD CONSTRAINT FK_DF1E9E8782EA2E54 FOREIGN KEY (commande_id) REFERENCES commande (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE commande_produit ADD CONSTRAINT FK_DF1E9E87F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC27A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC27365BF48 FOREIGN KEY (sous_categorie_id) REFERENCES sous_categorie (id)');
        $this->addSql('ALTER TABLE produit_reduction ADD CONSTRAINT FK_35700A84F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE produit_reduction ADD CONSTRAINT FK_35700A84C03CB092 FOREIGN KEY (reduction_id) REFERENCES reduction (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE produit_variation ADD CONSTRAINT FK_E60C6D06F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE produit_variation ADD CONSTRAINT FK_E60C6D065182BFD8 FOREIGN KEY (variation_id) REFERENCES variation (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sous_categorie ADD CONSTRAINT FK_52743D7BBCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id)');
        $this->addSql('ALTER TABLE variation ADD CONSTRAINT FK_629B33EA51383AF3 FOREIGN KEY (attribut_id) REFERENCES attribut (id)');
        $this->addSql('ALTER TABLE user CHANGE roles roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE variation DROP FOREIGN KEY FK_629B33EA51383AF3');
        $this->addSql('ALTER TABLE sous_categorie DROP FOREIGN KEY FK_52743D7BBCF5E72D');
        $this->addSql('ALTER TABLE commande_produit DROP FOREIGN KEY FK_DF1E9E8782EA2E54');
        $this->addSql('ALTER TABLE commande_produit DROP FOREIGN KEY FK_DF1E9E87F347EFB');
        $this->addSql('ALTER TABLE produit_reduction DROP FOREIGN KEY FK_35700A84F347EFB');
        $this->addSql('ALTER TABLE produit_variation DROP FOREIGN KEY FK_E60C6D06F347EFB');
        $this->addSql('ALTER TABLE produit_reduction DROP FOREIGN KEY FK_35700A84C03CB092');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC27365BF48');
        $this->addSql('ALTER TABLE produit_variation DROP FOREIGN KEY FK_E60C6D065182BFD8');
        $this->addSql('DROP TABLE attribut');
        $this->addSql('DROP TABLE categorie');
        $this->addSql('DROP TABLE commande');
        $this->addSql('DROP TABLE commande_produit');
        $this->addSql('DROP TABLE produit');
        $this->addSql('DROP TABLE produit_reduction');
        $this->addSql('DROP TABLE produit_variation');
        $this->addSql('DROP TABLE reduction');
        $this->addSql('DROP TABLE sous_categorie');
        $this->addSql('DROP TABLE variation');
        $this->addSql('ALTER TABLE `user` CHANGE roles roles LONGTEXT NOT NULL COLLATE `utf8mb4_bin`');
    }
}
