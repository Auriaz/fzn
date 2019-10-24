<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191019190006 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE images_animals (file_manager_id INT NOT NULL, animal_id INT NOT NULL, INDEX IDX_91177BFC1D6EFD5 (file_manager_id), INDEX IDX_91177BF8E962C16 (animal_id), PRIMARY KEY(file_manager_id, animal_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE images_animals ADD CONSTRAINT FK_91177BFC1D6EFD5 FOREIGN KEY (file_manager_id) REFERENCES file_manager (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE images_animals ADD CONSTRAINT FK_91177BF8E962C16 FOREIGN KEY (animal_id) REFERENCES animal (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE file_manager_animal');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE file_manager_animal (file_manager_id INT NOT NULL, animal_id INT NOT NULL, INDEX IDX_31519C36C1D6EFD5 (file_manager_id), INDEX IDX_31519C368E962C16 (animal_id), PRIMARY KEY(file_manager_id, animal_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE file_manager_animal ADD CONSTRAINT FK_31519C368E962C16 FOREIGN KEY (animal_id) REFERENCES animal (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE file_manager_animal ADD CONSTRAINT FK_31519C36C1D6EFD5 FOREIGN KEY (file_manager_id) REFERENCES file_manager (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE images_animals');
    }
}
