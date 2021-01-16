<?php

/**
 * TechDivision\Import\Converter\Observers\AbstractSerializerAwareConverterObserverTest
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

use TechDivision\Import\Subjects\AbstractTest;
use TechDivision\Import\Converter\Subjects\ConverterSubject;
use TechDivision\Import\Adapter\SerializerAwareAdapterInterface;
use TechDivision\Import\Serializer\SerializerInterface;
use TechDivision\Import\Serializer\SerializerFactoryInterface;

/**
 * Test class for the serializer aware subject instance.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2019 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-converter
 * @link      http://www.techdivision.com
 */
class AbstractSerializerAwareConverterObserverTest extends AbstractTest
{

    /**
     * The observer instance we want to test.
     *
     * @var \TechDivision\Import\Converter\Observers\AbstractSerializerAwareConverterObserver
     */
    protected $observer;

    /**
     * The default CSV configuration values.
     *
     * @var array
     */
    protected $defaultCsvConfiguration = array(
        'getDelimiter' => ',',
        'getEnclosure' => '"',
        'getEscape'    => '\\'
    );

    /**
     * Sets up the fixture, for example, open a network connection.
     * This method is called before a test is executed.
     *
     * @return void
     * @see \PHPUnit\Framework\TestCase::setUp()
     */
    protected function setUp()
    {

        // create the mock serializer
        $serializer = $this->getMockBuilder(SerializerInterface::class)->getMock();

        // create the mock serializer factory
        $serializerFactory = $this->getMockBuilder(SerializerFactoryInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $serializerFactory->expects($this->any())
            ->method('createSerializer')
            ->willReturn($serializer);

        // initialize the observer we want to test
        $this->observer = $this->getMockBuilder(AbstractSerializerAwareConverterObserver::class)
            ->setConstructorArgs(array($serializerFactory))
            ->getMockForAbstractClass();
    }

    /**
     * The class name of the subject we want to test.
     *
     * @return string The class name of the subject
     */
    protected function getSubjectClassName()
    {
        return ConverterSubject::class;
    }

    /**
     * Return's an array with method names that should also be mocked.
     *
     * @return array The array with the method names
     */
    protected function getSubjectMethodsToMock()
    {
        return array('getImportAdapter', 'getDefaultCallbackMappings', 'getExecutionContext');
    }

    /**
     * Test the createObserver() method and checks if the serializer has been set.
     *
     * @return void
     */
    public function testCreateObserver()
    {

        // initialize the mock serializer with the configuration to copy
        $serializer = $this->getMockBuilder(SerializerInterface::class)
            ->setMethods(get_class_methods(SerializerInterface::class))
            ->getMock();

        // initialize the mock import adapter to load the serializer from
        $importAdapter = $this->getMockBuilder(SerializerAwareAdapterInterface::class)
            ->setMethods(get_class_methods(SerializerAwareAdapterInterface::class))
            ->getMock();
        $importAdapter->expects($this->any())
            ->method('getSerializer')
            ->willReturn($serializer);

        // initialize the mock subject to load the import adapter from
        $subjectInstance = $this->getSubjectInstance();
        $subjectInstance->expects($this->any())
            ->method('getImportAdapter')
            ->willReturn($importAdapter);

        // assert that the observer contains the expected serializer
        $this->assertSame($this->observer, $this->observer->createObserver($subjectInstance));
        $this->assertInstanceOf(SerializerInterface::class, $this->observer->getSerializer());
    }
}
