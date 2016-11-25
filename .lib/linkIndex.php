<?php

include("_conexion.php");

header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1.
header("Pragma: no-cache"); // HTTP 1.0.
header("Expires: 0"); // Proxies.

if (isset($_POST['init']))
{
	$Respuesta = CLIENTE."|".SISTEMA."|".ALIAS."|".AUTOR;
	echo utf8_encode($Respuesta);
}

if (isset($_POST['ingresar']))
{
	$Conexion=conexionBD();

	if (!$Conexion)
		$Respuesta = ERRORCONEXION;
		else
		{
			$Respuesta = "";

			$Valores = explode("|",$_POST['ingresar']);
			$Valores[0] = trim($Valores[0]);
			//$Valores[1] = trim($Valores[1]);
			$Valores[2] = trim($Valores[2]);

			$Sentencia = "select count(a.nCodigo) as nroreg, a.nCodigo, a.cUsuario, a.nTipo, a.ofiId, b.ofiDesc
							from `" . BASEDATOS . "`.mUsuarios a
							inner join `" . BASEDATOS . "`.muniOficina b on b.ofiId = a.ofiId
							where cUsuario='".$Valores[0]."' and cPass='".$Valores[1]."' and cActivo=1  ";

			$NroReg = $Conexion -> query($Sentencia);
			while ($Fila = $NroReg -> fetch_row())
				for ($i=0; $i<6; $i++)
					$Respuesta .= $Fila[$i]."|";

			$Fila = explode("|",$Respuesta);

			if ($Fila[0]==1)
			{
				session_start();

				if($Fila[3] == 1)
				{
					$_SESSION['tipo'] =	'admin';
					$_SESSION['oficina'] = $Fila[4];
					$_SESSION['codigo'] = $Fila[1];
					$_SESSION['descofi'] = $Fila[5];
                    $Respuesta .= 'main-admin.html|';
				}
				else if($Fila[3] == 2)
				{
					$_SESSION['tipo'] = "usuario";
					$_SESSION['oficina'] = $Fila[4];
					$_SESSION['codigo'] = $Fila[1];
					$_SESSION['descofi'] = $Fila[5];
                    $Respuesta .= '|';
				}
				/*
				$Cadena = "'".$Fila[1]."', '".$Valores[2]."'";
				$Sentencia = "insert into `" . BASEDATOS . "`.tUsuarios (`nCodUsuario`, `dTimeStamp`) values (".$Cadena.")";
				$NroReg = $Conexion->query($Sentencia);
				*/
			}

			$Conexion -> close();
		}
		echo $Respuesta;
}

if(isset($_POST['salir']))
{
	session_start();
	unset($_SESSION['oficina']);
	unset($_SESSION['tipo']);
	unset($_SESSION['codigo']);
    unset($_SESSION['descofi']);
	session_destroy();
}

if(isset($_POST['sesion']))
{
	session_start();
	if(isset($_SESSION['tipo']))
	{
		$Conexion=conexionBD();

		if (!$Conexion){
			$Respuesta = ERRORCONEXION;
		}
		else {
			$Respuesta = "";

			$Sentencia = "select count(a.nCodigo) as nroreg, a.nCodigo, a.cUsuario, a.nTipo, a.ofiId, b.ofiDesc
							from `" . BASEDATOS . "`.mUsuarios a
							inner join `" . BASEDATOS . "`.muniOficina b on b.ofiId = a.ofiId
							where nCodigo='" . $_SESSION['codigo'] . "'  and cActivo=1";

			$NroReg = $Conexion->query($Sentencia);
			while ($Fila = $NroReg->fetch_row())
				for ($i = 0; $i < 6; $i++)
					$Respuesta .= $Fila[$i] . "|";

            if($_SESSION['tipo'] == 'admin') // 1 : es admin
            {
                $Respuesta .= 'main-admin.html|';
            }
            else if($_SESSION['tipo'] == 'usuario') // 2: usuario comun y silvestre
            {
                $Respuesta .= '|';
            }

			$Conexion -> close();
		}

		echo $Respuesta;
	}
	else
	{
		echo 'salir';
	}
}

?>