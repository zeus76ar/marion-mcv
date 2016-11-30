<?php
class Inicio extends Controlador{
	protected $menu;// array
	
	function __construct(){
		parent::__construct();
		
		$this->menu = array();
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
	public function index(){
		$ou = new Url();
		
		$param = array();
		$param['c'] = 'inicio';
		$param['a'] = 'index';
		
		//genero la url del menu con funciones del sistema
		$this->menu['Inicio'] = $ou->generarUrlMenu($param, $this->config);
		//genero la url del menu manualmente
		$this->menu['Menu 1'] = 'inicio/index';
		//
		$param['c'] = 'datos';
		$param['a'] = 'editar';
		$this->menu['Datos'] = $ou->generarUrlMenu($param, $this->config);
		
		//cargo las vista
		$this->vista["titulo"] = "Inicio";
		$this->vista["con_masterpage"] = 1;
		$this->vista["pagina"] = "html/inicio.phtml";
		$this->vista["incluir_css_extra"] = 1;
		$this->vista["incluir_js_extra"] = 0;
		$this->vista["info"] = "Ejemplo de info !!";
		$this->vista["error"] = "Ejemplo de error !!";
		
		$this->generarVista();
	}//fin function
}//fin class
?>
