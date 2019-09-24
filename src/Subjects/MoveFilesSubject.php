<?php

/**
 * TechDivision\Import\Converter\Subjects\MoveFilesSubject
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
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-converter
 * @link      http://www.techdivision.com
 */

namespace TechDivision\Import\Converter\Subjects;

/**
 * Move files subject implementation.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-converter
 * @link      http://www.techdivision.com
 */
class MoveFilesSubject extends \TechDivision\Import\Subjects\MoveFilesSubject
{

    /**
     * Imports the content of the file with the passed filename.
     *
     * @param string $serial   The serial of the actual import
     * @param string $filename The filename to process
     *
     * @return void
     * @throws \Exception Is thrown, if the import can't be processed
     */
    public function import($serial, $filename)
    {

        // initialize the serial/filename
        $this->setSerial($serial);
        $this->setFilename($filename);

        // query whether the new source directory has to be created or not
        if (!$this->isDir($newSourceDir = $this->getConfiguration()->getTargetDir())) {
            $this->mkdir($newSourceDir);
        }

        // move the file to the new source directory
        $this->rename($filename, sprintf('%s/%s', $newSourceDir, basename($filename)));
    }
}
