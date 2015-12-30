<?php
/**
 * @author Jaap Jansma (CiviCooP) <jaap.jansma@civicoop.org>
 * @license http://www.gnu.org/licenses/agpl-3.0.html
 */

namespace HomefinanceBundle\Importer;

class Factory {

    protected $importers;

    public function __construct()
    {
        $this->importers = array();
    }

    public function addImporter(ImporterInterface $importer, $alias) {
        $this->importers[$alias] = $importer;
    }

    public function getImporter($alias) {
        return $this->importers[$alias];
    }

    public function getAllImporters() {
        return $this->importers;
    }

    public function getImporterChoices() {
        $return = array();
        foreach($this->importers as $alias => $importer) {
            $return[$alias] = $importer->getName();
        }
        return $return;
    }

}