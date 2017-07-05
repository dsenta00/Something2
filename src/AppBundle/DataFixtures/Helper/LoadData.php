<?php

namespace AppBundle\DataFixtures\Helper;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

/**
 * Class LoadFixtures.
 *
 * @package AppBundle\DataFixtures\ORM
 */
abstract class LoadData implements FixtureInterface, ContainerAwareInterface, OrderedFixtureInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var ObjectManager
     */
    protected $manager;

    /**
     * LoadFixtures constructor.
     *
     * @param ObjectManager $manager
     * @param ContainerInterface|null $container
     */
    public function __construct(ObjectManager $manager, ContainerInterface $container = null)
    {
        $this->setContainer($container);
        $this->manager = $manager;
    }

    /**
     * Sets the container.
     *
     * @param ContainerInterface|null $container A ContainerInterface instance or null
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * Add record.
     *
     * @param $function
     */
    public function addRecord($function)
    {
        $record = $function();
        $this->manager->persist($record);
        $this->manager->flush();
    }

    /**
     * Load fixtures into.
     */
    public abstract function execute();

    /**
     * Dummy.
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
    }
}