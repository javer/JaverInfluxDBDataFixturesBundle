<?php

namespace Javer\InfluxDB\DataFixturesBundle\Purger;

use Fidry\AliceDataFixtures\Persistence\PurgeMode;
use Fidry\AliceDataFixtures\Persistence\PurgerFactoryInterface;
use Fidry\AliceDataFixtures\Persistence\PurgerInterface;
use InvalidArgumentException;
use Javer\InfluxDB\DataFixtures\Purger\MeasurementPurger;
use Javer\InfluxDB\ODM\MeasurementManager;

class PurgerFactory implements PurgerInterface, PurgerFactoryInterface
{
    private MeasurementManager $manager;

    private MeasurementPurger $purger;

    public function __construct(MeasurementManager $manager, ?PurgeMode $purgeMode = null)
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
        if ($purger === null) {
            return new self($this->manager, $mode);
        }

        if ($purger instanceof MeasurementPurger) {
            $manager = $purger->getMeasurementManager();
        } elseif ($purger instanceof self) {
            $manager = $purger->manager;
        } else {
            throw new InvalidArgumentException(
                sprintf(
                    'Expected purger to be either and instance of "%s" or "%s". Got "%s".',
                    MeasurementPurger::class,
                    __CLASS__,
                    get_class($purger)
                )
            );
        }

        return new self($manager, $mode);
    }

    public function purge(): void
    {
        $this->purger->purge();
    }
}
