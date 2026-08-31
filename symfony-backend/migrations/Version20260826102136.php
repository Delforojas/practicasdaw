<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260826102136 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE class_session (id SERIAL NOT NULL, room_id INT NOT NULL, teacher_id INT NOT NULL, name VARCHAR(255) NOT NULL, description TEXT DEFAULT NULL, date_time TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, duration_minutes INT NOT NULL, max_capacity INT NOT NULL, class_type VARCHAR(100) NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_1531B22854177093 ON class_session (room_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_1531B22841807E1D ON class_session (teacher_id)
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN class_session.date_time IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE password_reset_token (id SERIAL NOT NULL, user_id INT NOT NULL, token VARCHAR(255) NOT NULL, expires_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_6B7BA4B65F37A13B ON password_reset_token (token)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_6B7BA4B6A76ED395 ON password_reset_token (user_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE reservation (id SERIAL NOT NULL, user_id INT NOT NULL, class_id INT NOT NULL, wallet_voucher_id INT DEFAULT NULL, created_by_admin_id INT DEFAULT NULL, status VARCHAR(50) NOT NULL, reservation_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_42C84955A76ED395 ON reservation (user_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_42C84955EA000B10 ON reservation (class_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_42C84955AFF524AD ON reservation (wallet_voucher_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_42C8495564F1F4EE ON reservation (created_by_admin_id)
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN reservation.reservation_date IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE room (id SERIAL NOT NULL, name VARCHAR(100) NOT NULL, description TEXT DEFAULT NULL, max_capacity INT NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE "user" (id SERIAL NOT NULL, full_name VARCHAR(150) NOT NULL, surname VARCHAR(150) DEFAULT NULL, username VARCHAR(150) NOT NULL, address VARCHAR(150) DEFAULT NULL, postal_code VARCHAR(20) DEFAULT NULL, city VARCHAR(150) DEFAULT NULL, email VARCHAR(180) NOT NULL, password VARCHAR(255) NOT NULL, role VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, phone VARCHAR(20) DEFAULT NULL, profile_image VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN "user".created_at IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE voucher (id SERIAL NOT NULL, name VARCHAR(150) NOT NULL, class_type VARCHAR(100) NOT NULL, total_uses VARCHAR(255) NOT NULL, valid_until TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN voucher.valid_until IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE wallet (id SERIAL NOT NULL, user_id INT NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_7C68921FA76ED395 ON wallet (user_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE wallet_voucher (id SERIAL NOT NULL, wallet_id INT NOT NULL, voucher_id INT NOT NULL, remaining_uses INT NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_629380CE712520F3 ON wallet_voucher (wallet_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_629380CE28AA1B6F ON wallet_voucher (voucher_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE messenger_messages (id BIGSERIAL NOT NULL, body TEXT NOT NULL, headers TEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, available_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, delivered_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_75EA56E016BA31DB ON messenger_messages (delivered_at)
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN messenger_messages.created_at IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN messenger_messages.available_at IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN messenger_messages.delivered_at IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            CREATE OR REPLACE FUNCTION notify_messenger_messages() RETURNS TRIGGER AS $$
                BEGIN
                    PERFORM pg_notify('messenger_messages', NEW.queue_name::text);
                    RETURN NEW;
                END;
            $$ LANGUAGE plpgsql;
        SQL);
        $this->addSql(<<<'SQL'
            DROP TRIGGER IF EXISTS notify_trigger ON messenger_messages;
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TRIGGER notify_trigger AFTER INSERT OR UPDATE ON messenger_messages FOR EACH ROW EXECUTE PROCEDURE notify_messenger_messages();
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE class_session ADD CONSTRAINT FK_1531B22854177093 FOREIGN KEY (room_id) REFERENCES room (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE class_session ADD CONSTRAINT FK_1531B22841807E1D FOREIGN KEY (teacher_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE password_reset_token ADD CONSTRAINT FK_6B7BA4B6A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reservation ADD CONSTRAINT FK_42C84955A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reservation ADD CONSTRAINT FK_42C84955EA000B10 FOREIGN KEY (class_id) REFERENCES class_session (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reservation ADD CONSTRAINT FK_42C84955AFF524AD FOREIGN KEY (wallet_voucher_id) REFERENCES wallet_voucher (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reservation ADD CONSTRAINT FK_42C8495564F1F4EE FOREIGN KEY (created_by_admin_id) REFERENCES "user" (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE wallet ADD CONSTRAINT FK_7C68921FA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE wallet_voucher ADD CONSTRAINT FK_629380CE712520F3 FOREIGN KEY (wallet_id) REFERENCES wallet (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE wallet_voucher ADD CONSTRAINT FK_629380CE28AA1B6F FOREIGN KEY (voucher_id) REFERENCES voucher (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE SCHEMA public
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE class_session DROP CONSTRAINT FK_1531B22854177093
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE class_session DROP CONSTRAINT FK_1531B22841807E1D
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE password_reset_token DROP CONSTRAINT FK_6B7BA4B6A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reservation DROP CONSTRAINT FK_42C84955A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reservation DROP CONSTRAINT FK_42C84955EA000B10
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reservation DROP CONSTRAINT FK_42C84955AFF524AD
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reservation DROP CONSTRAINT FK_42C8495564F1F4EE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE wallet DROP CONSTRAINT FK_7C68921FA76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE wallet_voucher DROP CONSTRAINT FK_629380CE712520F3
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE wallet_voucher DROP CONSTRAINT FK_629380CE28AA1B6F
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE class_session
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE password_reset_token
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE reservation
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE room
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE "user"
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE voucher
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE wallet
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE wallet_voucher
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE messenger_messages
        SQL);
    }
}
