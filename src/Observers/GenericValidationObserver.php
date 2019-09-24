<?php

/**
 * TechDivision\Import\Converter\Observers\GenericValidationObserver
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

use TechDivision\Import\Converter\Utils\ConfigurationKeys;
use TechDivision\Import\Observers\AttributeCodeAndValueAwareObserverInterface;

/**
 * Observer that invokes the callbacks on fields that has been registered for custom validation functionality.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2019 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-converter
 * @link      http://www.techdivision.com
 */
class GenericValidationObserver extends AbstractConverterObserver implements AttributeCodeAndValueAwareObserverInterface
{

    /**
     * The attribute code of the attribute to create the values for.
     *
     * @var string
     */
    protected $attributeCode;

    /**
     * The attribute value to process.
     *
     * @var mixed
     */
    protected $attributeValue;

    /**
     * The attribute code that has to be processed.
     *
     * @return string The attribute code
     */
    public function getAttributeCode()
    {
        return $this->attributeCode;
    }

    /**
     * The attribute value that has to be processed.
     *
     * @return string The attribute value
     */
    public function getAttributeValue()
    {
        return $this->attributeValue;
    }

    /**
     * Process the observer's business logic.
     *
     * @return void
     */
    protected function process()
    {

        // load the array with the custom validations from the configuration
        $customValidations = $this->getCustomValidations(ConfigurationKeys::CUSTOM_VALIDATIONS);

        // iterate over the custom validations
        foreach ($customValidations as $customValidation) {
            // initialize the attribute code/value
            $this->attributeCode = str_replace('-', '_', $customValidation);
            $this->attributeValue = $this->getValue($this->attributeCode);

            // load the callbacks for the actual attribute code
            $callbacks = $this->getCallbacksByType($this->attributeCode);

            // invoke the registered callbacks
            foreach ($callbacks as $callback) {
                $callback->handle($this);
            }
        }
    }

    /**
     * Returns the values with the passed name from the configuration.
     *
     * @return array The values
     */
    protected function getCustomValidations($name)
    {

        // load the subject instance
        $subject = $this->getSubject();

        // query whether or not the configuration value is available
        if ($subject->getConfiguration()->getConfiguration()->hasParam($name)) {
            return array_keys($subject->getConfiguration()->getConfiguration()->getParam($name));
        }

        // return an empty array if the param has NOT been set
        return array();
    }

    /**
     * Return's the array with callbacks for the passed type.
     *
     * @param string $type The type of the callbacks to return
     *
     * @return array The callbacks
     */
    protected function getCallbacksByType($type)
    {
        return $this->getSubject()->getCallbacksByType($type);
    }
}
