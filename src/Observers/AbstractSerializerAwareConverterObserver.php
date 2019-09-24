<?php

/**
 * TechDivision\Import\Converter\Observers\AbstractSerializerAwareConverterObserver
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * PHP version 5
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2019 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-converter
 * @link      http://www.techdivision.com
 */

namespace TechDivision\Import\Converter\Observers;

use TechDivision\Import\Subjects\SubjectInterface;
use TechDivision\Import\Observers\ObserverFactoryInterface;
use TechDivision\Import\Serializers\SerializerInterface;
use TechDivision\Import\Serializers\SerializerAwareInterface;
use TechDivision\Import\Serializers\ConfigurationAwareSerializerFactoryInterface;

/**
 * Abstract import converter implementation that contains a customer serializer.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2019 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-converter
 * @link      http://www.techdivision.com
 */
abstract class AbstractSerializerAwareConverterObserver extends AbstractConverterObserver implements SerializerAwareInterface, ObserverFactoryInterface
{

    /**
     * The custom serializer instance to use.
     *
     * @var \TechDivision\Import\Serializers\SerializerInterface
     */
    protected $serializer;

    /**
     * The serializer factory to use.
     *
     * @var \TechDivision\Import\Serializers\ConfigurationAwareSerializerFactoryInterface
     */
    protected $serializerFactory;

    /**
     * The constructor to initialize the instance.
     *
     * @param \TechDivision\Import\Serializers\ConfigurationAwareSerializerFactoryInterface $serializerFactory The serializer factory instance
     */
    public function __construct(ConfigurationAwareSerializerFactoryInterface $serializerFactory)
    {
        $this->serializerFactory = $serializerFactory;
    }

    /**
     * Will be invoked by the observer visitor when a factory has been defined to create the observer instance.
     *
     * @param \TechDivision\Import\Subjects\SubjectInterface $subject The subject instance
     *
     * @return \TechDivision\Import\Observers\ObserverInterface The observer instance
     */
    public function createObserver(SubjectInterface $subject)
    {

        // initialize the serializer with the default serializer configuration from the passed subject
        $this->setSerializer($this->serializerFactory->createSerializer($subject->getImportAdapter()->getSerializer()->getCsvConfiguration()));

        // return the instance
        return $this;
    }

    /**
     * Sets the serializer instance.
     *
     * @param \TechDivision\Import\Serializers\SerializerInterface $serializer The serializer instance
     *
     * @return void
     */
    public function setSerializer(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * Returns the serializer instance.
     *
     * @return \TechDivision\Import\Serializers\SerializerInterface The serializer instance
     */
    public function getSerializer()
    {
        return $this->serializer;
    }
}
