<?php

/**
 * TechDivision\Import\Converter\Plugins\GenericExportableConverterPlugin
 *
 * PHP version 7
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2019 TechDivision GmbH <info@techdivision.com>
 * @license   https://opensource.org/licenses/MIT
 * @link      https://github.com/techdivision/import-converter
 * @link      http://www.techdivision.com
 */

namespace TechDivision\Import\Converter\Plugins;

use TechDivision\Import\Utils\CacheKeys;
use TechDivision\Import\Converter\Utils\ConfigurationKeys;
use TechDivision\Import\Plugins\SubjectPlugin;
use TechDivision\Import\Plugins\ExportableTrait;
use TechDivision\Import\Plugins\ExportablePluginInterface;

/**
 * Plugin that exports artefacts with type names defined in configuration.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2019 TechDivision GmbH <info@techdivision.com>
 * @license   https://opensource.org/licenses/MIT
 * @link      https://github.com/techdivision/import-converter
 * @link      http://www.techdivision.com
 */
class GenericExportableConverterPlugin extends SubjectPlugin implements ExportablePluginInterface
{

    /**
     * The trait that provides export functionality.
     *
     * @var \TechDivision\Import\Plugins\ExportableTrait
     */
    use ExportableTrait;

    /**
     * Returns the array with the exportable artefact types from the configuration.
     *
     * @return array The artefact types
     */
    protected function getExportableArtefactTypes()
    {

        // query whether or not the configuration value is available
        if ($this->getPluginConfiguration()->hasParam(ConfigurationKeys::EXPORTABLE_ARTEFACT_TYPES)) {
            return $this->getPluginConfiguration()->getParam(ConfigurationKeys::EXPORTABLE_ARTEFACT_TYPES);
        }

        // return an empty array if the param has NOT been set
        return array();
    }

    /**
     * Return's the artefacts for post-processing.
     *
     * @return array The artefacts
     */
    public function getArtefacts()
    {

        // load the available artefacts
        $artefacts = $this->getRegistryProcessor()->getAttribute(CacheKeys::ARTEFACTS);

        // initialize the array for the artefacts that has to be exported
        $toExport = array();

        // load the artefacts that has to be exported
        foreach ($this->getExportableArtefactTypes() as $exportableArtefactType) {
            if (isset($artefacts[$exportableArtefactType]) && is_array($artefacts[$exportableArtefactType])) {
                $toExport[$exportableArtefactType] = $artefacts[$exportableArtefactType];
            }
        }

        // return the array with the exportable artefac types
        return $toExport;
    }

    /**
     * Reset the array with the artefacts to free the memory.
     *
     * @return void
     */
    public function resetArtefacts()
    {
        // do nothing here, because we can't remove only one key from the array with artefacts in a multiprocess compatible manner
    }
}
