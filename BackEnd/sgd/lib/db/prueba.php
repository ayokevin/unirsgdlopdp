<?php
require_once 'db.php';

// Aquí se puede utilizar la clase db y sus métodos públicos
$_dsb2;
$db = new db();
//$resultado = $db->setParametrosBD();
$resultado = $db->select("SELECT * FROM common.client");
?>
