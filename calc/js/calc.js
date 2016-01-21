$(document).ready(function(){
	$(".texto").numeric();
	$(".texto").focus();
	$(".texto").keydown(function(e){
		if ($(".texto").val()=="0"){
			$(".texto").val("");
		}
		if (e.keyCode == 13){
			result();
		}
	});
});

$(".btnNum").click(function(){
	var valor = $(this).html();
	if ($(".texto").val()=="" && valor!="."){
		$(".texto").val(valor);
	}
	else{
		$(".texto").val($(".texto").val()+valor);
	}
	$(".texto").focus();
});

$(".btnCE").click(function(){
	$(".texto").val("");
	$("input[name=operacion]").val("");
});

$(".btnSum").click(op);
$(".btnRest").click(op);
$(".btnMult").click(op);
$(".btnDiv").click(op);
$(".btn-lg").click(result);

function op(){
	var valor = $(this).html();
	if ($("input[name=operacion]").val()==""){
		$("input[name=operacion]").val(valor);
		$("input[name=valorAnterior]").val($(".texto").val());
		$(".texto").val("");
		$(".texto").focus();
	}
}

function result(){
	var num1 = parseFloat($("input[name=valorAnterior]").val());
	var num2 = parseFloat($(".texto").val());
	var operacion = $("input[name=operacion]").val();
	switch(operacion){
		case "+":
			var result = parseFloat(num1+num2);
			$(".texto").val(result);
			$("input[name=operacion]").val("");
			break;
		case "-": 
			var result = parseFloat(num1-num2);
			$(".texto").val(result);
			$("input[name=operacion]").val("");
			break;
		case "/":
			var result = parseFloat(num1/num2);
			$(".texto").val(result);
			$("input[name=operacion]").val("");
			break;
		case "x":
			var result = parseFloat(num1*num2);
			$(".texto").val(result);
			$("input[name=operacion]").val("");
			break;
	}
	$(".texto").focus();
}
