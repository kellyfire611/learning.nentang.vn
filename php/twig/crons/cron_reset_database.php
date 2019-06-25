<?php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASSWORD', 'root');
define('DB_NAME', 'salomon');

$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME) or die('Xin lỗi, database không kết nối được.');
$conn->query("SET NAMES 'utf8'"); 
$conn->query("SET CHARACTER SET utf8");  
$conn->query("SET SESSION collation_connection = 'utf8_unicode_ci'"); 

function ExecSqlFile($conn, $filename) {
  if (!file_exists($filename)) {
    return false;
  }
  $querys = explode(";", file_get_contents($filename));
//   var_dump($querys);die;
  foreach ($querys as $q) {
    $q = trim($q);
    if (strlen($q)) {
      mysqli_query($conn, $q) or print_r($q);
    }      
  }
  return true;
}

// drop all table
$dropAllTableScriptFilePath = $_SERVER['DOCUMENT_ROOT'] . parse_url('/php/twig/db/drop_all_table.sql', PHP_URL_PATH);
ExecSqlFile($conn, $dropAllTableScriptFilePath);

// restore
$restoreScriptFilePath = $_SERVER['DOCUMENT_ROOT'] . parse_url('/php/twig/db/ecommerce_db.sql', PHP_URL_PATH);
ExecSqlFile($conn, $restoreScriptFilePath);

?>