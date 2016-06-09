<?php
  if ( ! defined("SSNS_SCRIPT_INCLUSION") ) die('NO F*CKING DIRECT SCRIPT ACCESS ALLOWED LOL');

  abstract class install {
    protected $tablePrefix;
    protected $dbHost;
    protected $dbPort;
    protected $dbName;
    protected $dbPassword;

    function CreateAdminAccount($adminLogin, $adminPasswd, $adminMail) {
      $dbDriver->prepare_and_execute(
        "INSERT INTO ".$this->tablePrefix."users(nick,password,mail,rank) VALUES ($1, $2, $3, TRUE)",
        array(
          $adminLogin,
          sha1($adminPasswd),
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
    }
  }

  class installPGSQL extends install {
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

      $dbDriver = new pgsqlDriver($connectionString);
      /* DROPING ALL TABLES*/
      $dbDriver->Execute("DROP TABLE IF EXISTS ".$tablePrefix."system CASCADE");
      $dbDriver->Execute("DROP TABLE IF EXISTS ".$tablePrefix."users CASCADE");
      $dbDriver->Execute("DROP TABLE IF EXISTS ".$tablePrefix."clusters CASCADE");
      $dbDriver->Execute("DROP TABLE IF EXISTS ".$tablePrefix."entries CASCADE");
      $dbDriver->Execute("DROP TABLE IF EXISTS ".$tablePrefix."items CASCADE");
      $dbDriver->Execute("DROP TABLE IF EXISTS ".$tablePrefix."users CASCADE");

      /* BUILDING TABLES */
      $dbDriver->Execute(
        "CREATE TABLE ".$TABLE_PREFIX."system (
          appSummary varchar(256) DEFAULT '',
          appName       varchar(32) DEFAULT 'SANS SOU NI SOUCI'
        )
      ");
      $dbDriver->Execute(
        "CREATE TABLE ".$TABLE_PREFIX."users (
	  id bigserial PRIMARY KEY,
	  nick varchar(32) DEFAULT 'anonymous' NOT NULL,
	  password varchar(128) NOT NULL,
	  mail varchar(96) NOT NULL,
	  avatar varchar(128) DEFAULT '',
          about varchar(1024) DEFAULT '',
          rank boolean DEFAULT FALSE
	)"
      );
      $dbDriver->Execute(
        "CREATE TABLE ".$TABLE_PREFIX."clusters (
	  id bigserial PRIMARY KEY,
	  type boolean DEFAULT FALSE
	)
      ");
      $dbDriver->Execute(
        "CREATE TABLE ".$TABLE_PREFIX."entries (
	  idAuthor bigint REFERENCES ".$TABLE_PREFIX."users(id),
	  idCluster bigint REFERENCES ".$TABLE_PREFIX."clusters(id),
	  public boolean DEFAULT FALSE,
	  text varchar(1024) NOT NULL,
          title varchar(64) DEFAULT '',
	  date timestamp  NOT NULL
	)"
      );
      $dbDriver->Execute(
        "CREATE TABLE ".$TABLE_PREFIX."items (
	  idOwner bigint REFERENCES ".$TABLE_PREFIX."users(id),
	  idCluster bigint REFERENCES ".$TABLE_PREFIX."clusters(id),
	  public boolean DEFAULT FALSE,
	  description varchar(1024) NOT NULL,
          img varchar(128) DEFAULT '',
	  date timestamp  NOT NULL
	)"
      );
    }
  }
?>
