function tieneNumeros(texto){
	
	var numeros="0123456789";
  
   for(i=0; i<texto.length; i++){
      if (numeros.indexOf(texto.charAt(i),0)!=-1){
         return true;
      }
   }
   return false;
}


function tieneLetras(texto){
   var letras="abcdefghyjklmnñopqrstuvwxyzABCDEFGHIJKLMNÑOPQRSTUVWXYZ";
   
   texto = texto.toLowerCase();
   for(i=0; i<texto.length; i++){
      if (letras.indexOf(texto.charAt(i),0)!=-1){
         return true;
      }
   }
   return false;
}

function validarAlfanumerico(valor)
{
	if ((tieneNumeros(valor)==true)&&(tieneLetras(valor)==true))
		return true;
	else
		return false;
}




function validarEmail(valor){
    re=/^[_a-z0-9-]+(.[_a-z0-9-]+)*@[a-z0-9-]+(.[a-z0-9-]+)*(.[a-z]{2,3})$/
    if(!re.exec(valor))    {
        return false;
    }else{
        return true;
    }
}



function validarDecimal(numero) 
{		
	if (numero=="")
		return 0;
	patron = /^([0-9])*\.?[0-9]?[0-9]?$/ ;
	patron2 = /^([0-9])*\,?[0-9]?[0-9]?$/ ;
	
	if ((patron.test(numero)==false)&&(patron2.test(numero)==false))
		return 0;
	else
		return 1;		
}



function validarDecimal2(numero) 
{		
	if (numero=="")
		return 0;
	patron = /^([0-9])*\.?[0-9]?[0-9]?$/ ;
	
	if ((patron.test(numero)==false)&&(patron2.test(numero)==false))
		return 0;
	else
		return 1;		
}



function validarEntero(numero) 
{		
	if (numero=="")
		return 0;
	var patron = /^\d*$/;
	
	if (patron.test(numero)==false)
		return 0;
	else
		return 1;		
}


function posicionElemento(id_elemento)
{
	element=document.getElementById(id_elemento);
	
	var y = 0;
 	var x = 0;
	while (element.offsetParent) 
	{
    	x += element.offsetLeft;
    	y += element.offsetTop;
    	element = element.offsetParent;
  	}
	
	return {top:y,left:x};
}



function refrescar(url)
{
	document.location.href=url;
}



function ltrim(s) {
	return s.replace(/^\s+/, "");
}

function rtrim(s) {
	return s.replace(/\s+$/, "");
}

function trim(s) {
	return rtrim(ltrim(s));
}

function now()
{
	fecha = new Date();
	hora=""+fecha.getFullYear()+"-"+fecha.getMonth()+"-"+fecha.getDate()+" "+fecha.getHours()+":"+fecha.getMinutes()+":"+fecha.getSeconds();
	return hora;
}


function loginML(id_ml)
{
	window.open("http://auth.mercadolibre.com/authorization?client_id="+id_ml+"&response_type=code","login_ml","toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=800,height=500");
}


function tarifas(path)
{
	window.open(path+"tarifas.php","tarifas","toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=800,height=500");
}

function terminos(path)
{
	window.open(path+"terminos.php","terminos","toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=700,height=600");
}

var nivel_path;
function cerrarSesion(nivel)
{
	nivel_path=nivel;	
	document.location.href=nivel_path+"cerrarSesion.php";
	
	//Dialog.info("<span class='Aller13Gris'>Saliendo de Mercasist...</span>",{width:250, height:100, showProgress: true});
	//document.getElementById("iframe").src="http://www.mercadolibre.com.ve/jm/logout";
	
		
	//setTimeout(goCerrarSesion,6000);
}
function goCerrarSesion()
{
	document.location.href=nivel_path+"cerrarSesion.php";
}

function getRadioButtonSelectedValue(ctrl)
{
	if (ctrl.length==null)
		if (ctrl.checked==true)
			return ctrl.value;

    for(i=0;i<ctrl.length;i++)
        if(ctrl[i].checked) 
			return ctrl[i].value;
			
			
	return false;
}




function novedadLeida(id,path)
{
	Dialog.info("<span class='Aller13Gris'>Ejecutando acción, por favor espere....</span>",{width:250, height:100, showProgress: true});
	
	req=getXMLHttpRequest();
	req.onreadystatechange=process_novedadLeida;
	req.open("GET",path+"/procesos/novedadLeida.php?id_novedad="+id,true);
	req.send(null);	
}

function process_novedadLeida()
{
	if (req.readyState==4)
	{
		if (req.status==200)
		{			
			var response=req.responseText;
			document.getElementById('barra_warning').innerHTML=response;
			
			cerrarDialog();
		} 
		else
			window.alert("Ha ocurrido un problema");	   
	}
	 
}


function mostrarMensaje(url)
{
	dialog(700,400,url);
}



function aceptarTerminos()
{
	if (document.forma_terminos.aceptado.checked==true)
	{
		document.forma_terminos.submit();
	}
	else
		window.alert("Debes aceptar los términos y condiciones de uso para continuar");
}


function isset ( strVariableName ) { 

    try { 
        eval( strVariableName );
    } catch( err ) { 
        if ( err instanceof ReferenceError ) 
           return false;
    }

    return true;

 } 
 
 function eliminarCuenta(path)
 {
	 var dec=window.confirm("¿Seguro de eliminar tu cuenta en Mercasist?");
	 if (dec==true)
	 	document.location.href=path+"/alertas/eliminarCuenta.php";
 }
 
 function nivelesPath()
 {
	 var locat=document.location.pathname;
	 var n=locat.length;
	 
	 var locacion=locat.substring(1,n);
	 
	 var path='';
	 for (var i = 0; i< locacion.length; i++)
	 {
		if (locacion.charAt(i)=='/')
			path=path+'../';
	 }	 
	 
	 return path;
	 
	 
 }
 
 
var req_activar_noti;
function activarNoti()
{
	  if (Notification) 
		{
			Notification.requestPermission( function(status) 
			{
				if (status == "granted")
				{
					var path=nivelesPath();
					
					req_activar_noti=getXMLHttpRequest();
					req_activar_noti.onreadystatechange=process_activarNoti;
					req_activar_noti.open("GET",path+"alertas/notificacionesActivar.php",true);
					req_activar_noti.send(null);				
					
				}
			});
		
		
		}
}
 
 
 function process_activarNoti()
{
	if (req_activar_noti.readyState==4)
	{
		if (req_activar_noti.status==200)
		{		
			cerrarDialog();
			
			window.alert('Muchas gracias. En breve empezaras a recibir notificaciones');
			
		} 
		else
			window.alert("Ha ocurrido un problema");	   
	}
	 
}
 
 
 
