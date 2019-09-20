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

    public function validate($observation)
    {
        $validator = new Validator();

        $validator->required('pid')->lengthBetween(1, 255);
        $validator->required('encounter')->lengthBetween(1, 255);
        $validator->required('code')->lengthBetween(4, 30);
        $validator->required('date')->datetime('Y-m-d');
        $validator->required('observation')->lengthBetween(2, 255);
        $validator->required('ob_value')->lengthBetween(2, 255);
        $validator->required('ob_unit')->lengthBetween(2, 255);

        return $validator->validate($observation);
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

    public function getObservation($oid)
    {
        $sql = "SELECT fo.id,
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
                       fo.table_code,
                       pd.lname as patient
                       FROM form_observation as fo
                       LEFT JOIN patient_data pd
                       ON fo.pid = pd.id
                       WHERE fo.id=?
                       ORDER BY fo.id
                       DESC";
        return sqlQuery($sql, array($oid));
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

    public function insert($data)
    {
        $sql = " INSERT INTO form_observation SET";
        $sql .= "     id=?,";
        $sql .= "     date=?,";
        $sql .= "     pid=?,";
        $sql .= "     encounter=?,";
        $sql .= "     user=?,";
        $sql .= "     groupname=?,";
        $sql .= "     authorized=?,";
        $sql .= "     activity=?,";
        $sql .= "     code=?,";
        $sql .= "     observation=?,";
        $sql .= "     ob_value=?,";
        $sql .= "     ob_unit=?,";
        $sql .= "     description=?,";
        $sql .= "     code_type=?,";
        $sql .= "     table_code=?";

        $results = sqlInsert(
            $sql,
            array(
                1,
                $data["date"],
                $data["pid"],
                $data["encounter"],
                $data["user"],
                $data["groupname"],
                $data["authorized"],
                $data["activity"],
                $data["code"],
                $data["observation"],
                $data["ob_value"],
                $data["ob_unit"],
                $data["description"],
                $data["code_type"],
                $data["table_code"]
            )
        );

        if ($results) {
            return $data;
        }

        return $results;
    }

    public function update($oid, $data)
    {
        $sql = " UPDATE form_observation SET";
        $sql .= "     date=?,";
        $sql .= "     pid=?,";
        $sql .= "     encounter=?,";
        $sql .= "     user=?,";
        $sql .= "     groupname=?,";
        $sql .= "     authorized=?,";
        $sql .= "     activity=?,";
        $sql .= "     code=?,";
        $sql .= "     observation=?,";
        $sql .= "     ob_value=?,";
        $sql .= "     ob_unit=?,";
        $sql .= "     description=?,";
        $sql .= "     code_type=?,";
        $sql .= "     table_code=?";
        $sql .= "     where id=?";

        return sqlStatement(
            $sql,
            array(
                $data["date"],
                $data["pid"],
                $data["encounter"],
                $data["user"],
                $data["groupname"],
                $data["authorized"],
                $data["activity"],
                $data["code"],
                $data["observation"],
                $data["ob_value"],
                $data["ob_unit"],
                $data["description"],
                $data["code_type"],
                $data["table_code"],
                $oid
            )
        );
    }

}
