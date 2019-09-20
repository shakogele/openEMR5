<?php
/**
 * FhirPatientRestController
 *
 * @package   OpenEMR
 * @link      http://www.open-emr.org
 * @author    Shalva Gelenidze <shakogele@gmail.com>
 * @copyright Copyright (c) 2018 Jerry Padgett <sjpadgett@gmail.com>
 * @license   https://github.com/openemr/openemr/blob/master/LICENSE GNU General Public License 3
 */


namespace OpenEMR\RestControllers;

use OpenEMR\Services\ObservationService;
use OpenEMR\Services\FhirResourcesService;
use OpenEMR\RestControllers\RestControllerHelper;
use OpenEMR\FHIR\R4\FHIRResource\FHIRBundle\FHIRBundleEntry;

class FhirObservationRestController
{
    private $observationService;
    private $fhirService;

    public function __construct()
    {
        $this->observationService = new ObservationService();
        $this->fhirService = new FhirResourcesService();
    }

    // implement put post in future

    public function getOne($oid)
    {
        $observation = $this->observationService->getObservation($oid);
        $observationId = 'observation-' . $oid;
        $observationResource = $this->fhirService->createObservationResource($observationId, $observation, false);
        return RestControllerHelper::responseHandler($observationResource, null, 200);
    }

    public function createOne($fhirObservation){
        return $this->fhirService->parseResource($fhirObservation);
        return RestControllerHelper::responseHandler($observationResource, null, 200);
    }

    public function getAll($search)
    {
        $resourceURL = \RestConfig::$REST_FULL_URL;
        if (strpos($resourceURL, '?') > 0) {
            $resourceURL = strstr($resourceURL, '?', true);
        }

        $searchParam = array(
            'name' => $search['name'],
            'dob' => $search['birthdate']
        );

        $searchResult = $this->patientService->getAll($searchParam);
        if ($searchResult === false) {
            http_response_code(404);
            exit;
        }
        $entries = array();
        foreach ($searchResult as $oept) {
            $entryResource = $this->fhirService->createPatientResource($oept['pid'], $oept, false);
            $entry = array(
                'fullUrl' => $resourceURL . "/" . $oept['pid'],
                'resource' => $entryResource
            );
            $entries[] = new FHIRBundleEntry($entry);
        }
        $searchResult = $this->fhirService->createBundle('Patient', $entries, false);
        return RestControllerHelper::responseHandler($searchResult, null, 200);
    }
}
