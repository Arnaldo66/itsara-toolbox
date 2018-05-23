<?php

namespace App\Services;

use Doctrine\ORM\EntityManagerInterface;

use App\Entity\Command;

class CommandService
{
    private $em;

    public function __construct(EntityManagerInterface $em) {
        $this->em = $em;
    }

    /**
     * Update commande notice what we'll print
     */
     public function updateCommand(array $post): void
     {
         $commandRepo = $this->em->getRepository('App\Entity\Command');
         $allCommands = $commandRepo->findAll();
         $arrayIds = [];
         foreach ($post as $key => $value) {
             $arrayIds[] = explode('_', $key)[1];
         }

         foreach ($allCommands as $command) {
             if(!$command->getIsDiscount()){
                 $command->setPrint(false);
             }
             if(in_array($command->getId(), $arrayIds)){
                 $command->setPrint(true);
             }
         }
         $this->em->flush();
     }

     /**
      * Insert csv data into local database
      */
     public function insertCSVInDB($file): void
     {
         $this->truncateCommand();
         $row = 1;
         while (($data = fgetcsv($file, 1000, ",")) !== FALSE) {
             if($row > 1){
                 $this->createCommand($data);
             }
             $row++;
         }

     }

     private function truncateCommand(): void
     {
         $connection = $this->em->getConnection();
         $platform   = $connection->getDatabasePlatform();

         $connection->executeUpdate($platform->getTruncateTableSQL('command', true));
     }

     private function createCommand(array $data)
     {
         $command = new Command;
         $command->setNumber($data[0]);
         $command->setDateCommand($data[2]);
         $command->setTypePayment($data[3]);
         $command->setArticle($data[4]);
         $command->setDeclinaison($data[5]);
         $command->setUnitPriceHT($data[6]);
         $command->setUnitPriceTTC($data[7]);
         $command->setShippingCoastHT($data[8]);
         $command->setShippingCoastTTC($data[9]);
         $command->setTVA($data[11]);
         $command->setNbArticle($data[12]);
         $command->setPriceHT($data[13]);
         $command->setPriceTTC($data[14]);
         $command->setName($data[19]);
         $command->setFirstName($data[21]);
         $command->setStreetComplement($data[22]);
         $command->setStreet($data[23]);
         $command->setPostalCode($data[24]);
         $command->setCity($data[25]);
         $command->setProvince($data[26]);
         $command->setCountry($data[27]);
         $command->setPhone($data[28]);
         $command->setEmail($data[29]);

         $command->setNameShipping($data[36]);
         $command->setFirstNameShipping($data[38]);
         $command->setStreetComplementShipping($data[39]);
         $command->setStreetShipping($data[40]);
         $command->setPostalCodeShipping($data[41]);
         $command->setCityShipping($data[42]);
         $command->setProvinceShipping($data[43]);
         $command->setCountryShipping($data[44]);
         $command->setPhoneShipping($data[45]);
         $command->setEmailShipping($data[46]);

         $command->setInfoBill($data[30]);
         $command->setInfoDelivery($data[47]);
         $command->setPrint(true);
         $command->setIsDiscount(false);

         if(strpos($data[4], 'Code de réduction') !== false){
             $command->setIsDiscount(true);
         }

         $this->em->persist($command);
         $this->em->flush();
     }
}
