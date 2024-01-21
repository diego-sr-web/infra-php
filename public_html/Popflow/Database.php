<?php
/**
 * Popflow - Sistema de monitoramento e organizacao de tarefas e projetos
 * PHP Version 7.2
 *
 * @package    Nerdweb
 * @author     Rafael Rotelok rotelok@nerdweb.com.br
 * @author     Junior Neves junior@nerdweb.com.br
 * @author     Giovane Ferreira giovane.ferreira@nerdweb.com.br
 * @copyright  2012-2020 Extreme Hosting Servicos de Internet LTDA
 * @license    https://nerdpress.com.br/license.txt
 * @version    Release: 3.5.0
 * @revision   2020-02-10
 */

class Database {
    /** @var PDO */
    private $pdo;
    private $args = [];
    private $lastInsertId = NULL;
    private $operationCount = 0;
    private $startTransaction = TRUE;

    /**
     * Load database configuration from the file config.php
     * or from the parameters
     *
     * Database constructor.
     *
     * @param array $args
     */
    public function __construct(array $args = []) {
        if ($args === []) {
            // Load Defaults
            $this->args["host"] = "127.0.0.1";
            $this->args["database"] = "pop_admin";
            $this->args["user"] = "root";
            $this->args["password"] = "";
        }
        else {
            // Load the custom parameters
            $this->args = $args;
        }
        $this->connect();
    }

    /**
     * Establish the connection to the database
     */
    protected function connect() {
        // Locking the charset to utf8mb4, damm you fucking emojis
        $charset = 'utf8mb4';
        // Creating the connection String
        $dsn = "mysql:host=" . $this->args["host"] . ";dbname=" . $this->args["database"] . ";charset=$charset";
        $opt = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => TRUE,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET time_zone = '-03:00'",
        ];
        try {
            $this->pdo = new PDO($dsn, $this->args["user"], $this->args["password"], $opt);
        }
        catch (PDOException $e) {
            echo $e->getMessage();
            exit;
        }
    }

    /**
     * Query the Database with the $sql passed, the call should be properly parametricized
     * the parameters are an array of $condValues, you can discard the results with $fetchResult = False
     *
     * @param string $sql
     * @param array  $condValues
     * @param bool   $fetchResult
     *
     * @return array
     */
    public function preparedAndBindQuery(string $sql, array $condValues = [], bool $fetchResult = TRUE): array {
        // Connect
        $this->connect();
        $this->pdo->beginTransaction();
        $stmt = $this->pdo->prepare($sql);
        foreach ($condValues as $key => $value) {
            $this->bind($stmt, $key, $value);
        }
        $stmt->execute($condValues);
        if (stripos($sql, "INSERT") !== FALSE || stripos($sql, "UPDATE") !== FALSE) {
            $fetchResult = FALSE;
            $this->lastInsertId = $this->pdo->lastInsertId();
        }
        $return = [];
        if ($fetchResult) {
            $return = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        // Commit and disconnect
        $this->pdo->commit();
        $this->disconnect();
        $this->operationCount+=1;
        // Return the results of the query
        return $return;
    }

    /**
     * @param PDOStatement         $stmt
     * @param string               $param
     * @param string|bool|int|null $value
     * @param null                 $type
     */
    private function bind(&$stmt, string $param, $value, $type = NULL) {
        if (is_null($type)) {
            switch (TRUE) {
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break;
                default:
                    $type = PDO::PARAM_STR;
            }
        }
        $stmt->bindValue($param, $value, $type);
    }

    /**
     * Disconnect from the database
     */
    protected function disconnect() {
        $this->pdo = NULL;
    }

    /**
     * @param string $tblname
     * @param array  $dataFieldValues
     *
     * @return bool
     */
    public function ngInsertPrepared($tblname, array $dataFieldValues) {
        $dataFields = array_keys($dataFieldValues);
        $dataValues = array_values($dataFieldValues);
        /** @noinspection PhpDeprecationInspection */
        return $this->insertPrepared($tblname, $dataFields, $dataValues);
    }

    /**
     * Insert Data into the Database
     *
     * @param string $tblname
     * @param array  $dataFields
     * @param array  $dataValues
     *
     * @return bool
     * @deprecated vai ser removida na versao 3.0
     *             Use \Nerdweb\Database->ngInsertPrepared().
     */
    public function insertPrepared(string $tblname, array $dataFields, array $dataValues): bool {
        $valuesMAsk = implode(',', array_fill(0, count($dataFields), '?'));
        $sql = "INSERT INTO $tblname (" . implode(",", $dataFields) . ") VALUES (" . $valuesMAsk . ")";
        $this->preparedQuery($sql, $dataValues);
        $last = $this->returnLastInsertedId();
        $this->lastInsertId = $last;

        return TRUE;
    }

    /**
     * Query the Database with the $sql passed, the call should be properly parametricized
     * the parameters are an array of $condValues, you can discard the results with $fetchResult = False
     *
     * @param string $sql
     * @param array  $condValues
     * @param bool   $fetchResult
     *
     * @return array
     */
    public function preparedQuery(string $sql, array $condValues = [], bool $fetchResult = TRUE) {
        if($this->startTransaction) {
            $this->pdo->beginTransaction();
        }
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($condValues);
        if (stripos($sql, "INSERT") !== FALSE || stripos($sql, "UPDATE") !== FALSE) {
            $fetchResult = FALSE;
            $this->lastInsertId = $this->pdo->lastInsertId();
        }
        $return = [];
        if ($fetchResult) {
            $return = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        if($this->startTransaction) {
            $this->pdo->commit();
        }
        $this->operationCount+=1;
        return $return;
    }

    public function returnLastInsertedId(): string {
        return $this->lastInsertId;
    }

    /**
     * @param string $tblname
     * @param array  $condNamesValues
     * @param string $fields
     * @param string $orderByField
     * @param string $limitResults
     *
     * @return array
     */
    public function ngSelectPrepared($tblname, array $condNamesValues = [], $fields = "", $orderByField = "", $limitResults = "") {
        $condFields = array_keys($condNamesValues);
        $condValues = array_values($condNamesValues);
        /** @noinspection PhpDeprecationInspection */
        return $this->selectPrepared($tblname, $condFields, $condValues, $fields, $orderByField, $limitResults);
    }

    /**
     * @param string       $tblname
     * @param array|string $condNames
     * @param array|string $condValues
     * @param string       $fields
     * @param string       $orderByField
     * @param string|int   $limitResults
     *
     * @return array
     * @deprecated vai ser removida na versao 3.0
     *             Use \Nerdweb\Database->ngSelectPrepared().
     */
    public function selectPrepared(string $tblname, $condNames = "", $condValues = "", string $fields = "", string $orderByField = "", $limitResults = ""): array {
        if ($condNames === "") {
            $condNames = [];
        }
        if ($condValues === "") {
            $condValues = [];
        }
        if ($fields === "") {
            $fields = "*";
        }
        $condicoes = $this->prepareFields($condNames, $condValues);
        $sufixQuery = " AND isUsed=1";
        if ($condNames === []) {
            $sufixQuery = "isUsed=1";
        }
        $conditions = implode(" AND ", $condicoes);
        $sql = "SELECT $fields FROM $tblname WHERE " . $conditions . $sufixQuery;
        if ($orderByField !== "") {
            $sql .= " ORDER BY " . $orderByField;
        }
        if ($limitResults !== "") {
            $sql .= " LIMIT " . $limitResults;
        }

        // Return the results of the query
        $this->startTransaction = FALSE;
        $return = $this->preparedQuery($sql, $condValues);
        if ($limitResults === 1 && isset($return[0])) {
            $return = $return[0];
        }
        $this->startTransaction = TRUE;
        return $return;
    }

    /**
     *  Common code used in the selectPrepared and updatePrepared functions
     *
     * @param array $condFields
     * @param array $condValues
     *
     * @return array
     */
    private function prepareFields(array $condFields, &$condValues): array {
        $i = 0;
        $condicoes = [];
        foreach ($condFields as $aux) {
            if ($condValues[$i] === NULL) {
                $condicoes[] = $aux . " is ?";
            }
            elseif ($condValues[$i] === "NOT NULL") {
                $condicoes[] = $aux . " is not ?";
                $condValues[$i] = NULL;
            }
            else {
                $condicoes[] = $aux . "=?";
            }
            $i++;
        }
        return $condicoes;
    }

    /**
     * @param string $tblName
     * @param array  $condNamesValues
     * @param bool   $permanentlyRemove
     *
     * @return array|bool
     */
    public function ngDeletePrepared($tblName, array $condNamesValues, $permanentlyRemove = FALSE) {
        $condNames = array_keys($condNamesValues);
        $condValues = array_values($condNamesValues);
        /** @noinspection PhpDeprecationInspection */
        return $this->deletePrepared($tblName, $condNames, $condValues, $permanentlyRemove);
    }

    /**
     * @param string       $tblname
     * @param array|string $condNames
     * @param array|int    $condValues
     * @param bool         $permanentlyRemove
     *
     * @return bool|array
     * @return bool|array
     * @deprecated vai ser removida na versao 3.0
     *             Use \Nerdweb\Database->ngDeletePrepared().
     */
    public function deletePrepared(string $tblname, $condNames, $condValues, $permanentlyRemove = FALSE) {
        if (is_array($condNames) && is_array($condValues)) {
            if ($permanentlyRemove) {
                $condicoes = $this->prepareFields($condNames, $condValues);
                $sql = "DELETE FROM $tblname WHERE " . implode(" and ", $condicoes);
                return $this->preparedQuery($sql, $condValues);
            }
            return $this->updatePrepared($tblname, ["isUsed"], [0], $condNames, $condValues);
        }
        // Legacy Behavior
        return $this->oldDelete($tblname, $condNames, $condValues);
    }

    /**
     * @param string $tblname
     * @param array  $datafields
     * @param array  $updateValues
     * @param array  $condFields
     * @param array  $condValues
     *
     * @return array
     * @deprecated vai ser removida na versao 3.0
     *             Use \Nerdweb\Database->ngUpdatePrepared().
     */
    public function updatePrepared(string $tblname, array $datafields, array $updateValues, array $condFields, array $condValues): array {
        $campos = [];
        foreach ($datafields as $aux) {
            $campos[] = $aux . "=? ";
        }
        $condicoes = $this->prepareFields($condFields, $condValues);
        $sql = "UPDATE $tblname SET " . implode(" , ", $campos) . " WHERE " . implode(" and ", $condicoes);
        $mergedUpdateCond = array_merge($updateValues, $condValues);
        return $this->preparedQuery($sql, $mergedUpdateCond);
    }

    private function oldDelete($tblname, $idName, $idValue) {
        $sql = "UPDATE $tblname SET isUsed=0 where $idName = ?";
        $this->preparedQuery($sql, [$idValue]);
        return TRUE;
    }

    /**
     * @param string $tblname
     * @param array  $dataFieldsValues
     * @param array  $condFieldsValues
     *
     * @return array
     */
    public function ngUpdatePrepared($tblname, array $dataFieldsValues, array $condFieldsValues) {
        $datafields = array_keys($dataFieldsValues);
        $updateValues = array_values($dataFieldsValues);
        $condFields = array_keys($condFieldsValues);
        $condValues = array_values($condFieldsValues);
        /** @noinspection PhpDeprecationInspection */
        return $this->updatePrepared($tblname, $datafields, $updateValues, $condFields, $condValues);
    }

    /**
     * @return string
     */
    public function getLastInsertedId(): string {
        return $this->pdo->lastInsertId();
    }

    // Legacy function

    /**
     * @param string $tblName
     * @param bool   $getType
     *
     * @return array
     */
    public function getTableRowNames($tblName, $getType = TRUE) {
        $sql = "SELECT `COLUMN_NAME`, `DATA_TYPE`, `COLUMN_DEFAULT`, `COLUMN_TYPE`, `CHARACTER_MAXIMUM_LENGTH`, `EXTRA`, `COLUMN_KEY`
                    FROM `INFORMATION_SCHEMA`.`COLUMNS`
                    WHERE `TABLE_SCHEMA` = ? AND `TABLE_NAME` = ?";
        $retorno = [];
        $columnList = $this->customQueryPDO($sql, [$this->args["database"], $tblName]);
        foreach ($columnList as $columnData) {
            if (isset($columnData["COLUMN_NAME"]) && $columnData["COLUMN_NAME"] !== "isUsed") {
                if ($getType) {
                    $tmp["type"] = $columnData["DATA_TYPE"];
                    $tmp["haveDefault"] = FALSE;
                    $tmp["primary"] = $columnData["COLUMN_KEY"];
                    $tmp["maximum_length"] = $columnData["CHARACTER_MAXIMUM_LENGTH"];
                    if ($columnData["COLUMN_DEFAULT"] !== NULL) {
                        $tmp["haveDefault"] = TRUE;
                    }
                    $tmp["size"] = 0;
                    if (preg_match("/\((\d*)\)/", $columnData["COLUMN_TYPE"], $sizeData)) {
                        $tmp["size"] = isset($sizeData[1]) ? $sizeData[1] : "Error";
                    }
                    $retorno[$columnData["COLUMN_NAME"]] = $tmp;
                }
                else {
                    $retorno[] = $columnData["COLUMN_NAME"];
                }
            }
        }
        return $retorno;
    }

    /**
     * @param string $sql
     * @param array  $condValues
     * @param bool   $fetchResult
     *
     * @return array
     */
    public function customQueryPDO(string $sql, array $condValues = [], bool $fetchResult = TRUE): array {
        return $this->preparedQuery($sql, $condValues, $fetchResult);
    }

    /**
     * @param string $tblName
     * @param array  $arrayColumn
     * @param array  $arrayKeyword
     *
     * @param string $sortBy
     * @param string $limitResults
     *
     * @return array
     */
    public function searchByKeyWord($tblName, array $arrayColumn = [], array $arrayKeyword = [], $sortBy = "", $limitResults = "") {
        $sql = "SELECT * FROM `$tblName` WHERE ";
        $i = 1;
        $size_keywords = count($arrayKeyword);
        $size_columns = count($arrayColumn);
        $size_total = $size_columns * $size_keywords;
        $condValues = [];
        foreach ($arrayColumn as $columnName) {
            foreach ($arrayKeyword as $keyword) {
                $sql .= "`$columnName` LIKE ? ";
                $condValues[] = "%" . $keyword . "%";
                if ($i < $size_total) {
                    $sql .= "OR ";
                }
                $i++;
            }
        }
        if ($sortBy !== "") {
            $sql .= " ORDER BY " . $sortBy;
        }
        if ($limitResults !== "") {
            $sql .= " LIMIT " . $limitResults;
        }
        return $this->preparedQuery($sql, $condValues);
    }

    public function getOperationCount() {
        return $this->operationCount;
    }
}
