<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260527075203 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE evaluation_code DROP FOREIGN KEY FK_3A537F209B834B34');
        $this->addSql('DROP TABLE evaluation_code');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE evaluation_code (id INT AUTO_INCREMENT NOT NULL, bakery_siret VARCHAR(15) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, code VARCHAR(6) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, used TINYINT(1) NOT NULL, INDEX IDX_3A537F209B834B34 (bakery_siret), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE evaluation_code ADD CONSTRAINT FK_3A537F209B834B34 FOREIGN KEY (bakery_siret) REFERENCES bakery (siret) ON UPDATE NO ACTION ON DELETE NO ACTION');
    }
}
