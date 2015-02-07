<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150207161220 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('CREATE TABLE Document (id INTEGER NOT NULL, name VARCHAR(255) NOT NULL, bodyText CLOB NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_211FE8205E237E06 ON Document (name)');
        $this->addSql('CREATE TABLE Note (id INTEGER NOT NULL, name VARCHAR(255) NOT NULL, bodyText CLOB NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_6F8F552A5E237E06 ON Note (name)');
        $this->addSql('CREATE TABLE WebUser (id INTEGER NOT NULL, username VARCHAR(30) NOT NULL, email_address VARCHAR(255) NOT NULL, password VARCHAR(64) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_CC3014E8F85E0677 ON WebUser (username)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_CC3014E8B08E074E ON WebUser (email_address)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP TABLE Document');
        $this->addSql('DROP TABLE Note');
        $this->addSql('DROP TABLE WebUser');
    }
}
