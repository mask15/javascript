
$(".btnNum").click(function(){
	var valor = $(this).html();
	if ($(".texto").html()=="0" && valor!="."){
		$(".texto").html(valor);
	}
	else{
		$(".texto").html($(".texto").html()+valor);
	}
});
$(".btnCE").click(function(){
	$(".texto").html("0");
	$("input[name=operacion]").val("");
});

$(".btnSum").click(function(){
	var valor = $(this).html();
	if ($("input[name=operacion]").val()==""){
		$("input[name=operacion]").val(valor);
		$("input[name=valorAnterior]").val($(".texto").html());
		$(".texto").html("0");
	}
});

$(".btnRest").click(function(){
	var valor = $(this).html();
	if ($("input[name=operacion]").val()==""){
		$("input[name=operacion]").val(valor);
		$("input[name=valorAnterior]").val($(".texto").html());
		$(".texto").html("0");
	}
});

$(".btnDiv").click(function(){
	var valor = $(this).html();
	if ($("input[name=operacion]").val()==""){
		$("input[name=operacion]").val(valor);
		$("input[name=valorAnterior]").val($(".texto").html());
		$(".texto").html("0");
	}
});

$(".btnMult").click(function(){
	var valor = $(this).html();
	if ($("input[name=operacion]").val()==""){
		$("input[name=operacion]").val(valor);
		$("input[name=valorAnterior]").val($(".texto").html());
		$(".texto").html("0");
	}
});

$(".btn-lg").click(function(){
	var valor = $(this).html();
	var num1 = parseFloat($("input[name=valorAnterior]").val());
	var num2 = parseFloat($(".texto").html());
	var operacion = $("input[name=operacion]").val();
	switch(operacion){
		case "+":
			var result = parseFloat(num1+num2);
			$(".texto").html(result);
			$("input[name=operacion]").val("");
			break;
		case "-": 
			var result = parseFloat(num1-num2);
			$(".texto").html(result);
			$("input[name=operacion]").val("");
			break;
		case "/":
			var result = parseFloat(num1/num2);
			$(".texto").html(result);
			$("input[name=operacion]").val("");
			break;
		case "x":
			var result = parseFloat(num1*num2);
			$(".texto").html(result);
			$("input[name=operacion]").val("");
			break;
	}
});

$(".texto").keydown(function(){
	if ($(".texto").html()=="0"){
		$(".texto").html("");
	}
$(".texto").numeric();
})