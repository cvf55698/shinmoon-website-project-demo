<?php

namespace App\Database;

use App\Result\ResultData;

class DatabaseUtility{

    private static $instance;
	private $pdo;

    public static function getInstance(): DatabaseUtility
    {
        if (null === static::$instance) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    
    private function __construct()
    {
        $db_config = require CONFIG_PATH."database.php";
        $servername = $db_config["host"];
        $username = $db_config["username"];
        $password = $db_config["password"];
        $database = $db_config["database"];
        $port = $db_config["port"];
        $pdo= new \PDO("mysql:host=$servername;dbname=$database", $username, $password);
		$pdo->setAttribute(\PDO::ATTR_ERRMODE,\PDO::ERRMODE_EXCEPTION);
		$pdo->exec('set names utf8');
        $pdo->exec('SET SESSION TRANSACTION ISOLATION LEVEL repeatable read;');
        $pdo->exec('unlock tables');
        $this->pdo = $pdo;
    }

    private function __clone()
    {
    }

    private function __wakeup()
    {
    }
    
    public function exec($sql,$prepare_arr = []){
        $sth = $this->pdo->prepare($sql, array(\PDO::ATTR_CURSOR => \PDO::CURSOR_FWDONLY));
        $sth->execute($prepare_arr);   
    }

    public function query($sql,$prepare_arr = []){
        $sth = $this->pdo->prepare($sql, array(\PDO::ATTR_CURSOR => \PDO::CURSOR_FWDONLY));
        $sth->execute($prepare_arr); 
        $rows = $sth->fetchAll(\PDO::FETCH_ASSOC);
        $row_count = $sth->rowCount();
        $find_row = (bool) (count($rows)!=0);
        return new ResultData($find_row,null,['rows'=>$rows,'row_count'=>$row_count]);
    }

    public function get_last_insert_id(){
        return (int) $this->pdo->lastInsertId();
    }

    public function begin_transaction(){
        $this->pdo->beginTransaction();
    }

    public function commit(){
        $this->pdo->commit();
    }

    public function rollback(){
        try{
            $this->pdo->rollBack();
        }catch(\Error | \Exception $e){

        }
    }

    public function get_inTransaction():bool{
        return $this->pdo->inTransaction();
    }

}

?>