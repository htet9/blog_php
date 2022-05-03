<?php

define ('MYSQL_USER', 'root');
define ('MYSQL_PASSWORD', '');
define ('MYSQL_DATABASE', 'blog_app');
define ('MYSQL_HOST', 'localhost');

$options = array(
  PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
);

$pdo = new PDO(
  'mysql:host='.MYSQL_HOST.';dbname='.MYSQL_DATABASE,MYSQL_USER,MYSQL_PASSWORD,$options
);

?>
