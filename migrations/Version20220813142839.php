<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220813142839 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE quantite_variation');

        $this->addSql('ALTER TABLE quantite ADD variations LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\'');
        
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE quantite_variation (quantite_id INT NOT NULL, variation_id INT NOT NULL, INDEX IDX_59B818035182BFD8 (variation_id), INDEX IDX_59B818036444A2DB (quantite_id), PRIMARY KEY(quantite_id, variation_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE quantite_variation ADD CONSTRAINT FK_59B818035182BFD8 FOREIGN KEY (variation_id) REFERENCES variation (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE quantite_variation ADD CONSTRAINT FK_59B818036444A2DB FOREIGN KEY (quantite_id) REFERENCES quantite (id) ON DELETE CASCADE');
       
        $this->addSql('ALTER TABLE quantite DROP variations');
      
    }
}
