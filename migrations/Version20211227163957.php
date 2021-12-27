<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211227163957 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE company (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, address VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE customer (id INT AUTO_INCREMENT NOT NULL, company_id INT NOT NULL, name VARCHAR(255) NOT NULL, first_name VARCHAR(255) NOT NULL, phone_number VARCHAR(255) NOT NULL, INDEX IDX_81398E09979B1AD6 (company_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE note (id INT AUTO_INCREMENT NOT NULL, customer_id INT NOT NULL, text VARCHAR(255) NOT NULL, date DATE NOT NULL, INDEX IDX_CFBDFA149395C3F3 (customer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE task (id INT AUTO_INCREMENT NOT NULL, customer_id INT NOT NULL, text VARCHAR(255) NOT NULL, date DATE NOT NULL, status VARCHAR(255) NOT NULL, INDEX IDX_527EDB259395C3F3 (customer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE customer ADD CONSTRAINT FK_81398E09979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id)');
        $this->addSql('ALTER TABLE note ADD CONSTRAINT FK_CFBDFA149395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id)');
        $this->addSql('ALTER TABLE task ADD CONSTRAINT FK_527EDB259395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE customer DROP FOREIGN KEY FK_81398E09979B1AD6');
        $this->addSql('ALTER TABLE note DROP FOREIGN KEY FK_CFBDFA149395C3F3');
        $this->addSql('ALTER TABLE task DROP FOREIGN KEY FK_527EDB259395C3F3');
        $this->addSql('DROP TABLE company');
        $this->addSql('DROP TABLE customer');
        $this->addSql('DROP TABLE note');
        $this->addSql('DROP TABLE task');
    }
}
