<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250408065222 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE comment ADD user_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE comment ADD CONSTRAINT FK_9474526CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_9474526CA76ED395 ON comment (user_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE favorite ADD user_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE favorite ADD CONSTRAINT FK_68C58ED9A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_68C58ED9A76ED395 ON favorite (user_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE comment DROP FOREIGN KEY FK_9474526CA76ED395
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_9474526CA76ED395 ON comment
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE comment DROP user_id
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE favorite DROP FOREIGN KEY FK_68C58ED9A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_68C58ED9A76ED395 ON favorite
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE favorite DROP user_id
        SQL);
    }
}
