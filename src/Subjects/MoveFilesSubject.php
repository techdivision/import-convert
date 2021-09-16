<?php

/**
 * TechDivision\Import\Converter\Subjects\MoveFilesSubject
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

/**
 * Move files subject implementation.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   https://opensource.org/licenses/MIT
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
