<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200723174846 extends AbstractMigration
{
    /**
     * @return string
     */
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'postgresql',
            'Migration can only be executed safely on \'postgresql\'.'
        );

        $this->addSql('ALTER TABLE person_album_image DROP CONSTRAINT fk_de6e11f17e3c61f9');
        $this->addSql('ALTER TABLE person_album_image DROP CONSTRAINT FK_DE6E11F11137ABCF');
        $this->addSql('DROP INDEX idx_de6e11f17e3c61f9');
        $this->addSql('ALTER TABLE person_album_image ADD "user_id" INT DEFAULT NULL');
        $this->addSql('ALTER TABLE person_album_image DROP owner_id');
        $this->addSql(
            'ALTER TABLE person_album_image ADD CONSTRAINT FK_DE6E11F1A88CC693 FOREIGN KEY ("user_id") REFERENCES person ("id") NOT DEFERRABLE INITIALLY IMMEDIATE'
        );
        $this->addSql(
            'ALTER TABLE person_album_image ADD CONSTRAINT FK_DE6E11F11137ABCF FOREIGN KEY (album_id) REFERENCES person_album (id) NOT DEFERRABLE INITIALLY IMMEDIATE'
        );
        $this->addSql('CREATE INDEX IDX_DE6E11F1A88CC693 ON person_album_image ("user_id")');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'postgresql',
            'Migration can only be executed safely on \'postgresql\'.'
        );

        $this->addSql('ALTER TABLE person_album_image DROP CONSTRAINT FK_DE6E11F1A88CC693');
        $this->addSql('ALTER TABLE person_album_image DROP CONSTRAINT fk_de6e11f11137abcf');
        $this->addSql('DROP INDEX IDX_DE6E11F1A88CC693');
        $this->addSql('ALTER TABLE person_album_image ADD owner_id INT NOT NULL');
        $this->addSql('ALTER TABLE person_album_image DROP "user_id"');
        $this->addSql(
            'ALTER TABLE person_album_image ADD CONSTRAINT fk_de6e11f17e3c61f9 FOREIGN KEY (owner_id) REFERENCES person (id) NOT DEFERRABLE INITIALLY IMMEDIATE'
        );
        $this->addSql(
            'ALTER TABLE person_album_image ADD CONSTRAINT fk_de6e11f11137abcf FOREIGN KEY (album_id) REFERENCES person_album (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE'
        );
        $this->addSql('CREATE INDEX idx_de6e11f17e3c61f9 ON person_album_image (owner_id)');
    }
}
