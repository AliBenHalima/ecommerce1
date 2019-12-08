<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191208175348 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E66AAA30D73');
        $this->addSql('DROP INDEX IDX_23A0E66AAA30D73 ON article');
        $this->addSql('ALTER TABLE article DROP artcile_likes_id');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649CFAE935F');
        $this->addSql('DROP INDEX IDX_8D93D649CFAE935F ON user');
        $this->addSql('ALTER TABLE user DROP user_likes_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE article ADD artcile_likes_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E66AAA30D73 FOREIGN KEY (artcile_likes_id) REFERENCES article_like (id)');
        $this->addSql('CREATE INDEX IDX_23A0E66AAA30D73 ON article (artcile_likes_id)');
        $this->addSql('ALTER TABLE user ADD user_likes_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649CFAE935F FOREIGN KEY (user_likes_id) REFERENCES article_like (id)');
        $this->addSql('CREATE INDEX IDX_8D93D649CFAE935F ON user (user_likes_id)');
    }
}
