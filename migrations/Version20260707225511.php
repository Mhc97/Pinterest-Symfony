<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260707225511 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pin ADD image_name VARCHAR(255) DEFAULT NULL, ADD image_size INT DEFAULT NULL, CHANGE uptated_at updated_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE pin ADD CONSTRAINT FK_B5852DF3A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pin DROP FOREIGN KEY FK_B5852DF3A76ED395');
        $this->addSql('ALTER TABLE pin DROP image_name, DROP image_size, CHANGE updated_at uptated_at DATETIME NOT NULL');
    }
}
