<?php
/*
version: 16.12.01
Autor: Ariel Balmaceda.
Compatible con PHP 5.
*/

class Controlador extends PrepararArchivo{
    protected $config;
	protected $vista;
	protected $url;
	
    // constructor
	function __construct(){
        $this->config=array();
		$this->vista=array();
		$this->url='';
	}
    
    //metodos
	protected function _cargarModelosBase($clases){
		foreach ($clases as $i=>$clase){
			$this->prepararModeloBase($this->config, $clase);
			
			if ($this->getErrorPreparar() == false){
				include_once($this->getArchivoPreparar());
			}else{
				exit('No se pudo cargar el archivo '.$clase);
			}
		}
	}
	
	protected function _cargarModelos($clases){
		foreach ($clases as $i=>$clase){
			$this->prepararModelo($this->config, $clase);
			
			if ($this->getErrorPreparar() == false){
				include_once($this->getArchivoPreparar());
			}else{
				exit('No se pudo cargar el archivo '.$clase);
			}
		}
	}
	
	protected function _cargarConfig($archivos){
		$config=array();
		
		foreach ($archivos as $i=>$archivo){
			$this->prepararConfig($this->config, $archivo);
			
			if ($this->getErrorPreparar() == false){
				include_once($this->getArchivoPreparar());
				
				$this->config=array_merge($this->config, $config);
			}else{
				exit('No se pudo cargar el archivo '.$archivo);
			}
		}
		
		//return $config;
	}
	
	protected function _cargarExtras($extras){
		foreach ($extras as $i=>$extra){
			$this->prepararExtra($this->config, $extra);
			
			if ($this->getErrorPreparar() == false){
				include_once($this->getArchivoPreparar());
			}else{
				exit('No se pudo cargar el archivo '.$extra);
			}
		}
	}
	
	protected function _cargarControladores($clases){
		foreach ($clases as $i=>$clase){
			$this->prepararControlador($this->config, $clase);
			
			if ($this->getErrorPreparar() == false){
				include_once($this->getArchivoPreparar());
			}else{
				exit('No se pudo cargar el archivo '.$clase);
			}
		}
	}
	
	//
    public function setConfig($config){
        $this->config=$config;
    }
    
    public function getConfig(){
        return $this->config;
    }
	
	public function setVista($vista){
        $this->vista=$vista;
    }
    
    public function getVista(){
        return $this->vista;
    }
	
	public function setHtml($html){
        $this->html=$html;
    }
    
    public function getHtml(){
        return $this->html;
    }
	
	public function setUrl($url){
        $this->url=$url;
    }
    
    public function getUrl(){
        return $this->url;
    }
	
	public function generarVista($html = ''){
		/**
		 * (solo para codigo anterior)
		 * $html: pagina web a mostrar. Puede ser string (1 pagina) o un array (varias paginas)
		 **/
		 
		if (trim($html) !== ''){
			//codigo anterior
			if (is_array($html)){
				foreach ($html as $i=>$pagina){
					$this->prepararVista($this->config, $pagina);
					
					if ($this->error_prep == false){
						include($this->getArchivoPreparar());
					}else{
						exit('No se pudo cargar el archivo ' . $pagina);
					}
				}//fin foreach
			}else{
				$this->prepararVista($this->config, $html);
				
				if ($this->error_prep == false){
					include($this->getArchivoPreparar());
				}else{
					exit('No se pudo cargar el archivo ' . $html);
				}
			}//fin if
		}else{
			//codigo nuevo
			$pagina = '';
			$pagina = ($this->vista['con_masterpage'] == 1)?$this->vista['masterpage']:
			$this->vista['pagina'];
			
			$this->prepararVista($this->config, $pagina);
			
			if ($this->error_prep === false){
				include($this->getArchivoPreparar());
			}else{
				exit('No se pudo cargar el archivo ' . $pagina);
			}
		}
	}
	
	public function cargarUnaVista($pagina){
		if (trim($pagina) !== ''){
			$this->prepararVista($this->config, $pagina);
						
			if ($this->getErrorPreparar() === false){
				include($this->getArchivoPreparar());
			}else{
				echo '<p>No se pudo cargar ' . $pagina . '</p>';
			}
		}//fin if
	}
	
	public function obtenerDirVistas(){
		$retorno=".".$this->config['dirvista'];
		
		if ($this->config["url_amigables"]) $retorno=substr($this->config['dirvista'], 1);
		
		return $retorno;
	}//fin function
	
	public function obtenerUrlVistas(){
		$retorno=$this->vista["base"].substr($this->config['dirvista'], 1);
		
		return $retorno;
	}//fin function
}//fin clase