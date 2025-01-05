<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250105143905 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
{
    // Ajout de la colonne avec une valeur par défaut pour éviter les valeurs NULL
    $this->addSql('ALTER TABLE "user" ADD is_verified BOOLEAN DEFAULT false NOT NULL');

    // Si nécessaire, vous pouvez initialiser les valeurs existantes de manière conditionnelle
    // $this->addSql('UPDATE "user" SET is_verified = false WHERE is_verified IS NULL');

    // Une fois la migration terminée, vous pouvez supprimer la valeur par défaut (facultatif)
    $this->addSql('ALTER TABLE "user" ALTER COLUMN is_verified DROP DEFAULT');
}


    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE "user" DROP is_verified');
        $this->addSql('ALTER TABLE "user" ALTER role TYPE VARCHAR(255)');
    }
}
