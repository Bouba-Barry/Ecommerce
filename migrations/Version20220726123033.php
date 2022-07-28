<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220726123033 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commande_produit add qte_cmd Number,create_at DATETIME,update_at DATETIME');
        $this->addSql('ALTER TABLE  panier_produit add qte_produit Number, add create_at DATETIME, add updated_at DATETIME');
        $this->addSql('ALTER TABLE produit_reduction add create_at DATETIME , add updated_at DATETIME ');
        $this->addSql('ALTER TABLE produit_variation add create_at DATETIME , add updated_at DATETIME');

    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `user` DROP FOREIGN KEY FK_8D93D649A73F0036');
        $this->addSql('DROP INDEX IDX_8D93D649A73F0036 ON `user`');
        $this->addSql('ALTER TABLE `user` DROP ville_id');
    }
}
