<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240824144656 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE follow (id UUID NOT NULL, follower_id VARCHAR(255) NOT NULL, celeb_id VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX follower_celeb_unique_idx ON follow (follower_id, celeb_id)');
        $this->addSql('COMMENT ON COLUMN follow.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN follow.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN follow.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE article_tag ALTER article_id SET NOT NULL');
        $this->addSql('ALTER TABLE article_tag ALTER tag_id SET NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        // $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE follow');
        $this->addSql('ALTER TABLE article_tag ALTER article_id DROP NOT NULL');
        $this->addSql('ALTER TABLE article_tag ALTER tag_id DROP NOT NULL');
    }
}
