<?php

namespace Javer\InfluxDB\DataFixturesBundle\Purger;

use Doctrine\Common\DataFixtures\Purger\PurgerInterface as DoctrinePurgerInterface;
use Fidry\AliceDataFixtures\Persistence\PurgeMode;
use Fidry\AliceDataFixtures\Persistence\PurgerFactoryInterface;
use Fidry\AliceDataFixtures\Persistence\PurgerInterface;
use InvalidArgumentException;
use Javer\InfluxDB\DataFixtures\Purger\MeasurementPurger;
use Javer\InfluxDB\ODM\MeasurementManager;

/**
 * Class PurgerFactory
 *
 * @package Javer\InfluxDB\DataFixturesBundle\Purger
 */
class PurgerFactory implements PurgerInterface, PurgerFactoryInterface
{
    private MeasurementManager $manager;

    private MeasurementPurger $purger;

    /**
     * PurgerFactory constructor.
     *
     * @param MeasurementManager $manager
     * @param PurgeMode|null     $purgeMode
     */
    public function __construct(MeasurementManager $manager, PurgeMode $purgeMode = null)
    {
        $this->manager = $manager;

        $this->purger = new MeasurementPurger($manager);

        if ($purgeMode !== null) {
            $this->purger->setPurgeMode($purgeMode->getValue());
        }
    }

    /**
     * {@inheritDoc}
     *
     * @throws InvalidArgumentException
     */
    public function create(PurgeMode $mode, PurgerInterface $purger = null): PurgerInterface
    {
        if (null === $purger) {
            return new self($this->manager, $mode);
        }

        if ($purger instanceof DoctrinePurgerInterface) {
            $manager = $purger->getObjectManager();
        } elseif ($purger instanceof self) {
            $manager = $purger->manager;
        } else {
            throw new InvalidArgumentException(
                sprintf(
                    'Expected purger to be either and instance of "%s" or "%s". Got "%s".',
                    DoctrinePurgerInterface::class,
                    __CLASS__,
                    get_class($purger)
                )
            );
        }

        if (null === $manager) {
            throw new InvalidArgumentException(
                sprintf(
                    'Expected purger "%s" to have an object manager, got "null" instead.',
                    get_class($purger)
                )
            );
        }

        return new self($manager, $mode);
    }

    /**
     * {@inheritDoc}
     */
    public function purge(): void
    {
        $this->purger->purge();
    }
}
