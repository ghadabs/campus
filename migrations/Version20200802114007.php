<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200802114007 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE session CHANGE date_deb date_deb DATETIME DEFAULT NULL, CHANGE date_fin date_fin DATETIME DEFAULT NULL, CHANGE date_inscri_deb date_inscri_deb DATETIME DEFAULT NULL, CHANGE date_inscri_fin date_inscri_fin DATETIME DEFAULT NULL, CHANGE nbre_places nbre_places INT DEFAULT NULL, CHANGE adresse adresse VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE session CHANGE date_deb date_deb DATETIME NOT NULL, CHANGE date_fin date_fin DATETIME NOT NULL, CHANGE date_inscri_deb date_inscri_deb DATETIME NOT NULL, CHANGE date_inscri_fin date_inscri_fin DATETIME NOT NULL, CHANGE nbre_places nbre_places INT NOT NULL, CHANGE adresse adresse VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci');
    }
}
