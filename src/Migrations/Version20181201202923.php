<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181201202923 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP INDEX UNIQ_16DB4F8918C1BCA5');
        $this->addSql('DROP INDEX UNIQ_16DB4F89B0FC9251');
        $this->addSql('CREATE TEMPORARY TABLE __temp__picture AS SELECT id, exif_id, mediainfo_id FROM picture');
        $this->addSql('DROP TABLE picture');
        $this->addSql('CREATE TABLE picture (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, exif_id INTEGER DEFAULT NULL, mediainfo_id INTEGER DEFAULT NULL, filename VARCHAR(255) NOT NULL, added DATETIME NOT NULL, created DATETIME DEFAULT NULL, CONSTRAINT FK_16DB4F89B0FC9251 FOREIGN KEY (exif_id) REFERENCES exif (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_16DB4F8918C1BCA5 FOREIGN KEY (mediainfo_id) REFERENCES media_info (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO picture (id, exif_id, mediainfo_id) SELECT id, exif_id, mediainfo_id FROM __temp__picture');
        $this->addSql('DROP TABLE __temp__picture');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_16DB4F8918C1BCA5 ON picture (mediainfo_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_16DB4F89B0FC9251 ON picture (exif_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP INDEX UNIQ_16DB4F89B0FC9251');
        $this->addSql('DROP INDEX UNIQ_16DB4F8918C1BCA5');
        $this->addSql('CREATE TEMPORARY TABLE __temp__picture AS SELECT id, exif_id, mediainfo_id FROM picture');
        $this->addSql('DROP TABLE picture');
        $this->addSql('CREATE TABLE picture (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, exif_id INTEGER DEFAULT NULL, mediainfo_id INTEGER DEFAULT NULL)');
        $this->addSql('INSERT INTO picture (id, exif_id, mediainfo_id) SELECT id, exif_id, mediainfo_id FROM __temp__picture');
        $this->addSql('DROP TABLE __temp__picture');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_16DB4F89B0FC9251 ON picture (exif_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_16DB4F8918C1BCA5 ON picture (mediainfo_id)');
    }
}
