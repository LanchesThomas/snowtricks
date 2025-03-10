<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250119133949 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE media DROP name');
        $this->addSql('ALTER TABLE "user" ALTER created_at DROP DEFAULT');
        $this->addSql('ALTER TABLE "user" ALTER role SET NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE media ADD name VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE "user" ALTER role DROP NOT NULL');
        $this->addSql('ALTER TABLE "user" ALTER created_at SET DEFAULT \'now()\'');
    }
}
