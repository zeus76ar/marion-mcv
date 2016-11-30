<?php
/*
version: 16.08.09
Autor: Ariel Balmaceda.
Compatible con PHP 5.
*/

class ConexionBD {
	protected $nom_host;//string
	protected $nom_bd;//string
	protected $dns;//string
	protected $nom_usuario;//string
	protected $clave_usuario;//string
	protected $tipo; // string - 'mysqli', 'pdo', 'postgresql', 'sqlserver'
	protected $conexion; // objeto conexion(false al iniciar y al dar error);
	protected $error; //string
	protected $comando;//string
	protected $charset;//string
  
	function __construct(){
		$this->nom_host='';
		$this->nom_bd='';
		$this->dns='';
		$this->nom_usuario='';
		$this->clave_usuario='';
		$this->tipo='mysqli';
		$this->conexion=false;
		$this->error='';
		$this->charset='utf8';
	}
	
	public function setDatosConexion($dns, $host, $bd, $usuario, $clave=''){
		$this->dns=$dns;
		$this->nom_host=$host;
		$this->nom_bd=$bd;
		$this->nom_usuario=$usuario;
		$this->clave_usuario=$clave;
	}
	
	public function getDatosConexion(){
		$retorno=array();
		$retorno['host']=$this->nom_host;
		$retorno['bd']=$this->nom_bd;
		$retorno['dns']=$this->dns;
		$retorno['usuario']=$this->nom_usuario;
		$retorno['clave']=$this->clave_usuario;
		
		return $retorno;
	}
	
	public function getConexion(){
		return $this->conexion;
	}
	
	public function getError(){
		return $this->error;
	}
	
	public function setTipo($opcion='mysqli'){
		$this->tipo=strtolower($opcion);
	}
	
	public function getComando(){
		return $this->comando;
	}
	
	public function setCharset($charset){
		$this->charset=$charset;
	}
	
	public function getCharset(){
		return $this->charset;
	}
	
	protected function _verificarDatosConexion(){
		$this->error='';
		
		switch ($this->tipo){
			case 'mysqli':
				$retorno= (trim($this->nom_host) == '') || (trim($this->nom_bd) == '') ||
				(trim($this->nom_usuario) == '');
				
				$this->error=($retorno)?'Ingrese los datos para la conexion: Host, Base de datos y Usuario.':'';
				break;
			case 'pdo':
				$this->error=(trim($this->dns) == '')?'Ingrese el Dns para la conexion.':'';
				break;
			case 'postgresql':
				$retorno= (trim($this->nom_host) == '') || (trim($this->nom_bd) == '') ||
				(trim($this->nom_usuario) == '');
				
				$this->error=($retorno)?'Ingrese los datos para la conexion: Host, Base de datos y Usuario.':'';
				break;
			case 'sqlserver':
				$retorno= (trim($this->nom_host) == '') || (trim($this->nom_bd) == '');
				
				$this->error=($retorno)?'Ingrese los datos para la conexion: Host y Base de datos.':'';
				break;
		}
	}
	
	public function abrirConexion(){
		$this->_verificarDatosConexion();
		if (trim($this->error) != "") return;
		
		$this->error="";
		
		switch ($this->tipo){
			case 'mysqli':
				$this->conexion = new mysqli($this->nom_host, $this->nom_usuario,
				$this->clave_usuario, $this->nom_bd);
				
				$this->setError();
				
				if ($this->error == "") $this->conexion->set_charset($this->charset);
				
				break;
			case 'pdo':
				$this->conexion=new PDO($this->dns, $this->nom_usuario,
				$this->clave_usuario);
				
				$this->setError();
				break;
			case 'postgresql':
				$this->conexion = pg_connect("host=".$this->nom_host." dbname=".$this->nom_bd.
				" user=".$this->nom_usuario." password=".$this->clave_usuario);
				
				$this->setError();
				break;
			case 'sqlserver':
				$opciones["Database"] = $this->nom_bd;
				
				if (trim($this->nom_usuario) != "") $opciones["UID"] = $this->nom_usuario;
				
                if (trim($this->clave_usuario) != "") $opciones["PWD"] = $this->clave_usuario;
				
				$this->conexion = sqlsrv_connect($this->nom_host, $opciones);
				
				$this->setError();
				break;
		}
	}
	
	public function cerrarConexion(){
		if ($this->conexion === false) return;
		
		switch ($this->tipo){
			case 'mysqli':
				$this->conexion->close();
				break;
			case 'pdo':
				break;
			case 'postgresql':
				pg_close($this->conexion);
				break;
			case 'sqlserver':
				sqlsrv_close($this->conexion);
				break;
		}
	}
	
	protected function setError($error=""){
		$this->error="";
		
		if ($this->conexion === false){
			$this->error="La conexion NO es valida!!";
			//return;
		}
		
		if (trim($error) != ""){
			$this->error=$error;
			return;
		}
		
		switch($this->tipo){
			case 'mysqli':
				// opcion 1 (en algunas versiones de php no funciona )
				if ($this->conexion->connect_errno > 0){
					$this->error='('.$this->conexion->connect_errno.') '.
					$this->conexion->connect_error;
				}else{
					if ($this->conexion->errno > 0){
						$this->error='('.$this->conexion->errno.') '.
						$this->conexion->error;
					}
				}
				
				break;
			case 'pdo':
				$tempo=$this->conexion->errorInfo();
				
				if (trim($tempo[1]) != ''){
					$this->error='('.$tempo[1].') '.$tempo[2];
				}
				
				break;
			case 'postgresql':
				$this->error = pg_last_error($this->conexion);
				break;
			case 'sqlserver':
				$errores = sqlsrv_errors();
				
				$this->error = $errores[0]["code"]." - ".$errores[0]["message"];
				
				if (trim($this->error) == "-") $this->error = "";
				/*
				foreach ( $errors as $error )
				{  
					echo "SQLSTATE: ".$error['SQLSTATE']."<br/>";  
					echo "Code: ".$error['code']."<br/>";  
					echo "Message: ".$error['message']."<br/>";  
				}
				*/
				break;
		}
	}
	
	public function ejecutarComando($comando){
		if ($this->conexion === false) $this->abrirConexion();
		
		if (trim($this->error) != "") return false;
		
		$this->comando=$comando;
		
		switch ($this->tipo){
			case 'mysqli':
				$resultado = $this->conexion->query($comando);
				break;
			case 'pdo':
				$resultado = $this->conexion->query($comando);
				break;
			case 'postgresql':
				$resultado = pg_query($this->conexion, $comando);
				break;
			case 'sqlserver':
				$resultado = sqlsrv_query($this->conexion, $comando);
				break;
		}
		
		$this->setError();
		
		return $resultado;
	}
	
	public function obtenerFila($resultado){
		if ($resultado === false) return;
		
		switch($this->tipo){
			case 'mysqli':
				$fila=$resultado->fetch_assoc();
				break;
			case 'pdo':
				$fila=$resultado->fetch(PDO::FETCH_ASSOC);
				break;
			case 'postgresql':
				$fila=pg_fetch_array($resultado, null, PGSQL_ASSOC);
				break;
			case 'sqlserver':
				$fila = sqlsrv_fetch_array($resultado);
				break;
		}
		
		return $fila;
	}
	
	public function liberarResultado(&$resultado){
		if ($resultado === false) return;
		
		switch ($this->tipo){
			case 'mysqli':
				$resultado->free();
				break;
			case 'pdo':
				break;
			case 'postgresql':
				pg_free_result($resultado);
				break;
			case 'sqlserver':
				sqlsrv_free_stmt($resultado);
				break;
		}
	}
}/// fin clase
?>
