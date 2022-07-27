<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220727120342 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commande_produit DROP qte_cmd, DROP create_at, DROP update_at');
        $this->addSql('ALTER TABLE panier_produit DROP qte_produit, DROP create_at, DROP update_at');
        $this->addSql('ALTER TABLE produit_reduction DROP create_at, DROP updated_at');
        $this->addSql('ALTER TABLE produit_variation DROP create_at, DROP updated_at');
        $this->addSql('ALTER TABLE reduction ADD date_debut DATETIME NOT NULL, ADD date_fin DATETIME NOT NULL, ADD etat VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commande_produit ADD qte_cmd BIGINT NOT NULL, ADD create_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, ADD update_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL');
        $this->addSql('ALTER TABLE panier_produit ADD qte_produit BIGINT NOT NULL, ADD create_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, ADD update_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL');
        $this->addSql('ALTER TABLE produit_reduction ADD create_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, ADD updated_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL');
        $this->addSql('ALTER TABLE produit_variation ADD create_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, ADD updated_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL');
        $this->addSql('ALTER TABLE reduction DROP date_debut, DROP date_fin, DROP etat');
    }
}
