<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191009121019 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE choice_question');
        $this->addSql('DROP TABLE question_choice');
        $this->addSql('DROP TABLE question_free_type');
        $this->addSql('DROP TABLE text_question');
        $this->addSql('ALTER TABLE session ADD coach VARCHAR(40) NOT NULL, ADD session_name VARCHAR(40) NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE choice_question (id INT AUTO_INCREMENT NOT NULL, discr VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, text VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE question_choice (id INT NOT NULL, text VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE question_free_type (id INT NOT NULL, text VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE text_question (id INT AUTO_INCREMENT NOT NULL, discr VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, text VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE question_choice ADD CONSTRAINT FK_C6F6759ABF396750 FOREIGN KEY (id) REFERENCES question (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE question_free_type ADD CONSTRAINT FK_B8BE1372BF396750 FOREIGN KEY (id) REFERENCES question (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE session DROP coach, DROP session_name');
    }
}
