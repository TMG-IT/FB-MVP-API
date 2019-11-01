<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191010114026 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE q_intro DROP FOREIGN KEY FK_F68C8CFEBF396750');
        $this->addSql('ALTER TABLE q_intro ADD subtitle VARCHAR(255) NOT NULL, CHANGE id id INT AUTO_INCREMENT NOT NULL, CHANGE text title VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE q_intro ADD text VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, DROP title, DROP subtitle, CHANGE id id INT NOT NULL');
        $this->addSql('ALTER TABLE q_intro ADD CONSTRAINT FK_F68C8CFEBF396750 FOREIGN KEY (id) REFERENCES question (id) ON DELETE CASCADE');
    }
}
