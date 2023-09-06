<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230905081850 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE panier (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, transport_name VARCHAR(255) NOT NULL, transport_price DOUBLE PRECISION NOT NULL, adresse_livraison LONGTEXT NOT NULL, more_informations LONGTEXT NOT NULL, is_paid TINYINT(1) NOT NULL, quantity_panier INT NOT NULL, sub_total_ht DOUBLE PRECISION NOT NULL, taxe_panier DOUBLE PRECISION NOT NULL, sub_total_ttc DOUBLE PRECISION NOT NULL, created_at DATETIME NOT NULL, reference VARCHAR(255) NOT NULL, INDEX IDX_24CC0DF2A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE panier_details (id INT AUTO_INCREMENT NOT NULL, panier_id INT NOT NULL, product_name VARCHAR(255) NOT NULL, product_price DOUBLE PRECISION NOT NULL, product_quantity INT NOT NULL, subtotal_product_ht DOUBLE PRECISION NOT NULL, taxe_product DOUBLE PRECISION NOT NULL, subtotal_product_ttc DOUBLE PRECISION NOT NULL, INDEX IDX_889CCF0BF77D927C (panier_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE panier ADD CONSTRAINT FK_24CC0DF2A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE panier_details ADD CONSTRAINT FK_889CCF0BF77D927C FOREIGN KEY (panier_id) REFERENCES panier (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE panier DROP FOREIGN KEY FK_24CC0DF2A76ED395');
        $this->addSql('ALTER TABLE panier_details DROP FOREIGN KEY FK_889CCF0BF77D927C');
        $this->addSql('DROP TABLE panier');
        $this->addSql('DROP TABLE panier_details');
    }
}
