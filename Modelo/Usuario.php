<?php
include_once 'Control/BD/BD.php';
include_once 'CorreoUser.php';
require_once 'Utils.php';
 class Usuario{
	
	private $mId_Cliente;
	private $mNombre_Empresa;
	private $mNombre;
	private $mApellido1;
	private $mApellido2;
	private $mPassword;
	private $mDireccion;
	private $mCiudad;
	private $mTelefono;
	private $mEmail;
	private $mValidado;
	private $mFecha;
		
	//Constructor de la clase
	public function __construct()
	{
		$this->mId_Cliente="";
		$this->mNombre_Empresa="";
		$this->mNombre="";
		$this->mApellido1="";
		$this->mApellido2="";
		$this->mPassword="";
		$this->mDireccion="";
		$this->mCiudad="";
		$this->mTelefono="";
		$this->mEmail="";
		$this->mValidado="";
		$this->mFecha="";
	}

	//************************
	//SECCION GETTER Y SETTERS
	//************************

	//ID_USUARIO
	public function setIdUsuario($idUsu)
	{
		$this->mId_Cliente=$idUsu;
	}
	public function getIdUsuario()
	{
		return $this->mId_Cliente;
	}
	//mNombre_Empresa
	public function setNombre_Empresa($nom_empr)
	{
		$this->mNombre_Empresa=$nom_empr;
	}
	public function getNombre_Empresa()
	{
		return $this->mNombre_Empresa;
	}
	//NOMBRE
	public function setNombreUsuario($nom)
	{
		$this->mNombre=$nom;
	}
	public function getNombreUsuario()
	{
		return $this->mNombre;
	}

	//APELLIDO 1
	public function setApellido1($ape1)
	{
		$this->mApellido1=$ape1;
	}
	public function getApellido1()
	{
		return $this->mApellido1;
	}

	//APELLIDO 2
	public function setApellido2($ape2)
	{
		$this->mApellido2=$ape2;
	}
	public function getApellido2()
	{
		return $this->mApellido2;
	}
	//mPassword
	public function setmPassword($pass)
	{
		$this->mPassword=$pass;
	}
	public function getmPassword()
	{
		return $this->mPassword;
	}
	//Direccion
	public function setDireccion($dir)
	{
		$this->mDireccion=$dir;
	}
	public function getDireccion()
	{
		return $this->mDireccion;
	}	
	//mCiudad
		public function setmCiudad($ciud)
	{
		$this->mCiudad=$ciud;
	}
	public function getmCiudad()
	{
		return $this->mCiudad;
	}
	//mTelefono
	public function setmTelefono($tel)
	{
		$this->mTelefono=$tel;
	}
	public function getmTelefono()
	{
		return $this->mTelefono;
	}
	//EMAIL
	public function setEmail($email)
	{
		$this->mEmail=$email;
	}
	public function getEmail()
	{
		return $this->mEmail;
	}	

	//VALIDADO
	public function setValidado($vali)
	{
		$this->mValidado=$vali;
	}	
	public function getValidado()
	{
		return $this->mValidado;
	}

	//FECHA
	public function getFecha()
	{
		return $this->mFecha;
	}

	//******************************
	//SECCION INTERACCIÓN CON BBDD *
	//******************************
	public static function nuevoUsuario($nom_empresa,$nom,$app,$cont,$email,$dirr,$tel){

		//return $this->getIdUsuario();
		$retVal=1;//0->KO / 1->OK / 2->Existe el usuario/3-> Usuario insertado correo KO
		//Utils::escribeLog("Inicio nuevoUsuario","debug");
		 $timezone = date_default_timezone_get();
         $date = date('m/d/Y h:i:s a', time());
         $date = date('Y-m-d H:i:s');
		try{
			//Antes de insertar comprobar que no exista el mismo id_usuario y correo
			$sql="SELECT Client_Id FROM Clientes WHERE Nombre_empresa=:nom_emp or Email=:ema";
			$comando=Conexion::getInstance()->getDb()->prepare($sql);
			$comando->execute(array(":nom_emp"=>$nom_empresa,":ema"=>$email));

		}catch(PDOException $e){
			//Utils::escribeLog("Error: ".$e->getMessage()." | Fichero: ".$e->getFile()." | Línea: ".$e->getLine()." [Usuario o email existentes]","debug");
			$retVal=0;
			return $retVal;
		}		

		$cuenta=$comando->rowCount();

		if($cuenta!=0)
		{
			Utils::escribeLog("nom_empresa y/o correo  existentes en la BBDD -> KO","debug");
			$retVal=2;
			return $retVal;
		}		
		//Utils::escribeLog("IdUsuario y/o correo no existentes en la BBDD -> OK","debug");
		try{
			//si la cuenta da 0 insertar
			$sql="INSERT INTO Clientes(Nombre_empresa,Nombre,Apelido,Password,Email,Direccion,Telefono,Fecha_crear)VALUES
			(:nom_empresa,:nombre,:ape,:contra,:email,:dir,:tel,:fecha)";
			//INSERT INTO `Clientes`(`Client_Id`, `Nombre`, `Apelido`, `Password`, `Direccion`, `Ciudad`, `Telefono`, `Email`, `Comprado`, `User_key`, `Fecha_creacion`, `otra`, `NIF`, `fecha_modif`)
			$key=Utils::random_string(50);
			$comando=null;
			$comando=Conexion::getInstance()->getDb()->prepare($sql);
			$comando->execute(array(":nom_empresa"=>$nom_empresa,
				":nombre"=>$nom,
				":ape"=>$app,
				":contra"=>md5($cont),
				":email"=>$email,
				":dir"=>$dirr,
				":tel"=>$tel,
				":fecha"=>$date,
				));

		}catch(PDOException $e){
			//Utils::escribeLog("Error: ".$e->getMessage()." | Fichero: ".$e->getFile()." | Línea: ".$e->getLine()." [Error al insertar usuario]","debug");
			$retVal=0;
			return $retVal;
		}
		
		$cuenta=$comando->rowCount();

		if($cuenta==0)//si no ha afectado a ninguna línea...
		{
			$retVal=0;
			return $retVal;
		}
		//Utils::escribeLog("Usuario insertado en la BBDD -> OK","debug");
		//Utils::escribeLog("Pre-envio correo","debug");
		//Enviar correo
		//$CorreoUse=new CorreoUser();
		//$result=$CorreoUser->enviarCorreoRegistro($nom_empresa,$nom,$app,$cont,$email,$key);
		//$result=$CorreoUse->email_confirm($nom_empresa,$key,$email);
		$result=CorreoUser::email_confirm($nom_empresa,$key,$email);
		if(!$result){
			//Utils::escribeLog("Error: ".$e->getMessage()." | Fichero: ".$e->getFile()." | Línea: ".$e->getLine()." [Error al enviar correo]","debug");
			$retVal=3;
			return $retVal;
		}
		//Utils::escribeLog("Correo enviado OK","debug");			
		return $retVal;	//si todo va OK deveria devolver 1	
	}

	public static function validarUsuario($correo,$key){
		$retVal=1;//0-> Fail , 1->OK, 2->Ya validado 
		
		try{
			//Comprobar que el usuario no este validado.
			$sql="SELECT id_usuario,nombre, apellido1,apellido2,email,key_usuario,validado FROM usuario WHERE email LIKE :correo and key_usuario LIKE :key";
			$comando=Conexion::getInstance()->getDb()->prepare($sql);
			$comando->execute(array(":correo"=>$correo,":key"=>$key));

		}catch(PDOException $ex){
			Utils::escribeLog("Error: ".$e->getMessage()." | Fichero: ".$e->getFile()." | Línea: ".$e->getLine()." [Error al buscar el usuario para validar]","debug");
			$retVal=0;
			return $retVal;
		}
		//comprobar filas
		$cuenta=$comando->rowCount();

		if($cuenta==0){			
		
			Utils::escribeLog("No hay usuario para validar","debug");
			$retVal=0;
			return $retVal;
		}
		//comprobar el estado de validado
		$result=$comando->fetch(PDO::FETCH_ASSOC);
		$id_usuario=$result['id_usuario'];
		$nombre=$result['nombre'];
		$ape1=$result['apellido1'];
		$ape2=$result['apellido2'];

		if($result['validado']==='1'){
			Utils::escribeLog("Ya está validado","debug");
			$retVal=2;
			return $retVal;
		}
		//actualizar campo validado
		try{
			$sql="UPDATE usuario SET validado='1' WHERE id_usuario LIKE :id";
			$comando=Conexion::getInstance()->getDb()->prepare($sql);
			$comando->execute(array(':id'=>$id_usuario));

		}catch (PDOException $e){
			$retVal=0;
			Utils::escribeLog("Error: ".$e->getMessage()." | Fichero: ".$e->getFile()." | Línea: ".$e->getLine()." [Error al buscar el usuario para validar]","debug");
			return $retVal;

		}

		//ver lineas afectadas
		if($comando->rowCount()==0){
			Utils::escribeLog("Error al validar","debug");
			$retVal=0;
			return $retVal;
		}

		//enviar correo de validado OK
		//$CorreoUser=new CorreoUser();
		//$result=$CorreoUser->enviarConfirmValidacion($nombre,$ape1,$ape2="",$correo);

		if(!$result){
			//Utils::escribeLog("Error: ".$e->getMessage()." | Fichero: ".$e->getFile()." | Línea: ".$e->getLine()." [Error al enviar correo]","debug");
			$retVal=3;
			return $retVal;
		}
		Utils::escribeLog("Correo enviado OK","debug");			
		return $retVal;	//si todo va OK deveria devolver 1
	}

	public static function comprobarUsuario($idUsuario,$pass){
		$retVal=1;
		Utils::escribeLog('inicio comprobar usuario','debug');

		//comprobar en bd
		
		try{
			$sql="SELECT id_usuario,nombre,apellido1,validado FROM usuario WHERE id_usuario LIKE :id AND pass LIKE :pass";
			$comando=Conexion::getInstance()->getDb()->prepare($sql);
			$comando->execute(array(":id"=>$idUsuario,":pass"=>md5($pass)));

		}catch(PDOException $e){
			Utils::escribeLog("Error: ".$e->getMessage()." | Fichero: ".$e->getFile()." | Línea: ".$e->getLine()." ","debug");
			$retval=0;
			return $retVal;

		}

		$cuenta=$comando->rowCount();
		if($cuenta==0){
			$retVal=0;
			return $retVal;
		} 

		$datos=$comando->fetch(PDO::FETCH_ASSOC);
		if($datos['validado']==0){
			$retVal=2;
			return $retVal;			
		}else{
			$_SESSION['id_usuario']=$datos['id_usuario'];
			$_SESSION['nombre']=$datos['nombre'];
			$_SESSION['apellido']=$datos['apellido1'];
			return $retVal;
		}		
	}
}
?>