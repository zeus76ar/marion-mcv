<?php
class Datos extends Controlador{
	protected $otv; //tipo objeto
    protected $ou;//tipo entero
    protected $datos;//tipo array
    protected $dato;//tipo array
    
	function __construct(){
		parent::__construct();
        
        $this->datos = array();
        $this->dato = array();
	}
	
	protected function _prepararDatos(){
        $datos=array();

		$datos["texto"] = addslashes($_POST["texto"]);

        return $datos;
    }

	protected function _validarDatos($datos){
		$retorno="";

		if (isset($datos["texto"])){
			$condbusqueda="texto Like '" . $datos["texto"] . "'";

			if ($_POST["hid"] !== ""){
				$condbusqueda .= " And codigo <> ".$_POST["hid"];
			}

			if ($this->otv->contarDatos($condbusqueda) > 0){
				$retorno = "El Texto ya existe. Ingrese otro.";
			}
		}

		return $retorno;
	}
	
	protected function _prepararObjetos(){
		$configs = array();
		$configs[] = 'conf_mysql.php';

		$this->_cargarConfig($configs);
		
		$clases = array();
		$clases[] = 'conexionbd.class.php';
        $clases[] = 'modelobase.class.php';

		$this->_cargarModelosBase($clases);

		$clases = array();

		$clases[] = 'tablas/tblvarios.class.php';

		$this->_cargarModelos($clases);

		//
        $this->ou = new Url();
        
		$this->otv = new tblVarios();
		$this->otv->setTipo($this->config['bd_tipo']);
		$this->otv->setDatosConexion('', $this->config['mysql_host'], $this->config['mysql_bd'],
		$this->config['mysql_usuario'], $this->config['mysql_clave']);
	}
	
    protected function _mostrarInfoError(){
        $arch_info_error = 'tpl/info_error.phtml';
        $this->prepararVista($this->config, $arch_info_error);
        
        if ($this->getErrorPreparar() === false){
            include($this->getArchivoPreparar());
        }else{
            echo '<p>No se pudo cargar ' . $arch_info_error . '</p>';
        }
    }
    
	//
    public function editar(){
		$this->_prepararObjetos();
		
        //
        $condbusqueda = "";
		$oplimite = "";
		$condorden = "codigo Desc";
		
		$this->datos = $this->otv->buscarDatos($condbusqueda, $condorden, $oplimite);
		
		if (trim($this->otv->getError()) !== ""){
			$this->vista["error"] = trim($this->otv->getError()) . "<br />";
		}else{
			if (count($this->datos)  < 1) $this->vista["info"] = "NO se encontraron datos.";
		}

		$this->vista["total_reg"] = $this->otv->contarDatos($condbusqueda);

		$this->otv->cerrarConexion();

		//cargo las vistas
        $param_get = array();
        $param_get['c'] = $_GET['c'];
        
		$param_get['a'] = 'eliminar';
		$this->vista["url_eliminar"] = $this->ou->generarUrlMenu($param_get, $this->config);
		
		$param_get['a'] = 'nuevo';
		$this->vista["url_nuevo"] = $this->ou->generarUrlMenu($param_get, $this->config);
        
        $param_get['a'] = 'modificar';
		$this->vista["url_modificar"] = $this->ou->generarUrlMenu($param_get, $this->config);
        
        $param_get['a'] = 'eliminar';
		$this->vista["url_eliminar"] = $this->ou->generarUrlMenu($param_get, $this->config);
        
		$this->vista["titulo"] = "Datos - Editar";
		$this->vista["con_masterpage"] = 1;
		$this->vista["pagina"] = "html/datos_editar.phtml";
		$this->vista["css_extra"] = "html/extra_css/datos_editar_css.phtml";
		$this->vista["js_extra"] = "html/extra_js/datos_editar_js.phtml";
		
		$this->generarVista();
    }
	
	public function nuevo(){
		$this->_prepararObjetos();
        
        //cargo las vistas
        $param_get = array();
        $param_get['c'] = $_GET['c'];
        
		$param_get['a'] = 'guardar';
		$this->vista["url_guardar"] = $this->ou->generarUrlMenu($param_get, $this->config);
        
		$this->vista["titulo"] = "Datos - Nuevo";
		$this->vista["con_masterpage"] = 1;
		$this->vista["pagina"] = "html/datos.phtml";
		$this->vista["css_extra"] = "html/extra_css/datos_css.phtml";
		$this->vista["js_extra"] = "";
		
		$this->generarVista();
        
    }//fin function
	
	public function modificar(){
		$this->_prepararObjetos();
		
		//
		if (isset($this->url[2])) $_GET["id"] = $this->url[2];

		$condbusqueda = "codigo = " . $_GET["id"];
		$oplimite = "";
		$condorden = "";

		$this->dato = $this->otv->buscarDatos($condbusqueda, $condorden, $oplimite);

		if (trim($this->otv->getError()) !== ""){
			$this->vista["error"] = trim($this->otv->getError())."<br />";
		}else{
			if (count($this->dato) < 1){
				$this->vista["info"] = "NO se encontr&oacute; el dato";
			}
		}

		$this->otv->cerrarConexion();

        //cargo las vistas
		$param_get = array();
        $param_get['c'] = $_GET['c'];
        
		$param_get['a'] = 'guardar';
		$this->vista["url_guardar"] = $this->ou->generarUrlMenu($param_get, $this->config);
        
		$this->vista["titulo"] = "Datos - Modificar";
		$this->vista["con_masterpage"] = 1;
		$this->vista["pagina"] = "html/datos.phtml";
		$this->vista["css_extra"] = "html/extra_css/datos_css.phtml";
		$this->vista["js_extra"] = "";
		
		$this->generarVista();
    }//fin function
	
	public function guardar(){
        if (count($_POST) > 0){
            $this->_prepararObjetos();

            //
			foreach ($_POST as $i=>$valor){
				$_POST[$i] = trim($valor);
			}

            $datos = $this->_prepararDatos();

			$this->vista["error"] = $this->_validarDatos($datos);

            if ($this->vista["error"] === ""){
				if ($_POST["hid"] === ""){
					$this->otv->agregarDatos($datos);
				}else{
					$this->otv->modificarDatos($datos, "codigo = " . $_POST["hid"]);
				}

                if (trim($this->otv->getError()) !== ""){
					$this->vista["error"] = trim($this->otv->getError());
				}else{
                    $this->vista["info"] = 'Guardar datos... OK';
                }
				
				$this->otv->cerrarConexion();
            }
        }else{
            $this->vista["error"] = "Faltan los campos POST";
        }//fin if
        
        //
        $this->vista["titulo"] = "Datos - Resultado";
		$this->vista["con_masterpage"] = 1;
		$this->vista["pagina"] = "html/datos_resultado.phtml";
		$this->vista["css_extra"] = "html/extra_css/datos_resultado_css.phtml";
		$this->vista["js_extra"] = "";
		
		$this->generarVista();
    }//fin function
	
	public function eliminar(){
		$this->_prepararObjetos();
        
        if (isset($this->url[2])) $_GET["id"] = $this->url[2];
        
		$consulta = "codigo = " . $_GET["id"];
        
        $this->otv->eliminarDatos($consulta);
        
        if (trim($this->otv->getError()) === ""){
            $this->vista["info"] = 'Eliminar dato... OK';
        }else{
            $this->vista["error"] = trim($this->otv->getError());
        }
			
		$this->otv->cerrarConexion();
        
        //
        $this->vista["titulo"] = "Datos - Resultado";
		$this->vista["con_masterpage"] = 1;
		$this->vista["pagina"] = "html/datos_resultado.phtml";
		$this->vista["css_extra"] = "html/extra_css/datos_resultado_css.phtml";
		$this->vista["js_extra"] = "";
		
		$this->generarVista();
	}//fin function
}//fin clase
?>