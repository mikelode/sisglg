window.onload= function()
{
    $.post('../.lib/linkIndex.php', {sesion: 'check'}, function(response){
        if(response != 'salir')
        {
            NroReg = response.split('|');

            if (NroReg[0]==1)
            {
                document.getElementById("user").innerHTML = NroReg[2].toUpperCase();
                document.getElementById("headofi").innerHTML = "<h3>" + NroReg[5] + "</h3>";
                cargarInit();
                cargarTimeStamp();
            }
        }
        else
        {
            document.getElementById("usuario").focus();
            cargarInit();
            cargarTimeStamp();
        }
    });
};

function salir()
{
    var xmlhttp;

    if (window.XMLHttpRequest)
    {// code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp=new XMLHttpRequest();
    }
    else
    {// code for IE6, IE5
        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    }

    xmlhttp.onreadystatechange=function()
    {
        //if (xmlhttp.readyState==4 && xmlhttp.status==200)
        location.href = '../';
    };

    xmlhttp.open("POST","../.lib/linkIndex.php",true);
    xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
    xmlhttp.send("salir=1");
}

function cargarModulo(desde)
{
	$("#main").load(desde);
}

function cargarInit()
{
    var xmlhttp;

    if (window.XMLHttpRequest)
    {// code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp=new XMLHttpRequest();
    }
    else
    {// code for IE6, IE5
        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    }

    xmlhttp.onreadystatechange=function()
    {
        if (xmlhttp.readyState==4 && xmlhttp.status==200)
        {
            Respuesta = xmlhttp.responseText.split('|');
            document.getElementById("cliente").innerHTML = Respuesta[0];
            document.getElementById("sistema").innerHTML = Respuesta[1] + " - " + Respuesta[2];
            document.getElementById("foot1").innerHTML = Respuesta[0] + " - " + Respuesta[1];
            document.getElementById("foot2").innerHTML = "Resolución mínima recomendada: 1024 x 768";
            //document.getElementById("foot3").innerHTML = "Programación y Diseño: " + Respuesta[3];
            document.getElementById("foot4").innerHTML = "Prohibida su reproducción total o parcial";
            document.getElementById("foot5").innerHTML = "Todos los Derechos Reservados © 2016";
        }
    };

    xmlhttp.open("POST","../.lib/linkIndex.php",true);
    xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
    xmlhttp.send("init=1");
}

function cargarTimeStamp()
{
    var meses = new Array ("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
    var dias = new Array("Domingo","Lunes","Martes","Miércoles","Jueves","Viernes","Sábado");
    var dia = new Date().getDate();
    var hora = new Date().getHours();
    var minuto = new Date().getMinutes();
    var segundo = new Date().getSeconds();

    if (dia<10)
        dia = "0" + dia;
    if (hora<10)
        hora = "0" + hora;
    if (minuto<10)
        minuto = "0" + minuto;
    if (segundo<10)
        segundo = "0" + segundo;

    var f = new Date();
    var timeStamp = dias[f.getDay()] + ", " + dia + " de " + meses[f.getMonth()] + " de " + f.getFullYear() + " - " + hora + ":" + minuto + ":" + segundo;
    document.getElementById("timestamp").innerHTML = timeStamp;
    setTimeout("cargarTimeStamp()",1000);
}