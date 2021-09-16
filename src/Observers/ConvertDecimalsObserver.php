<?php

/**
 * TechDivision\Import\Converter\ConvertDecimalsObserver
 *
 * PHP version 7
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   https://opensource.org/licenses/MIT
 * @link      https://github.com/techdivision/import-converter
 * @link      http://www.techdivision.com
 */

namespace TechDivision\Import\Converter\Observers;

use TechDivision\Import\Utils\MemberNames;
use TechDivision\Import\Utils\BackendTypeKeys;

/**
 * Observer implementation that converts decimal attributes to the Magento
 * specific decimal format.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   https://opensource.org/licenses/MIT
 * @link      https://github.com/techdivision/import-converter
 * @link      http://www.techdivision.com
 */
class ConvertDecimalsObserver extends AbstractConverterObserver
{

    /**
     * Process the observer's business logic.
     *
     * @return void
     */
    protected function process()
    {

        // convert all values columns that match an attribute with the backend
        // type 'decimal' from the specified source to the target format
        foreach ($this->getColumnNamesToConvert() as $columnName) {
            // convert the value, if the attribute code match
            if ($this->hasValue($columnName)) {
                $this->setValue($columnName, $this->getNumberConverter()->convert($this->getValue($columnName, null, function ($value) {
                    return trim($value);
                })));
            }
        }
    }

    /**
     * Returns the column names that has to be converted.
     *
     * @return array[] The column names
     */
    protected function getColumnNamesToConvert()
    {

        // initialize the array with the column names
        $columnNames = array();

        // load the EAV attributes with the backend type decimal
        foreach ($this->getEavAttributesByBackendType(BackendTypeKeys::BACKEND_TYPE_DECIMAL) as $attribute) {
            $columnNames[] = $attribute[MemberNames::ATTRIBUTE_CODE];
        }

        // return the column names
        return $columnNames;
    }

    /**
     * Returns the attributes with the passed backend type.
     *
     * @param string $backendType The backend type to return the attributes for
     *
     * @return array The attributes with the matching backend type
     */
    protected function getEavAttributesByBackendType($backendType)
    {
        return array_filter($this->getSubject()->getAttributes(), function ($attribute) use ($backendType) {
            return $attribute[MemberNames::BACKEND_TYPE] === $backendType;
        });
    }

    /**
     * Returns the number converter to use.
     *
     * @return \TechDivision\Import\Subjects\I18n\NumberConverterInterface The number converter instance
     */
    protected function getNumberConverter()
    {
        return $this->getSubject()->getNumberConverter();
    }
}
