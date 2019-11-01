<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191004090526 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE books DROP FOREIGN KEY FK_4A1B2A92F675F31B');
        $this->addSql('CREATE TABLE question (id INT AUTO_INCREMENT NOT NULL, session_id INT NOT NULL, text VARCHAR(255) NOT NULL, INDEX IDX_B6F7494EA4392681 (session_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE session (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(6) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE answer (id INT AUTO_INCREMENT NOT NULL, question_id INT NOT NULL, answer_placeholder_id INT DEFAULT NULL, text VARCHAR(255) DEFAULT NULL, INDEX IDX_DADD4A254FAF8F53 (question_id), INDEX IDX_DADD4A258B751712 (answer_placeholder_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE answer_placeholder (id INT AUTO_INCREMENT NOT NULL, question_id INT NOT NULL, text VARCHAR(255) DEFAULT NULL, INDEX IDX_34E80F214FAF8F53 (question_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE question ADD CONSTRAINT FK_B6F7494EA4392681 FOREIGN KEY (session_id) REFERENCES session (id)');
        $this->addSql('ALTER TABLE answer ADD CONSTRAINT FK_DADD4A254FAF8F53 FOREIGN KEY (question_id) REFERENCES question (id)');
        $this->addSql('ALTER TABLE answer ADD CONSTRAINT FK_DADD4A258B751712 FOREIGN KEY (answer_placeholder_id) REFERENCES answer_placeholder (id)');
        $this->addSql('ALTER TABLE answer_placeholder ADD CONSTRAINT FK_34E80F214FAF8F53 FOREIGN KEY (question_id) REFERENCES question (id)');
        $this->addSql('DROP TABLE authors');
        $this->addSql('DROP TABLE books');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE answer DROP FOREIGN KEY FK_DADD4A254FAF8F53');
        $this->addSql('ALTER TABLE answer_placeholder DROP FOREIGN KEY FK_34E80F214FAF8F53');
        $this->addSql('ALTER TABLE question DROP FOREIGN KEY FK_B6F7494EA4392681');
        $this->addSql('ALTER TABLE answer DROP FOREIGN KEY FK_DADD4A258B751712');
        $this->addSql('CREATE TABLE authors (id INT AUTO_INCREMENT NOT NULL, first_name VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, last_name VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, birthday DATE DEFAULT NULL, biography LONGTEXT DEFAULT NULL COLLATE utf8mb4_unicode_ci, gender VARCHAR(10) NOT NULL COLLATE utf8mb4_unicode_ci, place_of_birth VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE books (id INT AUTO_INCREMENT NOT NULL, author_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, release_date DATE NOT NULL, updated_at DATETIME NOT NULL, description LONGTEXT NOT NULL COLLATE utf8mb4_unicode_ci, isbn VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, format VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, number_of_pages INT NOT NULL, INDEX IDX_4A1B2A92F675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE books ADD CONSTRAINT FK_4A1B2A92F675F31B FOREIGN KEY (author_id) REFERENCES authors (id)');
        $this->addSql('DROP TABLE question');
        $this->addSql('DROP TABLE session');
        $this->addSql('DROP TABLE answer');
        $this->addSql('DROP TABLE answer_placeholder');
    }
}
