<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231019193643 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE parcel_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE parcel (id INT NOT NULL, valuation INT NOT NULL, sender_phone VARCHAR(255) NOT NULL, sender_full_name_first_name VARCHAR(255) NOT NULL, sender_full_name_last_name VARCHAR(255) NOT NULL, sender_full_name_middle_name VARCHAR(255) NOT NULL, sender_address_country VARCHAR(255) NOT NULL, sender_address_city VARCHAR(255) NOT NULL, sender_address_street VARCHAR(255) NOT NULL, sender_address_house INT NOT NULL, sender_address_apartment INT NOT NULL, recipient_phone VARCHAR(255) NOT NULL, recipient_full_name_first_name VARCHAR(255) NOT NULL, recipient_full_name_last_name VARCHAR(255) NOT NULL, recipient_full_name_middle_name VARCHAR(255) NOT NULL, recipient_address_country VARCHAR(255) NOT NULL, recipient_address_city VARCHAR(255) NOT NULL, recipient_address_street VARCHAR(255) NOT NULL, recipient_address_house INT NOT NULL, recipient_address_apartment INT NOT NULL, dimensions_weight INT NOT NULL, dimensions_length INT NOT NULL, dimensions_height INT NOT NULL, dimensions_width INT NOT NULL, PRIMARY KEY(id))');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE parcel_id_seq CASCADE');
        $this->addSql('DROP TABLE parcel');
    }
}
