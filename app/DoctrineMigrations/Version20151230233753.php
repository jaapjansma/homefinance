<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20151230233753 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE rule_action (id INT AUTO_INCREMENT NOT NULL, rule_id INT DEFAULT NULL, action VARCHAR(255) NOT NULL, params LONGTEXT DEFAULT NULL, INDEX IDX_DC667C02744E0351 (rule_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE administration (id INT AUTO_INCREMENT NOT NULL, owner_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(64) NOT NULL, UNIQUE INDEX UNIQ_9FDD0D18989D9B62 (slug), INDEX IDX_9FDD0D187E3C61F9 (owner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE transaction (id INT AUTO_INCREMENT NOT NULL, administration_id INT DEFAULT NULL, bank_account_id INT NOT NULL, category_id INT DEFAULT NULL, parent_id INT DEFAULT NULL, date DATETIME NOT NULL, name VARCHAR(255) DEFAULT NULL, iban VARCHAR(255) DEFAULT NULL, description LONGTEXT DEFAULT NULL, amount DOUBLE PRECISION NOT NULL, is_processed TINYINT(1) NOT NULL, source_data LONGTEXT DEFAULT NULL, source_id VARCHAR(255) DEFAULT NULL, INDEX IDX_723705D139B8E743 (administration_id), INDEX IDX_723705D112CB990C (bank_account_id), INDEX IDX_723705D112469DE2 (category_id), INDEX IDX_723705D1727ACA70 (parent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tag_transactions (transaction_id INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_4ABE314E2FC0CB0F (transaction_id), INDEX IDX_4ABE314EBAD26311 (tag_id), PRIMARY KEY(transaction_id, tag_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rule_condition (id INT AUTO_INCREMENT NOT NULL, rule_id INT DEFAULT NULL, `condition` VARCHAR(255) NOT NULL, params LONGTEXT DEFAULT NULL, condition_link VARCHAR(255) DEFAULT NULL, INDEX IDX_627A9B63744E0351 (rule_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tag (id INT AUTO_INCREMENT NOT NULL, administration_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(64) NOT NULL, UNIQUE INDEX UNIQ_389B783989D9B62 (slug), INDEX IDX_389B78339B8E743 (administration_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rule (id INT AUTO_INCREMENT NOT NULL, administration_id INT DEFAULT NULL, INDEX IDX_46D8ACCC39B8E743 (administration_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bank_account (id INT AUTO_INCREMENT NOT NULL, administration_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, iban VARCHAR(255) NOT NULL, image VARCHAR(255) DEFAULT NULL, starting_balance DOUBLE PRECISION NOT NULL, INDEX IDX_53A23E0A39B8E743 (administration_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, current_administration_id INT DEFAULT NULL, password VARCHAR(255) DEFAULT NULL, salt VARCHAR(32) NOT NULL, lastlogin DATETIME DEFAULT NULL, password_requested_at DATETIME DEFAULT NULL, confirmation_token VARCHAR(128) DEFAULT NULL, confirmation_requested_at DATETIME DEFAULT NULL, confirmation_token_valid_till DATETIME DEFAULT NULL, email VARCHAR(255) NOT NULL, new_email VARCHAR(255) DEFAULT NULL, enabled TINYINT(1) NOT NULL, locked TINYINT(1) NOT NULL, name VARCHAR(255) DEFAULT NULL, avatar VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), INDEX IDX_8D93D6496D88F9F7 (current_administration_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, administration_id INT DEFAULT NULL, parent_id INT DEFAULT NULL, title VARCHAR(64) NOT NULL, type VARCHAR(64) NOT NULL, slug VARCHAR(64) NOT NULL, lft INT NOT NULL, rgt INT NOT NULL, root INT DEFAULT NULL, lvl INT NOT NULL, UNIQUE INDEX UNIQ_64C19C1989D9B62 (slug), INDEX IDX_64C19C139B8E743 (administration_id), INDEX IDX_64C19C1727ACA70 (parent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE share (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, administration_id INT DEFAULT NULL, permission VARCHAR(255) NOT NULL, INDEX IDX_EF069D5AA76ED395 (user_id), INDEX IDX_EF069D5A39B8E743 (administration_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE rule_action ADD CONSTRAINT FK_DC667C02744E0351 FOREIGN KEY (rule_id) REFERENCES rule (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE administration ADD CONSTRAINT FK_9FDD0D187E3C61F9 FOREIGN KEY (owner_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D139B8E743 FOREIGN KEY (administration_id) REFERENCES administration (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D112CB990C FOREIGN KEY (bank_account_id) REFERENCES bank_account (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D112469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D1727ACA70 FOREIGN KEY (parent_id) REFERENCES transaction (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tag_transactions ADD CONSTRAINT FK_4ABE314E2FC0CB0F FOREIGN KEY (transaction_id) REFERENCES transaction (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tag_transactions ADD CONSTRAINT FK_4ABE314EBAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE rule_condition ADD CONSTRAINT FK_627A9B63744E0351 FOREIGN KEY (rule_id) REFERENCES rule (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tag ADD CONSTRAINT FK_389B78339B8E743 FOREIGN KEY (administration_id) REFERENCES administration (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE rule ADD CONSTRAINT FK_46D8ACCC39B8E743 FOREIGN KEY (administration_id) REFERENCES administration (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE bank_account ADD CONSTRAINT FK_53A23E0A39B8E743 FOREIGN KEY (administration_id) REFERENCES administration (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6496D88F9F7 FOREIGN KEY (current_administration_id) REFERENCES administration (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE category ADD CONSTRAINT FK_64C19C139B8E743 FOREIGN KEY (administration_id) REFERENCES administration (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE category ADD CONSTRAINT FK_64C19C1727ACA70 FOREIGN KEY (parent_id) REFERENCES category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE share ADD CONSTRAINT FK_EF069D5AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE share ADD CONSTRAINT FK_EF069D5A39B8E743 FOREIGN KEY (administration_id) REFERENCES administration (id) ON DELETE CASCADE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE transaction DROP FOREIGN KEY FK_723705D139B8E743');
        $this->addSql('ALTER TABLE tag DROP FOREIGN KEY FK_389B78339B8E743');
        $this->addSql('ALTER TABLE rule DROP FOREIGN KEY FK_46D8ACCC39B8E743');
        $this->addSql('ALTER TABLE bank_account DROP FOREIGN KEY FK_53A23E0A39B8E743');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6496D88F9F7');
        $this->addSql('ALTER TABLE category DROP FOREIGN KEY FK_64C19C139B8E743');
        $this->addSql('ALTER TABLE share DROP FOREIGN KEY FK_EF069D5A39B8E743');
        $this->addSql('ALTER TABLE transaction DROP FOREIGN KEY FK_723705D1727ACA70');
        $this->addSql('ALTER TABLE tag_transactions DROP FOREIGN KEY FK_4ABE314E2FC0CB0F');
        $this->addSql('ALTER TABLE tag_transactions DROP FOREIGN KEY FK_4ABE314EBAD26311');
        $this->addSql('ALTER TABLE rule_action DROP FOREIGN KEY FK_DC667C02744E0351');
        $this->addSql('ALTER TABLE rule_condition DROP FOREIGN KEY FK_627A9B63744E0351');
        $this->addSql('ALTER TABLE transaction DROP FOREIGN KEY FK_723705D112CB990C');
        $this->addSql('ALTER TABLE administration DROP FOREIGN KEY FK_9FDD0D187E3C61F9');
        $this->addSql('ALTER TABLE share DROP FOREIGN KEY FK_EF069D5AA76ED395');
        $this->addSql('ALTER TABLE transaction DROP FOREIGN KEY FK_723705D112469DE2');
        $this->addSql('ALTER TABLE category DROP FOREIGN KEY FK_64C19C1727ACA70');
        $this->addSql('DROP TABLE rule_action');
        $this->addSql('DROP TABLE administration');
        $this->addSql('DROP TABLE transaction');
        $this->addSql('DROP TABLE tag_transactions');
        $this->addSql('DROP TABLE rule_condition');
        $this->addSql('DROP TABLE tag');
        $this->addSql('DROP TABLE rule');
        $this->addSql('DROP TABLE bank_account');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE share');
    }
}
