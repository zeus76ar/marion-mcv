<?php
/*
version: 13.11.21.
Autor: Ariel Balmaceda.
Compatible con PHP 5.
*/

class ModeloBase extends ConexionBD{
	//propiedades
	protected $tabla; //nombre tabla relacionada con el modelo
	protected $campos; //campos de la tabla
	
	//constructor
	function __construct(){
		parent::__construct();
		
		$this->tabla='';
		$this->campos='';
	}
	
	//metodos
	public function setTabla($dato){
		$this->tabla=$dato;
	}
	
	public function getTabla(){
		return $this->tabla;
	}
	
	public function buscarDatos($condbusqueda="", $condorden="", $opclimite=""){
		/*
		$condbusqueda: aqui se definira la condicion de busqueda si se requiere.
		El formato es el siguiente (formato de MYSQL):
		'nomcampo operador valor And|Or nomcampo operador valor'.
		
		$condorden: aqui se define los campos que ordenaran la consulta.
		El formato es el siguiente (formato de MYSQL):
		'nomcampo1 [Desc|Asc], nomcampo2 [Desc|Asc], ...'.
		
		$opclimite: aqui se define las opciones para limitar la consulta.
		El formato es el siguiente (formato de MYSQL):
		'posicion, cantidad'.
		*/
		$datos=array();
		$this->error="";
		
		$comando = "SELECT * FROM ".$this->tabla;
		
		if (trim($condbusqueda)!="") $comando.=" WHERE ".trim($condbusqueda);
		if (trim($condorden)!="") $comando.=" ORDER BY ".trim($condorden);
		if (trim($opclimite)!="") $comando.=" LIMIT ".trim($opclimite);
		
		$resultado=$this->ejecutarComando($comando);
		if (trim($this->error) != "") return;
		
		while($fila=$this->obtenerFila($resultado)){
			$datos[]=$fila;
		}
		
		$this->liberarResultado($resultado);
		
		return $datos;
	}
	
	public function agregarDatos($datos){
		/*
		$datos: arreglo asociativo con los datos a agregar.
		Formato: $datos["nombre"], $datos["edad"] y $datos["peso"].
		*/
		$this->error="";
		$campos="";
		$valores="";
		
		foreach ($datos as $ind=>$dato){
			if (trim($campos)!="") $campos.=", ";
			if (trim($valores)!="") $valores.=", ";
			
			$campos.=$ind;
			$valores.="'".$dato."'";
		}
		
		$comando = "INSERT INTO ".$this->tabla." (".$campos.") VALUES (".$valores.")";
		
		$resultado=$this->ejecutarComando($comando);
	}
	
	public function modificarDatos($datos, $condbusqueda=""){
		/*
		$condbusqueda: aqui se definira la condicion de busqueda si se requiere.
		El formato es el siguiente (formato de MYSQL):
		'nomcampo operador valor And|Or nomcampo operador valor'.
		
		$datos: arreglo asociativo con los datos a agregar.
		Formato: $datos["nombre"], $datos["edad"] y $datos["peso"]. 
		*/
		$this->error="";
		
		$comando = "UPDATE ".$this->tabla." Set ";
		
		foreach ($datos as $ind=>$dato){
			if ($comando != ("UPDATE ".$this->tabla." Set ")) $comando.=", ";
			$comando.=($ind." = '".$dato."'");
		}
		
		if (trim($condbusqueda)!="") $comando.=" WHERE ".$condbusqueda;
		
		$resultado=$this->ejecutarComando($comando);
	}
	
	public function eliminarDatos($condbusqueda=""){
		/*
		$condbusqueda: aqui se definira la condicion de busqueda si se requiere.
		El formato es el siguiente (formato de MYSQL):
		'nomcampo operador valor And|Or nomcampo operador valor'.
		*/
		$this->error="";
		
		$comando = "DELETE FROM ".$this->tabla;
		if (trim($condbusqueda)!="") $comando.=" WHERE ".trim($condbusqueda);
		
		$resultado=$this->ejecutarComando($comando);
	}
	
	public function contarDatos($condbusqueda=""){
		/*
		$condbusqueda: aqui se definira la condicion de busqueda si se requiere.
		El formato es el siguiente (formato de MYSQL):
		'nomcampo operador valor And|Or nomcampo operador valor'.
		*/	
		$this->error="";
		
		$comando = "SELECT Count(*) as cantidad FROM ".$this->tabla;
		if (trim($condbusqueda)!="") $comando.=" WHERE ".trim($condbusqueda);
		
		$resultado=$this->ejecutarComando($comando);
		$fila=$this->obtenerFila($resultado);
		$this->liberarResultado($resultado);
		
		return $fila["cantidad"];	
	}
}//fin clase
?>
