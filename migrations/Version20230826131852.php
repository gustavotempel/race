<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230826131852 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE race (id INT NOT NULL, title TEXT NOT NULL, date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, long_distance_avg TEXT NOT NULL, medium_distance_avg TEXT NOT NULL, file_path VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE racer (id INT NOT NULL, race_id INT DEFAULT NULL, full_name TEXT NOT NULL, distance TEXT NOT NULL, time TEXT NOT NULL, age_category TEXT NOT NULL, overall_place INT DEFAULT NULL, age_category_place INT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_2ABA2E5F6E59D40D ON racer (race_id)');
        $this->addSql('ALTER TABLE racer ADD CONSTRAINT FK_2ABA2E5F6E59D40D FOREIGN KEY (race_id) REFERENCES race (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE racer DROP CONSTRAINT FK_2ABA2E5F6E59D40D');
        $this->addSql('DROP TABLE race');
        $this->addSql('DROP TABLE racer');
    }
}
