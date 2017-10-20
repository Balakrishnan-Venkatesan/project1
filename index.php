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

/*class uploadform extends page {

 public function get()
 {
    $form = '<form action="index.php?page=uploadform" method="post" enctype="multipart/form-data">';
    $form .= '<input type="file" name="chooseFile" id="chooseFile">';
    $form .= '<input type="submit" value="Upload Image" name="submit">';
    $form .= '</form> ';
    $this->html .= '<h1>Upload Form</h1>';
    $this->html .= $form;
 }*/

 public function post() {
  $targetDir = "uploads/";
  print_r($_FILES);
  $targetFile = $targetDir . $_FILES["chooseFile"]["name"];
  $source = pathinfo($targetFile,PATHINFO_EXTENSION);
  if(isset($_POST["submit"])) {
   $fileName = $_FILES["chooseFile"]["temp_name"];
   move_uploaded_file($fileName,$targetFile);
   echo 'file uploaded';
  }
 }

}

class stringFunctions {
 static public function printThis($text) {
    print($text);
 }
}

class htmlTable extends page {}

?>			   
