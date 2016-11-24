<?php
$config["rutabase"] = str_replace("\\", "/", realpath(dirname(__FILE__)));

// archivo de configuracion base
require($config["rutabase"] . "/config/conf_general.php");
require($config["rutabase"] . "/config/conf_rutas.php");

if ($config["usar_sesion"] == 1) session_start();

ini_set("display_errors", $config["mostrar_errores"]);

date_default_timezone_set($config["timezone"]);

header($config["charset"]);

$protocolo = 'http';
if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') $protocolo = 'https';

$vista["base"] = $protocolo . "://" . $_SERVER["HTTP_HOST"] .
dirname($_SERVER["SCRIPT_NAME"]) . "/";

//
require($config["rutabase"] . $config["dirmodelo_base"] . "base/url.class.php");
require($config["rutabase"] . $config["dirmodelo_base"] . "base/preparar_archivo.class.php");
require($config["rutabase"] . $config["dirmodelo_base"] . "base/controlador.class.php");

//preparo las variables $_GET
$ou = new Url();
$url_array = array();

$url_array = $ou->revisarUrl($config, $rutas);
unset($ou);
    
// cargo el controlador correspondiente
$controlador = ((isset($_GET["m"]))?($_GET["m"]."/"):'') . $_GET["c"];

$opa = new prepararArchivo();
$opa->prepararControlador($config, $controlador);

if ($opa->getErrorPreparar() == false){
    include($opa->getArchivoPreparar());
    
    $comando = '$ctr = new ' . ucwords($_GET['c']) . '();';
    eval($comando);
    
    if (method_exists($ctr, $_GET['a'])){
        $ctr->setConfig($config);
        $ctr->setVista($vista);
        $ctr->setUrl($url_array);
        
        unset($config, $vista, $url_array, $rutas);
        
        $metodo = $_GET['a'];
        
        $ctr->$metodo();
    }else{
        $vista['error'] = 'El metodo "' . $_GET['a'] . '" NO existe en el controlador "' .
        $_GET['c'] . '"';
        
        if (isset($_GET['m'])) $vista['error'] .= ' del modulo "' . $_GET['m'] . '"';
        
        require($config["rutabase"] . $config["dirvista"] . $config["pag_error"]);
    }
}else{
    $vista['error'] = 'El controlador "' . $_GET['c'] . '" NO existe';
    
    if (isset($_GET['m'])) $vista['error'] .= ' en el modulo "' . $_GET['m'] . '"';
    
    require($config["rutabase"] . $config["dirvista"] . $config["pag_error"]);
}
?>