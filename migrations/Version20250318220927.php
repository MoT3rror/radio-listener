<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250318220927 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE audio_file (id INT AUTO_INCREMENT NOT NULL, path VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, mime_type VARCHAR(255) NOT NULL, size NUMERIC(10, 0) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE radio (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, folder_name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE recording (id INT AUTO_INCREMENT NOT NULL, start_time DATETIME NOT NULL, end_time DATETIME NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, radio_id INT NOT NULL, audio_file_id INT NOT NULL, INDEX IDX_BB532B535B94ADD2 (radio_id), UNIQUE INDEX UNIQ_BB532B53AC7C70B0 (audio_file_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE recording ADD CONSTRAINT FK_BB532B535B94ADD2 FOREIGN KEY (radio_id) REFERENCES radio (id)');
        $this->addSql('ALTER TABLE recording ADD CONSTRAINT FK_BB532B53AC7C70B0 FOREIGN KEY (audio_file_id) REFERENCES audio_file (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE recording DROP FOREIGN KEY FK_BB532B535B94ADD2');
        $this->addSql('ALTER TABLE recording DROP FOREIGN KEY FK_BB532B53AC7C70B0');
        $this->addSql('DROP TABLE audio_file');
        $this->addSql('DROP TABLE radio');
        $this->addSql('DROP TABLE recording');
    }
}
