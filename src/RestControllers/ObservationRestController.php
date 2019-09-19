<?php
/**
 * EncounterRestController
 *
 * @package   OpenEMR
 * @link      http://www.open-emr.org
 * @author    Matthew Vita <matthewvita48@gmail.com>
 * @copyright Copyright (c) 2018 Matthew Vita <matthewvita48@gmail.com>
 * @license   https://github.com/openemr/openemr/blob/master/LICENSE GNU General Public License 3
 */


namespace OpenEMR\RestControllers;

use OpenEMR\Services\ObservationService;
use OpenEMR\RestControllers\RestControllerHelper;

class ObservationRestController
{
    private $observationService;

    public function __construct()
    {
        $this->observationService = new ObservationService();
    }

    public function getOne($pid, $eid)
    {
        $serviceResult = $this->observationService->getObservationForPatient($pid, $eid);
        return RestControllerHelper::responseHandler($serviceResult, null, 200);
    }

    public function getAll($pid)
    {
        $serviceResult = $this->observationService->getObservationsForPatient($pid);
        return RestControllerHelper::responseHandler($serviceResult, null, 200);
    }
}
