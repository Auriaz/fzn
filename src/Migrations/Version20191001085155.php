<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191001085155 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE article ADD is_delete TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE article_reference DROP FOREIGN KEY FK_74961937C1D6EFD5');
        $this->addSql('DROP INDEX UNIQ_74961937C1D6EFD5 ON article_reference');
        $this->addSql('ALTER TABLE article_reference ADD article_id INT NOT NULL, ADD filename VARCHAR(255) NOT NULL, ADD original_filename VARCHAR(255) NOT NULL, ADD mime_type VARCHAR(255) NOT NULL, DROP file_manager_id');
        $this->addSql('ALTER TABLE article_reference ADD CONSTRAINT FK_749619377294869C FOREIGN KEY (article_id) REFERENCES article (id)');
        $this->addSql('CREATE INDEX IDX_749619377294869C ON article_reference (article_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE article DROP is_delete');
        $this->addSql('ALTER TABLE article_reference DROP FOREIGN KEY FK_749619377294869C');
        $this->addSql('DROP INDEX IDX_749619377294869C ON article_reference');
        $this->addSql('ALTER TABLE article_reference ADD file_manager_id INT DEFAULT NULL, DROP article_id, DROP filename, DROP original_filename, DROP mime_type');
        $this->addSql('ALTER TABLE article_reference ADD CONSTRAINT FK_74961937C1D6EFD5 FOREIGN KEY (file_manager_id) REFERENCES file_manager (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_74961937C1D6EFD5 ON article_reference (file_manager_id)');
    }
}
