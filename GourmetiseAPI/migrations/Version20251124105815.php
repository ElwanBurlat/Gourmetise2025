<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251124105815 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE bakery (siret VARCHAR(15) NOT NULL, bakery_user_id INT NOT NULL, company_name VARCHAR(30) NOT NULL, phone VARCHAR(20) NOT NULL, adress VARCHAR(50) NOT NULL, city VARCHAR(50) NOT NULL, postalcode INT NOT NULL, country VARCHAR(50) NOT NULL, name_contact VARCHAR(55) NOT NULL, phone_contact VARCHAR(20) NOT NULL, description VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_C647FA2AF960E4E (bakery_user_id), PRIMARY KEY(siret)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE contest_params (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, status VARCHAR(255) DEFAULT \'not_opened\' NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, last_name VARCHAR(30) NOT NULL, first_name VARCHAR(30) NOT NULL, email VARCHAR(50) NOT NULL, password_hash VARCHAR(50) NOT NULL, role VARCHAR(50) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE bakery ADD CONSTRAINT FK_C647FA2AF960E4E FOREIGN KEY (bakery_user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bakery DROP FOREIGN KEY FK_C647FA2AF960E4E');
        $this->addSql('DROP TABLE bakery');
        $this->addSql('DROP TABLE contest_params');
        $this->addSql('DROP TABLE user');
    }
}
