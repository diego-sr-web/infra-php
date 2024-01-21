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

class Session {
    public $installNerdpress = FALSE;
    /** @var Database */
    private $PDO;
    private $tblSession = NULL;

    /**
     * Session constructor.
     * @throws RuntimeException
     */
    public function __construct() {
        $this->PDO = new Database();
        $this->tblSession = "session_v2";
        $this->configDB = "pop_admin";
        $sql = "SELECT COLUMN_NAME,DATA_TYPE
                   FROM INFORMATION_SCHEMA.COLUMNS
                   WHERE
                      TABLE_SCHEMA = :DBNAME
                      AND TABLE_NAME = :TBLNAME";
        $args = [":DBNAME" => $this->configDB, ":TBLNAME" => $this->tblSession];
        $row = $this->PDO->preparedAndBindQuery($sql, $args);

        if (!isset($row[0])) {
            // IF the table doesn't exist then create the table
            $sql = "CREATE TABLE IF NOT EXISTS
                            " . $this->tblSession . " (
                                        `id` VARCHAR(32) NOT NULL,
                                        `access` INT(10) UNSIGNED DEFAULT NULL,
                                        `data` TEXT, PRIMARY KEY (`id`)
                                     )
                          ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
            $this->PDO->preparedAndBindQuery($sql, [], FALSE);
            $this->installNerdpress = TRUE;
        }
        if (session_status() == PHP_SESSION_NONE) {
            // Set handler to overide SESSION
            session_set_save_handler([$this, "_open"], [$this, "_close"], [$this, "_read"], [$this, "_write"], [$this, "_destroy"], [$this, "_gc"]);
            // Start the session
            session_start();
        }
    }

    /**
     * @return bool
     */
    public function _open() {
        if ($this->PDO) {
            return TRUE;
        }
        return FALSE;
    }

    /**
     * @return bool
     */
    public function _close() {
        $this->PDO = NULL;
        return TRUE;
    }

    /**
     * @param string $id
     *
     * @return string
     */
    public function _read($id) {
        $sql = 'SELECT data FROM ' . $this->tblSession . ' WHERE id = :id';
        $args = [':id' => $id];
        $data = $this->PDO->preparedAndBindQuery($sql, $args);
        if (isset($data[0]["data"])) {
            return $data[0]["data"];
        }
        return '';
    }

    /**
     * @param string $id
     * @param string $data
     *
     * @return bool
     */
    public function _write($id, $data) {
        $sql = 'REPLACE INTO ' . $this->tblSession . ' VALUES (:id, :access, :data)';
        $args = [':id' => $id, ':access' => time(), ':data' => $data];
        $this->PDO->preparedAndBindQuery($sql, $args, FALSE);
        return TRUE;
    }

    /**
     * @param string $id
     *
     * @return bool
     */
    public function _destroy($id) {
        $sql = 'DELETE FROM ' . $this->tblSession . ' WHERE id = :id';
        $args = [':id' => $id];
        $this->PDO->preparedAndBindQuery($sql, $args, FALSE);
        return TRUE;
    }

    /**
     * @return bool
     */
    public function clean() {
        $this->_gc();
        return TRUE;
    }

    /**
     * @param int $max
     *
     * @return bool
     */
    public function _gc($max = 3600) {
        // Calculate what is to be deemed old
        // Deactivated for now
        return TRUE;
        $old = time() - $max;
        $sql = 'DELETE * FROM ' . $this->tblSession . ' WHERE access < :old';
        $args = [':old', $old];
        $this->PDO->preparedAndBindQuery($sql, $args, FALSE);
        return TRUE;
    }
}
