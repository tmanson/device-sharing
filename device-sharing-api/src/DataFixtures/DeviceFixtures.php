<?php

namespace App\DataFixtures;

use App\Entity\Device;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class DeviceFixtures extends Fixture implements FixtureGroupInterface, OrderedFixtureInterface
{
    public const DEVICES_REFERENCE = 'DEVICES';

    public function load(ObjectManager $manager)
    {
        $devices = array();
        // create 20 devices! Bam!
        for ($i = 0; $i < 40; $i++) {
            $device = new Device();
            $device->setCode('DEVICE_ ' . $i);
            $device->setDescription('Description_' . $i);
            $devices = $device;
            $manager->persist($device);
        }

        $manager->flush();
        $this->addReference(self::DEVICES_REFERENCE, $devices);
    }

    /**
     * @inheritDoc
     */
    public static function getGroups(): array
    {
        return ['test', 'test-devices'];
    }

    /**
     * @inheritDoc
     */
    public function getOrder()
    {
       return 20;
    }
}
