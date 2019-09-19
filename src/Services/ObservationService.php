<?php
/**
 * EncounterService
 *
 * @package   OpenEMR
 * @link      http://www.open-emr.org
 * @author    Matthew Vita <matthewvita48@gmail.com>
 * @author    Jerry Padgett <sjpadgett@gmail.com>
 * @author    Brady Miller <brady.g.miller@gmail.com>
 * @copyright Copyright (c) 2018 Matthew Vita <matthewvita48@gmail.com>
 * @copyright Copyright (c) 2018 Jerry Padgett <sjpadgett@gmail.com>
 * @copyright Copyright (c) 2018 Brady Miller <brady.g.miller@gmail.com>
 * @license   https://github.com/openemr/openemr/blob/master/LICENSE GNU General Public License 3
 */


namespace OpenEMR\Services;

use Particle\Validator\Validator;

class ObservationService
{

  /**
   * Default constructor.
   */
    public function __construct()
    {
    }

    public function getObservationsForPatient($pid)
    {
        $sql = "SELECT fo.id as id,
                       fo.date,
                       fo.pid,
                       fo.encounter,
                       fo.user,
                       fo.groupname,
                       fo.authorized,
                       fo.activity,
                       fo.code,
                       fo.observation,
                       fo.ob_value,
                       fo.ob_unit,
                       fo.description,
                       fo.code_type,
                       fo.table_code
                       FROM form_observation as fo
                       WHERE pid=?
                       ORDER BY fo.id
                       DESC";

        $statementResults = sqlStatement($sql, array($pid));

        $results = array();
        while ($row = sqlFetchArray($statementResults)) {
            array_push($results, $row);
        }

        return $results;
    }

    public function getObservation($eid)
    {
        $sql = "SELECT fo.id as id,
                       fo.date,
                       fo.pid,
                       fo.encounter,
                       fo.user,
                       fo.groupname,
                       fo.authorized,
                       fo.activity,
                       fo.code,
                       fo.observation,
                       fo.ob_value,
                       fo.ob_unit,
                       fo.description,
                       fo.code_type,
                       fo.table_code
                       FROM form_observation as fo
                       WHERE fo.encounter=?
                       ORDER BY fo.id
                       DESC";

        return sqlQuery($sql, array($eid));
    }

    // @todo recm changing routes
    // encounter id is system unique so pid is not needed
    // resources should be independent where possible
    public function getObservationForPatient($pid, $oid)
    {
        $sql = "SELECT fo.id as id,
                       fo.date,
                       fo.pid,
                       fo.encounter,
                       fo.user,
                       fo.groupname,
                       fo.authorized,
                       fo.activity,
                       fo.code,
                       fo.observation,
                       fo.ob_value,
                       fo.ob_unit,
                       fo.description,
                       fo.code_type,
                       fo.table_code
                       FROM form_observation as fo
                       WHERE pid=? and fo.id=?
                       ORDER BY fo.id
                       DESC";

        return sqlQuery($sql, array($pid, $oid));
    }

}
