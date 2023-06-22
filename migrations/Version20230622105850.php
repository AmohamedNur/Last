<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230622105850 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE dier ADD user_id INT NOT NULL');
        $this->addSql('ALTER TABLE dier ADD CONSTRAINT FK_7487C015A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_7487C015A76ED395 ON dier (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE dier DROP FOREIGN KEY FK_7487C015A76ED395');
        $this->addSql('DROP INDEX IDX_7487C015A76ED395 ON dier');
        $this->addSql('ALTER TABLE dier DROP user_id');
    }
}
