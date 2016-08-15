<?php
  if ( ! defined("SSNS_SCRIPT_INCLUSION") ) die('NO F*CKING DIRECT SCRIPT ACCESS ALLOWED LOL');

  abstract class Install {
    protected $tablePrefix;
    protected $dbHost;
    protected $dbPort;
    protected $dbName;
    protected $dbPassword;
    protected $dbDriver;

    function CreateAdminAccount($adminLogin, $adminPasswd, $adminMail) {
      $this->dbDriver->PrepareAndExecute(
        "INSERT INTO ".$this->tablePrefix."users(nick,password,mail,rank) VALUES ($1, $2, $3, TRUE)",
        array(
          $adminLogin,
          hash('sha512',$adminPasswd),
          $adminMail,
        )
      );
    }

    function SetConfigurationFile() {
      $configurationFile = 
        "<?php\n\t\$TABLE_PREFIX = \"".$this->tablePrefix."\";".
        "\n\t\$usePostgreSQL = true;".
        "\n\t\$dbDriver = new pgsqlDriver(\"host=".$this->dbHost.
                                          " port=".$this->dbPort.
                                          " dbname=".$this->dbName.
                                          " user=".$this->dbUser.
                                          " password=".$this->dbPasswd.
        "\");\n?>";
      $handle = fopen("_config_.php", "w");
      fwrite($handle, $configurationFile);
      mkdir("avatar");
      mkdir("items");
    }
  }

  class InstallPGSQL extends Install {
    function __construct($dbHost, $dbPort, $dbName, $dbUser, $dbPasswd, $tablePrefix) {
      $this->dbHost = $dbHost;
      $this->dbPort = $dbPort;
      $this->dbName = $dbName;
      $this->dbUser = $dbUser;
      $this->dbPasswd = $dbPasswd;
      $this->tablePrefix = $tablePrefix;

      $connectionString =  "host=".$dbHost.
                          " port=".$dbPort.
                          " dbname=".$dbName.
                          " user=".$dbUser.
                          " password=".$dbPasswd;

      $this->dbDriver = new pgsqlDriver($connectionString);
      /* DROPING ALL TABLES*/
      $this->dbDriver->Execute("DROP TABLE IF EXISTS ".$tablePrefix."system CASCADE");
      $this->dbDriver->Execute("DROP TABLE IF EXISTS ".$tablePrefix."users CASCADE");
      $this->dbDriver->Execute("DROP TABLE IF EXISTS ".$tablePrefix."clusters CASCADE");
      $this->dbDriver->Execute("DROP TABLE IF EXISTS ".$tablePrefix."entries CASCADE");
      $this->dbDriver->Execute("DROP TABLE IF EXISTS ".$tablePrefix."items CASCADE");
      $this->dbDriver->Execute("DROP TABLE IF EXISTS ".$tablePrefix."users CASCADE");

      /* BUILDING TABLES */
      $this->dbDriver->Execute(
        "CREATE TABLE ".$tablePrefix."system (
          appSummary varchar(256) DEFAULT '',
          appName       varchar(32) DEFAULT 'SANS SOU NI SOUCI'
        )
      ");
      $this->dbDriver->Execute(
        "CREATE TABLE ".$tablePrefix."users (
	  id bigserial PRIMARY KEY,
	  nick varchar(32) DEFAULT 'anonymous' NOT NULL,
	  password varchar(128) NOT NULL,
	  mail varchar(96) NOT NULL,
	  avatar varchar(128) DEFAULT '',
          about varchar(1024) DEFAULT '',
          rank boolean DEFAULT FALSE
	)"
      );
      $this->dbDriver->Execute(
        "CREATE TABLE ".$tablePrefix."clusters (
	  id bigserial PRIMARY KEY,
	  type boolean DEFAULT FALSE
	)
      ");
      $this->dbDriver->Execute(
        "CREATE TABLE ".$tablePrefix."entries (
	  idAuthor bigint REFERENCES ".$tablePrefix."users(id),
	  idCluster bigint REFERENCES ".$tablePrefix."clusters(id),
	  public boolean DEFAULT FALSE,
          tags varchar(512) DEFAULT '',
	  text varchar(1024) NOT NULL,
          title varchar(64) DEFAULT '',
	  date timestamp  NOT NULL
	)"
      );
      $this->dbDriver->Execute(
        "CREATE TABLE ".$tablePrefix."items (
	  idOwner bigint REFERENCES ".$tablePrefix."users(id),
	  idCluster bigint REFERENCES ".$tablePrefix."clusters(id),
	  public boolean DEFAULT FALSE,
	  description varchar(1024) NOT NULL,
          tags varchar(512) DEFAULT '',
          img varchar(128) DEFAULT '',
	  date timestamp  NOT NULL
	)"
      );
    }
  }
?>
