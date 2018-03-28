<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Driver\Connection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180328081240 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $conn = $this->connection;
                
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        
        $this->addSql('SET FOREIGN_KEY_CHECKS=0');
        
        $this->addSql('ALTER TABLE `beleg` CHANGE COLUMN `omschrijving` `omschrijving` LONGTEXT NULL');
        
        $broodjesRepo = __DIR__ . '/broodjes.csv';

        $broodjesData = [];
        if ($fp = fopen($broodjesRepo, 'r')) {
            while (!feof($fp)) {
                list($category, $topping, $priceSmall, $priceBig, $description) = fgetcsv($fp, 1024, ';');
                $broodjesData[] = [
                    'categorie' => $category,
                    'naam' => $topping,
                    'prijs_klein' => (float) str_replace(',', '.', $priceSmall),
                    'prijs_groot' => (float) str_replace(',', '.', $priceBig),
                    'omschrijving' => $description,
                ];
            }
            fclose($fp);
        }
        
        $categories = [];
        foreach (array_slice($broodjesData, 1) as $broodje) {
            
            if (!array_key_exists($broodje['categorie'], $categories)) {
                $this->addSql('INSERT INTO categorie (categorie) VALUES ("'.$broodje['categorie'].'")');
                $categoryId = $conn->lastInsertId();
                $categories[$broodje['categorie']] = $categoryId; 
            } 
            $this->addSql('INSERT INTO `beleg` 
                            (`prijsKlein`, `prijsGroot`, `naam`, `omschrijving`, `category_id`)
                            VALUES ('.$broodje['prijs_klein'].', '.$broodje['prijs_groot'].', "'.$broodje['naam'].'", "'.$broodje['omschrijving'].'", '.$categories[$broodje['categorie']].')');
            $categoryId ++;
        }
        
        $supplementenlijst = ['smos_groot', 'smos_klein', 'tabasco', 'chilly'];
        $suppPrijs =  [0.8, 0.5, 0.2, 0.2];
        $suppCount = 0;
        foreach ($supplementenlijst as $supplement)
        {
            $this->addSql('INSERT INTO `supplement` (`supplement`, `prijs`) VALUES ("'.$supplement.'", '.$suppPrijs[$suppCount].')');
            $suppCount ++;
        }
        
        $soepenlijst = ['Tomatensoep met balletjes', 'Witloofsoep', 'Heldere kippensoep', 'Pompoensoep', 'Kervelsoep', 'Niet beschikbaar', 'Niet beschikbaar'];

        foreach ($soepenlijst as $soep) {
            $this->addSql('INSERT INTO `soep` (`soep`) VALUES ("'.$soep.'")');
        }
        
        $password = password_hash('finesse', PASSWORD_DEFAULT);
        
        $this->addSql('INSERT INTO `user` (`naam`, `voornaam`, `password`, `email`, `potje`, `role`) VALUES ("Doe", "John", "'.$password.'", "johndoe@talent.it", 0, "admin")');
        
        $this->addSql('SET FOREIGN_KEY_CHECKS=1');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        
    }
}
