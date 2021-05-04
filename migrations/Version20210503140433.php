<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210503140433 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ad ADD fuel_id INT NOT NULL, ADD gearbox_id INT NOT NULL, ADD category_id INT NOT NULL');
        $this->addSql('ALTER TABLE ad ADD CONSTRAINT FK_77E0ED5897C79677 FOREIGN KEY (fuel_id) REFERENCES fuel (id)');
        $this->addSql('ALTER TABLE ad ADD CONSTRAINT FK_77E0ED58C826082F FOREIGN KEY (gearbox_id) REFERENCES gearbox (id)');
        $this->addSql('ALTER TABLE ad ADD CONSTRAINT FK_77E0ED5812469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('CREATE INDEX IDX_77E0ED5897C79677 ON ad (fuel_id)');
        $this->addSql('CREATE INDEX IDX_77E0ED58C826082F ON ad (gearbox_id)');
        $this->addSql('CREATE INDEX IDX_77E0ED5812469DE2 ON ad (category_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ad DROP FOREIGN KEY FK_77E0ED5897C79677');
        $this->addSql('ALTER TABLE ad DROP FOREIGN KEY FK_77E0ED58C826082F');
        $this->addSql('ALTER TABLE ad DROP FOREIGN KEY FK_77E0ED5812469DE2');
        $this->addSql('DROP INDEX IDX_77E0ED5897C79677 ON ad');
        $this->addSql('DROP INDEX IDX_77E0ED58C826082F ON ad');
        $this->addSql('DROP INDEX IDX_77E0ED5812469DE2 ON ad');
        $this->addSql('ALTER TABLE ad DROP fuel_id, DROP gearbox_id, DROP category_id');
    }
}
