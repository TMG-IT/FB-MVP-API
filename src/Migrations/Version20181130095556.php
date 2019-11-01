<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181130095556 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE tokens ADD last_active_date DATE NOT NULL, DROP refresh_token, CHANGE user_id user_id INT NOT NULL, CHANGE expires_at expires_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE users CHANGE password_change_token password_reset_token VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE tokens ADD refresh_token VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, DROP last_active_date, CHANGE user_id user_id INT DEFAULT NULL, CHANGE expires_at expires_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE users CHANGE password_reset_token password_change_token VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci');
    }
}
