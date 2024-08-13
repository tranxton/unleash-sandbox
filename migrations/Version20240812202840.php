<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240812202840 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE "user_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE "user" (id INT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL ON "user" (email)');
        $this->addSql('CREATE TABLE messenger_messages (id BIGSERIAL NOT NULL, body TEXT NOT NULL, headers TEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, available_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, delivered_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name)');
        $this->addSql('CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at)');
        $this->addSql('CREATE INDEX IDX_75EA56E016BA31DB ON messenger_messages (delivered_at)');
        $this->addSql('COMMENT ON COLUMN messenger_messages.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN messenger_messages.available_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN messenger_messages.delivered_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE OR REPLACE FUNCTION notify_messenger_messages() RETURNS TRIGGER AS $$
            BEGIN
                PERFORM pg_notify(\'messenger_messages\', NEW.queue_name::text);
                RETURN NEW;
            END;
        $$ LANGUAGE plpgsql;');
        $this->addSql('DROP TRIGGER IF EXISTS notify_trigger ON messenger_messages;');
        $this->addSql('CREATE TRIGGER notify_trigger AFTER INSERT OR UPDATE ON messenger_messages FOR EACH ROW EXECUTE PROCEDURE notify_messenger_messages();');

        $this->addSql('INSERT INTO "user" (id, email, password, roles) VALUES (1, \'user1@example.com\', \'$2y$13$IxTAo6uTGb/FojXd/wO0D.DbFn3QnczlvrO3DETlX76d75XzmWVG2\', \'["ROLE_USER"]\')');
        $this->addSql('INSERT INTO "user" (id, email, password, roles) VALUES (2, \'user2@example.com\', \'$2y$13$kOgGYdak.FHuw0JdBdtCnu7O4lTggBNmthD0eRZQ3vqwI9eQg7pyG\', \'["ROLE_USER"]\')');
        $this->addSql('INSERT INTO "user" (id, email, password, roles) VALUES (3, \'user3@example.com\', \'$2y$13$xMBm53mmDJeqTW6MCP.dNOicmPKLlJH1WwTJaBSuVnqWDGnlSFYcG\', \'["ROLE_USER"]\')');
        $this->addSql('INSERT INTO "user" (id, email, password, roles) VALUES (4, \'user4@example.com\', \'$2y$13$j/zGNAqtDG0qorFRPRNY3.Hgx0R8kRkbXeT330/n/3iK99svl77Pi\', \'["ROLE_USER"]\')');
        $this->addSql('INSERT INTO "user" (id, email, password, roles) VALUES (5, \'user5@example.com\', \'$2y$13$Cv2O8JNoBZ3dBK0QOvXeIuBFsNom8gGAV8A2/PFPMEmoxiomUS9rS\', \'["ROLE_USER"]\')');
        $this->addSql('INSERT INTO "user" (id, email, password, roles) VALUES (6, \'user6@example.com\', \'$2y$13$mWi2g7TOLXwE8NkCN.XiM.W0aYPw3wI1wcuFJ84PaH8BpAPr8sTqS\', \'["ROLE_USER"]\')');
        $this->addSql('INSERT INTO "user" (id, email, password, roles) VALUES (7, \'user7@example.com\', \'$2y$13$u0oLfc1afpXCD9V7WxKwAu6XcCEaY6yoW4sVnYbE2/5Gc/b2X0HlO\', \'["ROLE_USER"]\')');
        $this->addSql('INSERT INTO "user" (id, email, password, roles) VALUES (8, \'user8@example.com\', \'$2y$13$/yGogtvMg.uAj708ZXM65eP17YfLgrLRECfolRQXOYC5taGSRBjcW\', \'["ROLE_USER"]\')');
        $this->addSql('INSERT INTO "user" (id, email, password, roles) VALUES (9, \'user9@example.com\', \'$2y$13$XgPwrc8d1An4dLE2J0FOweYYQjv7jguXlvJiaOjjwdDMGGXzGHDHa\', \'["ROLE_ADMIN"]\')');
        $this->addSql('INSERT INTO "user" (id, email, password, roles) VALUES (10, \'user10@example.com\', \'$2y$13$HP69NGVFZvD5J9bnMzci.eSHGhCnUdjpszH5O59e/k82slckDWgqC\', \'["ROLE_ADMIN"]\')');
    }

    public function down(Schema $schema): void
    {
        $this->addSql(<<<DELETE_USERS
                    DELETE
                    FROM "user"
                    WHERE email IN ('user1@example.com', 'user2@example.com', 'user3@example.com', 'user4@example.com', 
                                    'user5@example.com', 'user6@example.com', 'user7@example.com', 'user8@example.com', 
                                    'user9@example.com', 'user10@example.com')
                    DELETE_USERS);

        $this->addSql('DROP SEQUENCE "user_id_seq" CASCADE');
        $this->addSql('DROP TABLE "user"');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
