<?php

namespace Javer\InfluxDB\DataFixturesBundle\Purger;

use Fidry\AliceDataFixtures\Persistence\PurgeMode;
use Fidry\AliceDataFixtures\Persistence\PurgerFactoryInterface;
use Fidry\AliceDataFixtures\Persistence\PurgerInterface;
use InvalidArgumentException;
use Javer\InfluxDB\DataFixtures\Purger\MeasurementPurger;
use Javer\InfluxDB\ODM\MeasurementManager;

final class PurgerFactory implements PurgerInterface, PurgerFactoryInterface
{
    private readonly MeasurementPurger $purger;

    public function __construct(
        private readonly MeasurementManager $manager,
    )
    {
        $this->purger = new MeasurementPurger($manager);
    }

    /**
     * {@inheritDoc}
     *
     * @throws InvalidArgumentException
     */
    public function create(PurgeMode $mode, PurgerInterface $purger = null): PurgerInterface
    {
        if ($purger === null) {
            return new self($this->manager);
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
                    get_class($purger),
                )
            );
        }

        return new self($manager);
    }

    public function purge(): void
    {
        $this->purger->purge();
    }
}
