<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260319122500 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE evaluation DROP FOREIGN KEY FK_1323A5759B834B34');
        $this->addSql('DROP INDEX IDX_1323A5759B834B34 ON evaluation');
        $this->addSql('ALTER TABLE evaluation CHANGE bakery_siret siret VARCHAR(15) NOT NULL');
        $this->addSql('ALTER TABLE evaluation ADD CONSTRAINT FK_1323A57526E94372 FOREIGN KEY (siret) REFERENCES bakery (siret)');
        $this->addSql('CREATE INDEX IDX_1323A57526E94372 ON evaluation (siret)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE evaluation DROP FOREIGN KEY FK_1323A57526E94372');
        $this->addSql('DROP INDEX IDX_1323A57526E94372 ON evaluation');
        $this->addSql('ALTER TABLE evaluation CHANGE siret bakery_siret VARCHAR(15) NOT NULL');
        $this->addSql('ALTER TABLE evaluation ADD CONSTRAINT FK_1323A5759B834B34 FOREIGN KEY (bakery_siret) REFERENCES bakery (siret) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_1323A5759B834B34 ON evaluation (bakery_siret)');
    }
}
