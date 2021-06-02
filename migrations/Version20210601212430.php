<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210601212430 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ad ADD locality_id INT NOT NULL');
        $this->addSql('ALTER TABLE ad ADD CONSTRAINT FK_77E0ED5888823A92 FOREIGN KEY (locality_id) REFERENCES locality (id)');
        $this->addSql('CREATE INDEX IDX_77E0ED5888823A92 ON ad (locality_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ad DROP FOREIGN KEY FK_77E0ED5888823A92');
        $this->addSql('DROP INDEX IDX_77E0ED5888823A92 ON ad');
        $this->addSql('ALTER TABLE ad DROP locality_id');
    }
}
