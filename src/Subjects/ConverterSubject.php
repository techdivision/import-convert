<?php

/**
 * TechDivision\Import\Converter\Subjects\ConverterSubject
 *
 * PHP version 7
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   https://opensource.org/licenses/MIT
 * @link      https://github.com/techdivision/import-converter
 * @link      http://www.techdivision.com
 */

namespace TechDivision\Import\Converter\Subjects;

use TechDivision\Import\Subjects\ExportableTrait;
use TechDivision\Import\Subjects\AbstractEavSubject;
use TechDivision\Import\Subjects\NumberConverterTrait;
use TechDivision\Import\Subjects\ExportableSubjectInterface;
use TechDivision\Import\Subjects\NumberConverterSubjectInterface;
use TechDivision\Import\Subjects\FilesystemSubjectInterface;

/**
 * Converter subject implementation.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   https://opensource.org/licenses/MIT
 * @link      https://github.com/techdivision/import-converter
 * @link      http://www.techdivision.com
 */
class ConverterSubject extends AbstractEavSubject implements ExportableSubjectInterface, NumberConverterSubjectInterface, FilesystemSubjectInterface
{

    /**
     * The trait that implements the export functionality.
     *
     * @var \TechDivision\Import\Subjects\ExportableTrait
     */
    use ExportableTrait;

    /**
     * The trait that implements the number converter functionality.
     *
     * @var \TechDivision\Import\Subjects\NumberConverterTrait
     */
    use NumberConverterTrait;

    /**
     * The SKU of the product that has been created recently.
     *
     * @var string
     */
    protected $lastSku;

    /**
     * The mapping for the SKUs to the created entity IDs.
     *
     * @var array
     */
    protected $skuEntityIdMapping = array();

    /**
     * The mapping for the SKUs to the store view codes.
     *
     * @var array
     */
    protected $skuStoreViewCodeMapping = array();

    /**
     * Set's the SKU of the last imported product.
     *
     * @param string $lastSku The SKU
     *
     * @return void
     */
    public function setLastSku($lastSku)
    {
        $this->lastSku = $lastSku;
    }

    /**
     * Return's the SKU of the last imported product.
     *
     * @return string|null The SKU
     */
    public function getLastSku()
    {
        return $this->lastSku;
    }

    /**
     * Add the passed SKU => entity ID mapping.
     *
     * @param string $sku The SKU
     *
     * @return void
     */
    public function addSkuEntityIdMapping($sku)
    {
        $this->skuEntityIdMapping[$sku] = $this->getLastEntityId();
    }

    /**
     * Add the passed SKU => store view code mapping.
     *
     * @param string $sku           The SKU
     * @param string $storeViewCode The store view code
     *
     * @return void
     */
    public function addSkuStoreViewCodeMapping($sku, $storeViewCode)
    {
        $this->skuStoreViewCodeMapping[$sku][] = $storeViewCode;
    }
}
