<?php

/**
 * TechDivision\Import\Converter\Callbacks\GenericConfigurationBasedValidatorCallback
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

namespace TechDivision\Import\Converter\Callbacks;

use TechDivision\Import\Subjects\SubjectInterface;
use TechDivision\Import\Callbacks\CallbackFactoryInterface;
use TechDivision\Import\Converter\Utils\ConfigurationKeys;

/**
 * Abstract import converter implementation.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2019 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-converter
 * @link      http://www.techdivision.com
 */
class GenericConfigurationBasedValidatorCallback extends AbstractValidatorCallback implements CallbackFactoryInterface
{

    /**
     * The array that contains the allowed values found in the configuration.
     *
     * @var array
     */
    protected $allowedValues = array();

    /**
     * Will be invoked by the callback visitor when a factory has been defined to create the callback instance.
     *
     * @param \TechDivision\Import\Subjects\SubjectInterface $subject The subject instance
     *
     * @return \TechDivision\Import\Callbacks\CallbackInterface The callback instance
     */
    public function createCallback(SubjectInterface $subject)
    {

        // query whether or not the configuration value is available
        $this->setAllowedValues($this->getParam($subject, ConfigurationKeys::CUSTOM_VALIDATIONS));

        // return the initialized instance
        return $this;
    }

    /**
     * Setter for the params allowed values.
     *
     * @param array $allowedValues
     */
    protected function setAllowedValues(array $allowedValues)
    {
        $this->allowedValues = $allowedValues;
    }

    /**
     * Returns the values with the passed name from the configuration.
     *
     * @param string $name The param name to return the values for
     *
     * @return array The values
     */
    protected function getParam(SubjectInterface $subject, $name)
    {

        // query whether or not the configuration value is available
        if ($subject->getConfiguration()->getConfiguration()->hasParam($name)) {
            return $subject->getConfiguration()->getConfiguration()->getParam($name);
        }

        // return an empty array if the param has NOT been set
        return array();
    }

    /**
     * Returns the allowed values for the attribute with the passed code.
     *
     * @param string $attributeCode The code of the attribute to return the allowed values for
     *
     * @return array The allowed values for the attribute with the passed code
     */
    protected function getAllowedValuesByAttributeCode($attributeCode)
    {

        // query whether or not if allowed values have been specified
        if (isset($this->allowedValues[$attributeCode])) {
            return $this->allowedValues[$attributeCode];
        }

        // return an empty array, if NOT
        return array();
    }

    /**
     * Validates the value for the attribute with the given code.
     *
     * @param string $attributeCode  The code of the attribute to validate the value for
     * @param string $attributeValue The value to validate
     *
     * @return void
     * @throws \InvalidArgumentException Is thrown, if the attribute value is NOT valid for the attribute with the passed code
     */
    protected function validate($attributeCode, $attributeValue)
    {

        // load the allowed values for the attribute with the passed code
        $allowedValues = $this->getAllowedValuesByAttributeCode(str_replace('_', '-', $attributeCode));

        // if the passed value is in the array, return immediately
        if (in_array($attributeValue, $allowedValues)) {
            return;
        }

        // throw an exception if NO allowed values have been configured
        if (sizeof($allowedValues) === 0) {
            throw new \InvalidArgumentException(
                sprintf('Missing configuration value for custom validation of attribute "%s"', $attributeCode)
            );
        }

        // throw an exception if the value is NOT in the array
        throw new \InvalidArgumentException(
            sprintf(
                'Found invalid value "%s" for column "%s" (must be one of: "%s")',
                $attributeValue,
                $attributeCode,
                implode(', ', $allowedValues)
            )
        );
    }
}
