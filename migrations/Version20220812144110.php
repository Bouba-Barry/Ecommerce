<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
<<<<<<<< HEAD:migrations/Version20220807143124.php
final class Version20220807143124 extends AbstractMigration
========
final class Version20220812144110 extends AbstractMigration
>>>>>>>> 82915ddfe8bede716b110da60a92c21527c85f99:migrations/Version20220812144110.php
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
<<<<<<<< HEAD:migrations/Version20220807143124.php
        $this->addSql('ALTER TABLE user ADD deletedAt DATETIME DEFAULT NULL');
========

        $this->addSql('ALTER TABLE sous_categorie ADD picture VARCHAR(255) DEFAULT NULL');
>>>>>>>> 82915ddfe8bede716b110da60a92c21527c85f99:migrations/Version20220812144110.php
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
<<<<<<<< HEAD:migrations/Version20220807143124.php
        $this->addSql('ALTER TABLE user DROP deletedAt');
========
        $this->addSql('ALTER TABLE sous_categorie DROP picture');
>>>>>>>> 82915ddfe8bede716b110da60a92c21527c85f99:migrations/Version20220812144110.php
    }
}
