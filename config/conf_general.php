<?php
// rutas de acceso
$config["dirvista"] = "/vistas/";
$config["dircontrol"] = "/controladores/";
$config["dirmodelo"] = "/modelos/";
$config["dirmodelo_base"] = "/_priv/clases/";
$config["dirextras"] = "/extras/";

// info del sistema
$config["sist_nom"] = "Marion MCV";
$config["sist_desc"] = "Marion - Mini framework PHP con MCV.";

$config["sist_anios"] = "2014";
if (date("Y") != $config["sist_anios"]) $config["sist_anios"] .= "-" . date("Y");

$config["sist_desarrollo"] = "Ariel Balmaceda. Analista Programador.";
$config["sist_ver"] = "16.12";//año.mes
$config["sist_subver"] = "01";//dia

//usar sesion? 1: si, 0:no
$config["usar_sesion"] = 1;

// prefijo para variables sesion
$config["prefijo_sesion"] = "m-mcv_";

// estado de la aplicacion: 1-en produccion, 0-en desarrollo
$config["en_produccion"] = 0;

// valores por defecto para las url
$config["m_base"] = '';
$config["c_base"] = 'inicio';
$config["a_base"] = 'index';

//
$config["mostrar_errores"] = ($config["en_produccion"] == 1)?0:1;
$config["timezone"] = "America/Argentina/Buenos_Aires";
$config["url_amigables"] = 1;
$config["url_incluir_index"] = 0;
$config["charset"] = "utf-8";
$config["pag_error"] = "tpl/404.phtml";

// ingreso sistema
$config["sist_usuario"] = "d033e22ae348aeb5660fc2140aec35850c4da997";//admin
$config["sist_clave"] = "d033e22ae348aeb5660fc2140aec35850c4da997";//admin
$config["sist_tiempo"] = 0;

// para las vistas
$vista = array();

$vista["masterpage"] = "tpl/master.phtml";
$vista["con_masterpage"] = 1;//1: incluir pagina master, 0: no incluir pagina master
$vista["pagina"] = "";
$vista["incluir_css_extra"] = 0;
$vista["incluir_js_extra"] = 0;
$vista["titulo"] = "Inicio";
$vista["error"] = "";
$vista["info"] = "";
$vista["form_action"] = "";
?>