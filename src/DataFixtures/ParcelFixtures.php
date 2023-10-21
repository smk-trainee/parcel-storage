<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Address;
use App\Entity\Dimensions;
use App\Entity\FullName;
use App\Entity\Parcel;
use App\Entity\Recipient;
use App\Entity\Sender;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ParcelFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $randNum = rand(1, 100);
        $randDimensionVal = rand(1, 200);
        $valuation = rand(200, 1000000);

        $fullNames = [
            new FullName("John", "Lennon", "Winston"),
            new FullName("James", "McCartney", "Paul"),
            new FullName("George", "Harrison", "Pattie")
        ];
        $addresses = [
            new Address("France", "Paris", "St 8th", $randNum, $randNum),
            new Address("Россия", "Уфа", "Кирова", $randNum, $randNum),
            new Address("England", "London", "St Baker", $randNum, $randNum),
        ];
        $phone = "+79999999999";
        $senders = [
            new Sender($fullNames[array_rand($fullNames)], $phone, $addresses[array_rand($addresses)]),
            new Sender($fullNames[array_rand($fullNames)], $phone, $addresses[array_rand($addresses)]),
            new Sender($fullNames[array_rand($fullNames)], $phone, $addresses[array_rand($addresses)]),
        ];
        $recipients = [
            new Recipient($fullNames[array_rand($fullNames)], $phone, $addresses[array_rand($addresses)]),
            new Recipient($fullNames[array_rand($fullNames)], $phone, $addresses[array_rand($addresses)]),
            new Recipient($fullNames[array_rand($fullNames)], $phone, $addresses[array_rand($addresses)]),
        ];
        $dimensions = [
            new Dimensions($randDimensionVal, $randDimensionVal, $randDimensionVal, $randDimensionVal),
            new Dimensions($randDimensionVal, $randDimensionVal, $randDimensionVal, $randDimensionVal),
            new Dimensions($randDimensionVal, $randDimensionVal, $randDimensionVal, $randDimensionVal),
        ];
        $manager->persist(new Parcel($senders[0], $recipients[0], $dimensions[0], $valuation));
        $manager->persist(new Parcel($senders[1], $recipients[1], $dimensions[1], $valuation));
        $manager->persist(new Parcel($senders[2], $recipients[2], $dimensions[2], $valuation));

        $manager->flush();
    }
}
