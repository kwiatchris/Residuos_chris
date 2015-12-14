<?php
header('Access-Control-Allow-Origin: *'); 
include_once 'Control/BD/BD.php';
require_once 'Utils.php';
	class PosicionUsuario{
		private $idPosicion;
	    private $id_usuario;
	    private $titulo;
		private $latitud;
	    private $longitud;
	    private $fecha;

	    public function __construct($idPos,$idUsu,$titulo,$lat,$long,$fecha){

	    	$this->idPosicion=$idPos;
		    $this->id_usuario=$idUsu;
		    $this->titulo=$titulo;
			$this->latitud=$lat;
		    $this->longitud=$long;
		    $this->fecha=$fecha;
	    }

	    //************************
		//SECCION GETTER Y SETTERS
		//************************

		//ID_POSICIÓN
		public function setIdPosicion($idPos)
		{
			$this->id_usuario=$idusu;
		}
		public function getIdPosicion()
		{
			return $this->id_usuario;
		}

		//ID_USUARIO
		public function setIdUsuario($idPos)
		{
			$this->id_usuario=$idusu;
		}
		public function getIdUsuario()
		{
			return $this->id_usuario;
		}

		//TITULO
		public function setTitulo($titulo)
		{
			$this->titulo=$titulo;
		}
		public function getTitulo()
		{
			return $this->titulo;
		}

		//LATITUD
		public function setLatitud($Lat)
		{
			$this->latitud=$Lat;
		}	
		public function getLatitud()
		{
			return $this->latitud;
		}

		//LONGITUD
		public function setLongitud($Long)
		{
			$this->longitud=$Long;
		}	
		public function getIdLongitud()
		{
			return $this->longitud;
		}

		//FECHA
		public function setFecha($Fecha)
		{
			$this->fecha=$Fecha;
		}	
		public function getFecha()
		{
			return $this->fecha;
		}

		public static function nuevaPosicion($id_usuario,$titulo,$latitud,$longitud){
			$retVal=true;
			//Utils::escribeLog("usu: ".$id_usuario." Titulo: ".$titulo." LAT: ".$latitud." LONG: ".$longitud,"debug");
			try{
				//si la cuenta da 0 insertar
				$sql="INSERT INTO posicion(id_usuario,titulo,latitud,longitud)VALUES(:id,:titulo,:lat,:long)";			
				$comando=Conexion::getInstance()->getDb()->prepare($sql);
				$comando->execute(array(":id"=>$id_usuario,
					":titulo"=>$titulo,
					":lat"=>$latitud,
					":long"=>$longitud));

			}catch(PDOException $e){
				Utils::escribeLog("Error: ".$e->getMessage()." | Fichero: ".$e->getFile()." | Línea: ".$e->getLine()." [Error al insertar posicion]","debug");
				$retVal=false;
				return $retVal;
			}
			
			$cuenta=$comando->rowCount();

			if($cuenta==0)//si no ha afectado a ninguna línea...
			{
				$retVal=false;				
			}
			return $retVal;

			}
		public  static function guardar($disId,$vol,$fuego){
			echo "guardar";
			 $timezone = date_default_timezone_get();
             $date = date('m/d/Y h:i:s a', time());
              $date = date('Y-m-d H:i:s');
              $sql="SELECT `Dispositivo_Id` FROM `Dis-datos` WHERE `Dispositivo_Id` = :Id  LIMIT 1";			
				$existe_disId=Conexion::getInstance()->getDb()->prepare($sql);
				$existe_disId->execute(array(":Id"=>$disId));
				//check if disID exist!!!
				$result = $existe_disId->fetchAll(PDO::FETCH_ASSOC);
							
		if($result){
			//$stmt=$var->query("INSERT INTO `Dis-datos` (`Dis-datos_Id`, `Dispositivo_Id`, `Volumen`, `Fuego`, `Fecha`, `otro`) VALUES (NULL, '$disId', '$vol', '$fuego', '$date', '');");
		$sql="INSERT INTO `Dis-datos` (`Dis-datos_Id`, `Dispositivo_Id`, `Volumen`, `Fuego`, `Fecha`, `otro`) VALUES (NULL, '$disId', '$vol', '$fuego', '$date', '');";			
				$comando=Conexion::getInstance()->
				getDb()->query($sql);
					echo  $output = "<script>console.log( ' OK' );</script>";	print_r("fila insertada");
					
		}else{
			echo "los siento pero el numero de dispositivo esta incorecto";
		}
	}
		public static function getPosicionesByUsuario(){
			$posiciones=array();
			
			try{
				//si la cuenta da 0 insertar
				$sql="SELECT `Latitud`,`Longitud`,`Barrio` FROM Dispositivos";			
				$comando=Conexion::getInstance()->getDb()->prepare($sql);
				$comando->execute();
				
			}catch(PDOException $e){
				Utils::escribeLog("Error: ".$e->getMessage()." | Fichero: ".$e->getFile()." | Línea: ".$e->getLine()." [Error al insertar posicion]","debug");
				$posiciones=null;
				return $posiciones;
			}
			
			$cuenta=$comando->rowCount();

			if($cuenta==0)//si no ha afectado a ninguna línea...
			{
				$posiciones=null;
				return $posiciones;			
			}
			$posiciones=$comando->fetchAll(PDO::FETCH_ASSOC);
			return $posiciones;
		}
	} 
?>