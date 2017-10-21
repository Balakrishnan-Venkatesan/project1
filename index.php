<?php

ini_set('display_errors', 'On');
error_reporting(E_ALL);

class Manage {
 public static function autoload($class) {
   include $class . '.php';
 }
}

spl_autoload_register(array('Manage', 'autoload'));
$obj = new main();


class main {

 public function __construct()
 {
  $pageRequest = 'homepage';
  if(isset($_REQUEST['page'])) {
  	$pageRequest = $_REQUEST['page'];
  }

  $page = new $pageRequest;

  if($_SERVER['REQUEST_METHOD'] == 'GET') {
      $page->get();
     } else {
      $page->post();
     }
 }
} 

abstract class page {
    protected $html;

    public function __construct()
    {
      $this->html .= '<html>';
      $this->html .= '<link rel="stylesheet" href="styles.css">';
      $this->html .= '<body>';
    }
    public function __destruct()
    {
      $this->html .= '</body></html>';
      stringFunctions::printThis($this->html);
    }
    public function get() {
      echo 'default get message';
    }
    public function post() {
      print_r($_POST);
    }
}

class homepage extends page {
  
  public function get() {
   $form = '<form action="index.php" method="post" enctype="multipart/form-data">';
   $form .= '<h1> csv upload </h1> <br>';
   $form .= '<input type="file" name="chooseFile" id="chooseFile">';
   $form .= '<input type="submit" value="submit">';
   $form .= '</form> ';
   $this->html .= $form;
 }

  public function post() {
  // if(isset($_POST["submit"])) {
     $fileName = $_FILES["chooseFile"]["name"];
     $tmpFileName = $_FILES["chooseFile"]["tmp_name"];
     $fileName =  upload::csvUpload($fileName,$tmpFileName);
     header('Location:?page=htmlTable&fileName='. $fileName);
  // }
  }

}

class htmlTable extends page {
   public function get() {
     $fileName = $_GET['fileName'];
     echo trim($fileName, "uploads/"). " was uploaded <br><br> The table is listed below, <br><br>";
     $heading = 1;
     $handle = fopen($fileName,"r");
     $table = '<table border="2">';
     while(($data = fgetcsv($handle))!=FALSE) {
        if ($heading == 1) {
         $table .= '<thead><tr>';
         foreach ($data as $value) {
          if(!isset($value))
	   $value = "&nbsp";
          else
           $table .= "<th>". $value ."</th>";
         }
     $table .=  '</tr></thead><tbody>';
        }
        else {
          $table .= '<tr>';
	  foreach ($data as $value) {
          if(!isset($value))
          $value = "&nbsp";
          else
          $table .=  "<td>". $value . "</td>";
          }
	  $table .= '</tr>';
       }
    $heading++; 
       
    }
     
     $table .= '</tbody></table>';
     $this->html .= $table;
     fclose($handle);
     //stringFunctions::printThis($this->html);
    }
}

class upload {
  public static function csvUpload($fileName,$tmpFileName) {
     $targetDir = "uploads/";
     $targetFile = $targetDir . $fileName;
     $source = pathinfo($targetFile,PATHINFO_EXTENSION);
     $fileName = $tmpFileName;
     move_uploaded_file($fileName,$targetFile);
     return $targetFile;
  }
}

class stringFunctions {
 static public function printThis($text) {
   print($text);
 }
}


?>
