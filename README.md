# Marion MCV
**Mini framwork PHP con MCV**

Si estas utilizando Apache server con mod_rewrite habilitado, podes usar
el .htaccess, cambiando las rutas donde corresponde. Esto permite usar
url amigables (www.sitio.net/controlador/accion/opcion).

Si mod_rewrite no esta habilitado, buscar el archivo config/conf_general.php y
cambiar la variable $config["url_incluir_index"] de 0 a 1. Esto permite usar
url amigables con index incluido (www.sitio.net/index.php/controlador/accion/opcion).

Para usar url estandar (parametros GET), buscar el archivo config/conf_general.php y
cambiar las variables config["url_amigables"] de 1 a 0 y
$config["url_incluir_index"] de 1 a 0. Esto permite usar
url tradicional (www.sitio.net/index.php?c=controlador&a=accion&op=opcion)