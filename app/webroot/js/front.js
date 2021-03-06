$(function(){
	$('.limite ').change(function(){
			document.location.href = BJS.setParam('limite',$(this).find('option:selected').val());
	});
	$('.orden').change(function(){
			document.location.href = BJS.setParam('orden',$(this).find('option:selected').val());
	});
	
	//CARGA EL LISTADO DE FILTROS
	var divFiltro=$("#listado_fltro");
	if(divFiltro.length > 0){
		divFiltro.load('/tags/filtro/'+divFiltro.attr('rel'),{'url':document.URL});
	}
	
	//CARGA EL PRODUCTO PROMOCIONADO
	var divPromocionado=$("#producto_destacado");
	if(divPromocionado.length > 0){
		divPromocionado.load('/products/featuredProduct/'+divPromocionado.attr('rel'));
	}
	
	//CARRITO
	/**
	 * Continuar con la orden desde
	 * /bcart/orders/getAddressInfo
	 */
	$(".envio-form").click(function(e){
		e.preventDefault();
		$("#OrderGetAddressInfoForm").submit();
	});
	
	//VALIDACION DE FORMULARIOS
	$.tools.validator.fn("[data-equals]", "el campo no es igual", function(input) {
		var name = input.attr("data-equals"),
		 field = this.getInputs().filter("[name='" + name + "']");
		return input.val() == field.val() ? true : [name]; 
	});
	
	$.tools.validator.localize("es", {
		'*'			: 'dato no valido',
		':email'  	: 'email no valido',
		':number' 	: 'el campo debe ser numerico',
		':url' 		: 'URL no valida',
		'[max]'	 	: 'el campo debe ser menor a $1',
		'[min]'		: 'el campo debe ser mayot a $1',
		'[required]'	: 'campo obligatorio',
		'[data-equals]' : 'verifique este campo'
	});
	$('#UserLoginForm').validator({lang:'es'});
	
	
	
});

