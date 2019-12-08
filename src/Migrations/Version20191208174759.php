<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191208174759 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE article_article_like');
        $this->addSql('DROP TABLE user_user');
        $this->addSql('ALTER TABLE article ADD artcile_likes_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E66AAA30D73 FOREIGN KEY (artcile_likes_id) REFERENCES article_like (id)');
        $this->addSql('CREATE INDEX IDX_23A0E66AAA30D73 ON article (artcile_likes_id)');
        $this->addSql('ALTER TABLE user ADD user_likes_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649CFAE935F FOREIGN KEY (user_likes_id) REFERENCES article_like (id)');
        $this->addSql('CREATE INDEX IDX_8D93D649CFAE935F ON user (user_likes_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE article_article_like (article_id INT NOT NULL, article_like_id INT NOT NULL, INDEX IDX_CF6DB0237294869C (article_id), INDEX IDX_CF6DB0236849BE85 (article_like_id), PRIMARY KEY(article_id, article_like_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE user_user (user_source INT NOT NULL, user_target INT NOT NULL, INDEX IDX_F7129A803AD8644E (user_source), INDEX IDX_F7129A80233D34C1 (user_target), PRIMARY KEY(user_source, user_target)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE article_article_like ADD CONSTRAINT FK_CF6DB0236849BE85 FOREIGN KEY (article_like_id) REFERENCES article_like (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE article_article_like ADD CONSTRAINT FK_CF6DB0237294869C FOREIGN KEY (article_id) REFERENCES article (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_user ADD CONSTRAINT FK_F7129A80233D34C1 FOREIGN KEY (user_target) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_user ADD CONSTRAINT FK_F7129A803AD8644E FOREIGN KEY (user_source) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E66AAA30D73');
        $this->addSql('DROP INDEX IDX_23A0E66AAA30D73 ON article');
        $this->addSql('ALTER TABLE article DROP artcile_likes_id');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649CFAE935F');
        $this->addSql('DROP INDEX IDX_8D93D649CFAE935F ON user');
        $this->addSql('ALTER TABLE user DROP user_likes_id');
    }
}
