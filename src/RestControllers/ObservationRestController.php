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

    public function getOne($pid, $oid)
    {
        $serviceResult = $this->observationService->getObservationForPatient($pid, $oid);
        return RestControllerHelper::responseHandler($serviceResult, null, 200);
    }

    public function getAll($pid)
    {
        $serviceResult = $this->observationService->getObservationsForPatient($pid);
        return RestControllerHelper::responseHandler($serviceResult, null, 200);
    }

    public function post($data)
    {
        $validationResult = $this->observationService->validate($data);

        $validationHandlerResult = RestControllerHelper::validationHandler($validationResult);
        if (is_array($validationHandlerResult)) {
            return $validationHandlerResult; }

        $serviceResult = $this->observationService->insert($data);
        return RestControllerHelper::responseHandler($serviceResult, array("data" => $serviceResult), 201);
    }

    public function put($oid, $data)
    {
        $validationResult = $this->observationService->validate($data);

        $validationHandlerResult = RestControllerHelper::validationHandler($validationResult);
        if (is_array($validationHandlerResult)) {
            return $validationHandlerResult; }

        $serviceResult = $this->observationService->update($oid, $data);
        return RestControllerHelper::responseHandler($serviceResult, array("oid" => $oid), 200);
    }
}
