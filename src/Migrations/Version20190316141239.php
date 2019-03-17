<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190316141239 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('ALTER TABLE exif ADD COLUMN exposure_time VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE exif ADD COLUMN f_number VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE exif ADD COLUMN exposure_program VARCHAR(16) DEFAULT NULL');
        $this->addSql('ALTER TABLE exif ADD COLUMN isospeed_ratings VARCHAR(16) DEFAULT NULL');
        $this->addSql('ALTER TABLE exif ADD COLUMN exif_version VARCHAR(16) DEFAULT NULL');
        $this->addSql('ALTER TABLE exif ADD COLUMN date_time_original DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE exif ADD COLUMN date_time_digitized DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE exif ADD COLUMN compressed_bits_per_pixel VARCHAR(16) DEFAULT NULL');
        $this->addSql('ALTER TABLE exif ADD COLUMN exposure_bias_value VARCHAR(16) DEFAULT NULL');
        $this->addSql('ALTER TABLE exif ADD COLUMN max_aperture_value VARCHAR(16) DEFAULT NULL');
        $this->addSql('ALTER TABLE exif ADD COLUMN metering_mode VARCHAR(16) DEFAULT NULL');
        $this->addSql('ALTER TABLE exif ADD COLUMN light_source VARCHAR(16) DEFAULT NULL');
        $this->addSql('ALTER TABLE exif ADD COLUMN flash VARCHAR(16) DEFAULT NULL');
        $this->addSql('ALTER TABLE exif ADD COLUMN focal_length VARCHAR(16) DEFAULT NULL');
        $this->addSql('ALTER TABLE exif ADD COLUMN maker_note VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE exif ADD COLUMN flash_pix_version VARCHAR(16) DEFAULT NULL');
        $this->addSql('ALTER TABLE exif ADD COLUMN color_space VARCHAR(16) DEFAULT NULL');
        $this->addSql('ALTER TABLE exif ADD COLUMN exif_image_width INTEGER DEFAULT NULL');
        $this->addSql('ALTER TABLE exif ADD COLUMN exif_image_length INTEGER DEFAULT NULL');
        $this->addSql('ALTER TABLE exif ADD COLUMN custom_rendered VARCHAR(16) DEFAULT NULL');
        $this->addSql('ALTER TABLE exif ADD COLUMN exposure_mode VARCHAR(16) DEFAULT NULL');
        $this->addSql('ALTER TABLE exif ADD COLUMN white_balance VARCHAR(16) DEFAULT NULL');
        $this->addSql('ALTER TABLE exif ADD COLUMN scene_capture_type VARCHAR(16) DEFAULT NULL');
        $this->addSql('ALTER TABLE exif ADD COLUMN contrast VARCHAR(16) DEFAULT NULL');
        $this->addSql('ALTER TABLE exif ADD COLUMN saturation VARCHAR(16) DEFAULT NULL');
        $this->addSql('ALTER TABLE exif ADD COLUMN sharpness VARCHAR(16) DEFAULT NULL');
        $this->addSql('ALTER TABLE exif ADD COLUMN make VARCHAR(64) DEFAULT NULL');
        $this->addSql('ALTER TABLE exif ADD COLUMN model VARCHAR(64) DEFAULT NULL');
        $this->addSql('ALTER TABLE exif ADD COLUMN aperture_fnumber VARCHAR(16) DEFAULT NULL');
        $this->addSql('ALTER TABLE exif ADD COLUMN date_time DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE exif ADD COLUMN ycb_cr_positioning INTEGER DEFAULT NULL');
        $this->addSql('ALTER TABLE exif ADD COLUMN height INTEGER DEFAULT NULL');
        $this->addSql('ALTER TABLE exif ADD COLUMN width INTEGER DEFAULT NULL');
        $this->addSql('ALTER TABLE exif ADD COLUMN mime_type VARCHAR(16) DEFAULT NULL');
        $this->addSql('DROP INDEX IDX_17BDE61FA76ED395');
        $this->addSql('CREATE TEMPORARY TABLE __temp__upload AS SELECT id, user_id, filename, file_size, target, original_filename, type, created FROM upload');
        $this->addSql('DROP TABLE upload');
        $this->addSql('CREATE TABLE upload (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id INTEGER NOT NULL, filename VARCHAR(255) NOT NULL COLLATE BINARY, file_size BIGINT NOT NULL, target VARCHAR(255) NOT NULL COLLATE BINARY, original_filename VARCHAR(255) NOT NULL COLLATE BINARY, type VARCHAR(255) NOT NULL COLLATE BINARY, created DATETIME NOT NULL, CONSTRAINT FK_17BDE61FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO upload (id, user_id, filename, file_size, target, original_filename, type, created) SELECT id, user_id, filename, file_size, target, original_filename, type, created FROM __temp__upload');
        $this->addSql('DROP TABLE __temp__upload');
        $this->addSql('CREATE INDEX IDX_17BDE61FA76ED395 ON upload (user_id)');
        $this->addSql('DROP INDEX UNIQ_16DB4F8918C1BCA5');
        $this->addSql('DROP INDEX UNIQ_16DB4F89B0FC9251');
        $this->addSql('CREATE TEMPORARY TABLE __temp__picture AS SELECT id, exif_id, mediainfo_id, filename, added, created, original_filename, status, name, file_size, last_update, dc, dc_gif, description, img_height, img_width, kind, original_url, src, title, t_height, t_width, t_url, type FROM picture');
        $this->addSql('DROP TABLE picture');
        $this->addSql('CREATE TABLE picture (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, exif_id INTEGER DEFAULT NULL, mediainfo_id INTEGER DEFAULT NULL, filename VARCHAR(255) NOT NULL COLLATE BINARY, added DATETIME NOT NULL, created DATETIME DEFAULT NULL, original_filename VARCHAR(255) NOT NULL COLLATE BINARY, status BOOLEAN NOT NULL, name VARCHAR(255) NOT NULL COLLATE BINARY, file_size INTEGER NOT NULL, last_update DATETIME DEFAULT NULL, dc VARCHAR(255) DEFAULT NULL COLLATE BINARY, dc_gif VARCHAR(255) DEFAULT NULL COLLATE BINARY, description VARCHAR(255) DEFAULT NULL COLLATE BINARY, img_height INTEGER DEFAULT NULL, img_width INTEGER DEFAULT NULL, kind VARCHAR(255) DEFAULT NULL COLLATE BINARY, original_url VARCHAR(255) DEFAULT NULL COLLATE BINARY, src VARCHAR(255) DEFAULT NULL COLLATE BINARY, title VARCHAR(255) DEFAULT NULL COLLATE BINARY, t_height VARCHAR(255) DEFAULT NULL COLLATE BINARY, t_width VARCHAR(255) DEFAULT NULL COLLATE BINARY, t_url VARCHAR(255) DEFAULT NULL COLLATE BINARY, type VARCHAR(255) NOT NULL COLLATE BINARY, CONSTRAINT FK_16DB4F89B0FC9251 FOREIGN KEY (exif_id) REFERENCES exif (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_16DB4F8918C1BCA5 FOREIGN KEY (mediainfo_id) REFERENCES media_info (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO picture (id, exif_id, mediainfo_id, filename, added, created, original_filename, status, name, file_size, last_update, dc, dc_gif, description, img_height, img_width, kind, original_url, src, title, t_height, t_width, t_url, type) SELECT id, exif_id, mediainfo_id, filename, added, created, original_filename, status, name, file_size, last_update, dc, dc_gif, description, img_height, img_width, kind, original_url, src, title, t_height, t_width, t_url, type FROM __temp__picture');
        $this->addSql('DROP TABLE __temp__picture');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_16DB4F8918C1BCA5 ON picture (mediainfo_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_16DB4F89B0FC9251 ON picture (exif_id)');
        $this->addSql('DROP INDEX IDX_336D34B0EE45BDBF');
        $this->addSql('DROP INDEX IDX_336D34B0BAD26311');
        $this->addSql('CREATE TEMPORARY TABLE __temp__picture_tag AS SELECT picture_id, tag_id FROM picture_tag');
        $this->addSql('DROP TABLE picture_tag');
        $this->addSql('CREATE TABLE picture_tag (picture_id INTEGER NOT NULL, tag_id INTEGER NOT NULL, PRIMARY KEY(picture_id, tag_id), CONSTRAINT FK_336D34B0EE45BDBF FOREIGN KEY (picture_id) REFERENCES picture (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_336D34B0BAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO picture_tag (picture_id, tag_id) SELECT picture_id, tag_id FROM __temp__picture_tag');
        $this->addSql('DROP TABLE __temp__picture_tag');
        $this->addSql('CREATE INDEX IDX_336D34B0EE45BDBF ON picture_tag (picture_id)');
        $this->addSql('CREATE INDEX IDX_336D34B0BAD26311 ON picture_tag (tag_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('CREATE TEMPORARY TABLE __temp__exif AS SELECT id FROM exif');
        $this->addSql('DROP TABLE exif');
        $this->addSql('CREATE TABLE exif (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL)');
        $this->addSql('INSERT INTO exif (id) SELECT id FROM __temp__exif');
        $this->addSql('DROP TABLE __temp__exif');
        $this->addSql('DROP INDEX UNIQ_16DB4F89B0FC9251');
        $this->addSql('DROP INDEX UNIQ_16DB4F8918C1BCA5');
        $this->addSql('CREATE TEMPORARY TABLE __temp__picture AS SELECT id, exif_id, mediainfo_id, filename, added, created, original_filename, status, name, file_size, last_update, dc, dc_gif, description, img_height, img_width, kind, original_url, src, title, t_height, t_width, t_url, type FROM picture');
        $this->addSql('DROP TABLE picture');
        $this->addSql('CREATE TABLE picture (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, exif_id INTEGER DEFAULT NULL, mediainfo_id INTEGER DEFAULT NULL, filename VARCHAR(255) NOT NULL, added DATETIME NOT NULL, created DATETIME DEFAULT NULL, original_filename VARCHAR(255) NOT NULL, status BOOLEAN NOT NULL, name VARCHAR(255) NOT NULL, file_size INTEGER NOT NULL, last_update DATETIME DEFAULT NULL, dc VARCHAR(255) DEFAULT NULL, dc_gif VARCHAR(255) DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, img_height INTEGER DEFAULT NULL, img_width INTEGER DEFAULT NULL, kind VARCHAR(255) DEFAULT NULL, original_url VARCHAR(255) DEFAULT NULL, src VARCHAR(255) DEFAULT NULL, title VARCHAR(255) DEFAULT NULL, t_height VARCHAR(255) DEFAULT NULL, t_width VARCHAR(255) DEFAULT NULL, t_url VARCHAR(255) DEFAULT NULL, type VARCHAR(255) NOT NULL)');
        $this->addSql('INSERT INTO picture (id, exif_id, mediainfo_id, filename, added, created, original_filename, status, name, file_size, last_update, dc, dc_gif, description, img_height, img_width, kind, original_url, src, title, t_height, t_width, t_url, type) SELECT id, exif_id, mediainfo_id, filename, added, created, original_filename, status, name, file_size, last_update, dc, dc_gif, description, img_height, img_width, kind, original_url, src, title, t_height, t_width, t_url, type FROM __temp__picture');
        $this->addSql('DROP TABLE __temp__picture');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_16DB4F89B0FC9251 ON picture (exif_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_16DB4F8918C1BCA5 ON picture (mediainfo_id)');
        $this->addSql('DROP INDEX IDX_336D34B0EE45BDBF');
        $this->addSql('DROP INDEX IDX_336D34B0BAD26311');
        $this->addSql('CREATE TEMPORARY TABLE __temp__picture_tag AS SELECT picture_id, tag_id FROM picture_tag');
        $this->addSql('DROP TABLE picture_tag');
        $this->addSql('CREATE TABLE picture_tag (picture_id INTEGER NOT NULL, tag_id INTEGER NOT NULL, PRIMARY KEY(picture_id, tag_id))');
        $this->addSql('INSERT INTO picture_tag (picture_id, tag_id) SELECT picture_id, tag_id FROM __temp__picture_tag');
        $this->addSql('DROP TABLE __temp__picture_tag');
        $this->addSql('CREATE INDEX IDX_336D34B0EE45BDBF ON picture_tag (picture_id)');
        $this->addSql('CREATE INDEX IDX_336D34B0BAD26311 ON picture_tag (tag_id)');
        $this->addSql('DROP INDEX IDX_17BDE61FA76ED395');
        $this->addSql('CREATE TEMPORARY TABLE __temp__upload AS SELECT id, user_id, filename, file_size, target, original_filename, type, created FROM upload');
        $this->addSql('DROP TABLE upload');
        $this->addSql('CREATE TABLE upload (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id INTEGER NOT NULL, filename VARCHAR(255) NOT NULL, file_size BIGINT NOT NULL, target VARCHAR(255) NOT NULL, original_filename VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, created DATETIME NOT NULL)');
        $this->addSql('INSERT INTO upload (id, user_id, filename, file_size, target, original_filename, type, created) SELECT id, user_id, filename, file_size, target, original_filename, type, created FROM __temp__upload');
        $this->addSql('DROP TABLE __temp__upload');
        $this->addSql('CREATE INDEX IDX_17BDE61FA76ED395 ON upload (user_id)');
    }
}
