<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181116100624 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE users (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL COMMENT \'(DC2Type:json_array)\', password VARCHAR(255) NOT NULL, salt VARCHAR(255) DEFAULT \'\', first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, gender VARCHAR(255) DEFAULT NULL, active TINYINT(1) DEFAULT \'1\' NOT NULL, login_token VARCHAR(255) DEFAULT NULL, password_change_token VARCHAR(255) DEFAULT NULL, email_confirmed TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, UNIQUE INDEX email_UNIQUE (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tokens (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, token_key VARCHAR(255) NOT NULL, refresh_token VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_AA5A118EA76ED395 (user_id), UNIQUE INDEX tokenkey_UNIQUE (token_key), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE tokens ADD CONSTRAINT FK_AA5A118EA76ED395 FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE tokens DROP FOREIGN KEY FK_AA5A118EA76ED395');
        $this->addSql('DROP TABLE users');
        $this->addSql('DROP TABLE tokens');
    }
}
