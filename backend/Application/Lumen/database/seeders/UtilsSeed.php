<?php
namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Doctrine\ORM\EntityManager;
use Domain\Entities\Utils\Currency;
use Domain\Entities\Utils\Units;

class UtilsSeed extends Seeder
{
    private EntityManager $em;

    public function __construct(
        EntityManager $em
    ){
        $this->em = $em;
    }

    public function run()
    {
        $this->setUtils();
        $this->setCurrency();
    }

    private function setCurrency() {
        $currency = new Currency();
        $currency->setCode('643');
        $currency->setTitle("RUB");
        $currency->setName("Российский рубль");
        $currency->setCreatedAt(new \DateTime());
        $this->em->persist($currency);
        $this->em->flush();
    }

    private function setUtils() {
        $list = [
            ['006','м;','Метр','1'],
            ['055','м2','Квадратный метр','1'],
            ['113','м3','Кубический метр','1'],
            ['112','л;','Литр','0.001'],
            ['166','кг;','Килограмм','1'],
            ['168','т;','Тонна','1000'],
            ['796','шт;','Штука','1'],
            ['625','лист;','Лист','1'],
            ['657','изд;','Изделие','1'],
            ['778','упак;','Упаковка','1'],

        ];

        foreach ($list as $itemUtil) {
            echo "Set DB Utils: ".$itemUtil[2]."\r\n";
            $util = new Units();
            $util->setCode($itemUtil[0]);
            $util->setTitle($itemUtil[1]);
            $util->setName($itemUtil[2]);
            $util->setFactor($itemUtil[3]);
            $util->setCreatedAt(new \DateTime());
            $this->em->persist($util);
            $this->em->flush();
        }
    }

}