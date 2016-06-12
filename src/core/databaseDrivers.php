<?php

  class QueryResultHandler {
    private $dbDriverName;
    private $dbResult;
    private $data;

    function __construct($dbDriverName, $result) {
      $this->dbDriverName = $dbDriverName;
      $this->dbResult = $result;
    }

    public function CountRow() {
      if ($this->dbDriverName == "PostgreSQL") {
        return pg_num_rows($this->dbResult);
      }
    }

    public function Get() {
      if ($this->dbDriverName == "PostgreSQL") {
        $this->data = pg_fetch_object($this->dbResult);
        return $this->data;
      }
    }
  }

  abstract class DatabaseDriver {
    private $host;
    private $port;
    private $dbname;
    private $dbuser;
    private $password;
    private $connection;

    abstract public function PrepareAndExecute($query, $param);
    abstract public function Execute($query);

    function __construct($connectionString) {
      $explodedConnectionString = explode(" ", $connectionString);
      $host = explode("=", $explodedConnectionString[0]);
      $port = explode("=", $explodedConnectionString[1]);
      $dbname = explode("=", $explodedConnectionString[2]);
      $dbuser = explode("=", $explodedConnectionString[3]);
      $password = explode("=", $explodedConnectionString[4]);
      $this->host = $password[1];
      $this->port = $port[1];
      $this->dbname = $dbname[1];
      $this->dbuser = $dbuser[1];
      $this->password = $password[1];
    }

    public function Begin() {
      $this->Execute("BEGIN");
    }

    public function ROLLBACK() {
      $this->Execute("ROLLBACK");
    }

    public function COMMIT() {
      $this->Execute("COMMIT");
    }

    protected function ThrowError($e) {
      if (strpos($e->getMessage(), $this->host) != false && strpos($e->getMessage(), $this->port) == false) {
        throw new ErrorException("Unknow Host", 129);
      }
      else if (strpos($e->getMessage(), $this->port) != false) {
        throw new ErrorException("Wrong port", 130);
      }
      else if (strpos($e->getMessage(), $this->dbname) != false) {
        throw new ErrorException("Wrong database name", 131);
      }
      else if (strpos($e->getMessage(), $this->dbuser) != false) {
        throw new ErrorException("Authentification failed", 132);
      }
      else {
        throw $e;
      }
    }
  }

  class pgsqlDriver extends DatabaseDriver{

    public function PrepareAndExecute($query, $param) {
      $queryName = microtime(true);
      pg_prepare($this->connection, $queryName, $query);
      return new QueryResultHandler(
        "PostgreSQL",
        pg_execute($this->connection, $queryName, $param)
      );
    }
		
    public function Execute($query) {
      if (!pg_connection_busy($this->connection)) {
        pg_send_query($this->connection, $query);
  	$result = pg_get_result($this->connection);
	if ("" != pg_result_error($result)) {
	  throw new ErrorException(
	    pg_result_error_field(
              $result,
              PGSQL_DIAG_SQLSTATE
            )."\n".
            pg_result_error_field(
	
              $result,
              PGSQL_DIAG_MESSAGE_PRIMARY
            )."\n".
            pg_result_error_field(
              $result, 
              PGSQL_DIAG_MESSAGE_DETAIL
            )
	  );
	}
	return new QueryResultHandler(
          "PostgreSQL",
          $result
        );
      }
    }

    function __construct($connectionString) {
      parent::__construct($connectionString);
      try {
        $this->connection = pg_connect($connectionString);
      }
      catch(Exception $e) {
        $this->ThrowError($e);
      }
    }
  }

?>
