<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260226110003 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE evaluation_code CHANGE bakery_id bakery_siret VARCHAR(15) NOT NULL');
        $this->addSql('ALTER TABLE evaluation_code ADD CONSTRAINT FK_3A537F209B834B34 FOREIGN KEY (bakery_siret) REFERENCES bakery (siret)');
        $this->addSql('CREATE INDEX IDX_3A537F209B834B34 ON evaluation_code (bakery_siret)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE evaluation_code DROP FOREIGN KEY FK_3A537F209B834B34');
        $this->addSql('DROP INDEX IDX_3A537F209B834B34 ON evaluation_code');
        $this->addSql('ALTER TABLE evaluation_code CHANGE bakery_siret bakery_id VARCHAR(15) NOT NULL');
    }
}
