<?php
  if ( ! defined("SSNS_SCRIPT_INCLUSION") ) die('NO F*CKING DIRECT SCRIPT ACCESS ALLOWED LOL');
  
  abstract class Content {
    protected $contents;

    abstract public function PrintContent();
    
    protected function PrintRelatedKeywords($keywords) {
      $output = "<ul class=\"contentKeywords\">";
      foreach( explode(',',$keywords) as $keyword ){
        $output .= "<li><a href=\"?filterByKeywords=".trim($keyword)."\">#".trim($keyword)."</a></li>";
      }
      $output .= "</ul>"; 
      return $output;
    }

    function __construct() {
      $this->contents = array();
    }

    public function SetContent($content) {
      array_push($this->contents, $content);
    }

  }
  
  class Entry extends Content {
    function __construct() {
      parent::__construct();
    }

    public function PrintContent() {
    }
  }

  class Items extends Content {

    function __construct() {
      parent::__construct();
    }

    public function PrintContent() {
      echo "<div class=\"cluster\">";
      $output = array('','');
      $i = 0;
      foreach($this->contents as $currentContent) {
        if ($i % 2 == 0) {
          $itemOwner = GetUser($currentContent->idowner);
          $output[$i%2] .= "<div class=\"item\">";
          $output[$i%2] .= "<div class=\"itemHeader\"><div class=\"itemImg\">";
          $output[$i%2] .= "<img alt=\"\" src=\"items/".$currentContent->img."\">";
          $output[$i%2] .= "</div>";
          $output[$i%2] .= "<div id=\"item".$currentContent->id."\" class=\"itemId\">";
          $output[$i%2] .= "#".$currentContent->id;
          $output[$i%2] .= "</div></div>";  
          $output[$i%2] .= "<div class=\"itemUserAvatar\">";
          $output[$i%2] .= "<a href=\"?page=profil&id=".$itemOwner->id."\"><img alt=\"\" src=\"avatars/".$itemOwner->avatar."\"></a>";
          $output[$i%2] .= "</div>";
          $output[$i%2] .= "<div class=\"itemDescription\">";
          $output[$i%2] .= htmlentities($currentContent->description)."\">";
          $output[$i%2] .= "</div>";
          $output[$i%2] .= $this->PrintRelatedKeywords(htmlentities($currentContent->tags));
          $output[$i%2] .= "</div>"; 
        }
        $i++;
      }
      echo "<div class=\"column1\">".$output[0]."</div>"."<div class=\"column2\">".$output[1]."</div>";
      echo "</div>";
    }
  }

  class Clusters {

    private $clustersFeed;

    public function PrintContents() {
      foreach($this->clustersFeed as $cluster) {
        $cluster->PrintContent();
      }
    }

    function __construct($filters) {
      global $dbDriver;
      global $TABLE_PREFIX;

      $this->clustersFeed = array();

      $QueryClustersString = "SELECT * FROM ".$TABLE_PREFIX."clusters";
      
      try {
        if ($filters["clusterType"] == true) {
          $QueryClustersString .= " WHERE type = true";
        }
        else if ($filters["clusterType"] == false) {
          $QueryClustersString .= " WHERE type = false";
        }
      }
      catch (Exception $e) {}

      $QueryClustersResult = $dbDriver->PrepareAndExecute(
        $QueryClustersString,
        array()
      );

      while( $cluster = $QueryClustersResult->Get() ) {
        // Set related tables name
        if($cluster->type == 1) {
          $relatedUserTableName = "idauthor";
          $relatedTableName = "entries";
        }
        else {
          $relatedUserTableName = "idowner";
          $relatedTableName = "items";
        }

        $QueryContentString = "SELECT * FROM ".$TABLE_PREFIX.$relatedTableName;
        $params = array();

        $QueryContentResult = $dbDriver->PrepareAndExecute(
          $QueryContentString,
          $params
        );
  
        if($cluster->type == 1) {
          $currentDataSet = new Entry();
        }
        else {
          $currentDataSet = new Items();
        }

        while ($content = $QueryContentResult->Get()) {
          $currentDataSet->SetContent($content);
        }

        array_push($this->clustersFeed, $currentDataSet);
      }
    }
  }
?>
