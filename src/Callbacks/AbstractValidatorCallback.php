<?php

/**
 * TechDivision\Import\Converter\Callbacks\AbstractValidatorCallback
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

use TechDivision\Import\Callbacks\CallbackInterface;
use TechDivision\Import\Observers\AttributeCodeAndValueAwareObserverInterface;

/**
 * Abstract callback implementation the validate the value for an specific attribute.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2019 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-converter
 * @link      http://www.techdivision.com
 */
abstract class AbstractValidatorCallback implements CallbackInterface
{

    /**
     * Will be invoked by a observer it has been registered for.
     *
     * @param \TechDivision\Import\Observers\ObserverInterface $observer The observer
     *
     * @return mixed The modified value
     */
    public function handle(AttributeCodeAndValueAwareObserverInterface $observer)
    {
        $this->validate($observer->getAttributeCode(), $observer->getAttributeValue());
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
    abstract protected function validate($attributeCode, $attributeValue);
}
