var date = new Date();
var meses = ["Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"];
var festivos = "";

$(document).ready(function(){
	var inicial = "<a href='#close' title='Cerrar' class='close'>X</a>"
	inicial+= "<table class='tabla'>";
	inicial+= "<th colspan='5'>";
	inicial+= "<i class='fa fa-angle-double-left fa-1x pull-left' id='mesIzq'></i>";
	inicial+= "<span id='meses'></span>";
	inicial+= "<i class='fa fa-angle-double-right fa-1x pull-right' id='mesDer'></i></th>";
	inicial+= "<th colspan='2'>";
	inicial+= "<i class='fa fa-angle-double-left fa-1x pull-left' id='añoIzq'></i>";
	inicial+= "<span id='años'></span>";
	inicial+= "<i class='fa fa-angle-double-right fa-1x pull-right' id='añoDer'></i></th>";
	inicial+= "<tr id='dias'>";
	inicial+= "<td class='activo'>L</td>";
	inicial+= "<td class='activo'>M</td>";
	inicial+= "<td class='activo'>X</td>";
	inicial+= "<td class='activo'>J</td>";
	inicial+= "<td class='activo'>V</td>";
	inicial+= "<td class='activo'>S</td>";
	inicial+= "<td class='activo'>D</td>";
	inicial+= "</tr></table><span style='display:none;' id='oculto'></span>";
	$(".modalbox").html(inicial);
	$("#oculto").html(date.getMonth());
	$("#meses").html(meses[date.getMonth()]);
	$("#años").html(date.getFullYear());
	rellenaCalendario(date.getFullYear(),date.getMonth()+1);




	// RESTA UN MES CADA VEZ QUE SE HACE CLICK EN LAS FLECHITAS << DEL MES
	// ================================================================

	$("#mesIzq").click(function(){
		var n = $("#oculto").html();
		n-=1;
		if (n<0){
			n=11;
			var año = parseInt($("#años").html())-1;
			$("#años").html(año);
		}
		$("#oculto").html(n);
		$("#meses").html(meses[n]);
		$(".calendario").remove();
		rellenaCalendario($("#años").html(),n+1);
	});

	// SUMA UN MES CADA VEZ QUE SE HACE CLICK EN LAS FLECHITAS >> DEL MES
	// ================================================================

	$("#mesDer").click(function(){
		var n = parseInt($("#oculto").html());
		n=n+1;
		if (n>11){
			n=0;
			var año = parseInt($("#años").html())+1;
			$("#años").html(año);
		}
		$("#oculto").html(n);
		$("#meses").html(meses[n]);
		$(".calendario").remove();
		rellenaCalendario($("#años").html(),n+1);
	});

	// RESTA UN AÑO CADA VEZ QUE SE HACE CLICK EN LAS FLECHITAS << DEL AÑO
	// ===================================================================

	$("#añoIzq").click(function() {
		var n = parseInt($("#oculto").html());
		n=n+1;
		var año = parseInt($("#años").html())-1;
		$("#años").html(año);
		$(".calendario").remove();
		rellenaCalendario($("#años").html(),n);
	});

	// SUMA UN AÑO CADA VEZ QUE SE HACE CLICK EN LAS FLECHITAS >> DEL AÑO
	// ==================================================================

	$("#añoDer").click(function() {
		var n = parseInt($("#oculto").html());
		n=n+1;
		var año = parseInt($("#años").html())+1;
		$("#años").html(año);
		$(".calendario").remove();
		rellenaCalendario($("#años").html(),n);
	});
});



// RELLENA EL MES SELECCIONADO CON TODOS LOS DÍAS QUE LE CORRESPONDEN
// ==================================================================

	function rellenaCalendario(año,mes){
		var n = 5;
		var m = 24; 
		var a = parseInt(año%19);
		var b = parseInt(año%4); 
		var c = parseInt(año%7); 
		var d = parseInt((((19*a)+m)%30)); 
		var e = parseInt(((2*b)+(4*c)+(6*d)+n)%7);
		var resurr;
		var mesJ;
		var mesV;
		if ((d+e)<10){
			resurr = parseInt(d+e+22);
			mesJ = 3;
			mesV = 3;
		}
		else{
			resurr = parseInt(d+e-9);
			mesJ = 4;
			mesV = 4;
		}
		var juevesSanto = parseInt(resurr-3);
		var viernesSanto = parseInt(resurr-2);
		switch(juevesSanto){
			case 0: 
				juevesSanto=31;
				mesJ=3;
				mesV=4;
				break;
			case -1:
				juevesSanto=30;
				mesJ=3;
				mesV=3;
				break;
			case -2: 
				juevesSanto=29;
				mesJ=3;
				mesV=3;
				break;
		}
		switch(viernesSanto){
			case 0:
				viernesSanto=31;
				mesJ=3;
				mesV=3;
				break;
			case -1:
				viernesSanto=30;
				mesJ=3;
				mesV=3;
				break;
		}

		if (mes<10){
			mes = "0"+mes;
		}
		var totalDias ="";
		switch (mes){
			case "01":
			case "03":
			case "05":
			case "07":
			case "08":
			case 10:
			case 12:
				totalDias = 31;
				break;
			case "02" :
				if (año%4==0){
					totalDias = 29;
				}
				else{
					totalDias = 28;
				}
				break;
			default:
				totalDias = 30;
				break;

		}

		var date2 = new Date(año+"-"+mes+"-01");
		var dias = "<tr class='calendario'>";
		var numDia = date2.getDay();
		if (numDia==0){
				numDia=7;
		}
		for(x=1;x<=numDia;x++){
			if (x>=numDia){
				for (i=1;i<=totalDias;i++){
					if (x%7==0){
						if (i==date.getDate() && date.getFullYear() == $("#años").html() && date.getMonth()+1 == mes){
							dias+="<td id='tdDia' class='festivo hoy'>"+i+"</td>"
							dias+="</tr><tr class='calendario'>";
						}
						else{
							dias+="<td id='tdDia' class='festivo'>"+i+"</td>";
							dias+="</tr><tr class='calendario'>";
						}
					}
					else{
						if (i==date.getDate() && date.getFullYear() == $("#años").html() && date.getMonth()+1 == mes){
							dias+="<td id='tdDia' class='hoy'>"+i+"</td>"
						}
						else{
							if (i==juevesSanto && parseInt(mes) == parseInt(mesJ) || i==viernesSanto && parseInt(mes) == parseInt(mesV)){
								dias+="<td id='tdDia' class='festivo'>"+i+"</td>";
							}
							else{
								dias+="<td id='tdDia'>"+i+"</td>";
							}
						}
					}
					x++;
				}
			}
			else{
				dias+= "<td class='vacio'></td>";
			}
		}
		dias+="</tr>";
		$("#dias").after(dias);

		$("td#tdDia").click(function(){
			var dia = $(this).html();
			var mes = parseInt($("#oculto").html())+1;
			var año = $("#años").html();
			if (dia<10){
				dia = "0"+dia;
			}
			if (mes<10){
				mes = "0"+mes;
			}
			$("#calendar").val(dia+"/"+mes+"/"+año);
			$("#calendar").attr("value",dia+"/"+mes+"/"+año);
			window.location.replace("#close");
		});
	}



