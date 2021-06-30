var changeShippingAddress = function(){

    $("._sisi").removeClass("_sisi");
    $("._active").addClass("._nono").removeClass('_active').next("div").hide();
    $(".direccion_entrega").addClass("_nono").addClass('_active').next("div").show();
    $("#resumen_checkout_envio").empty();
    $("#resumen_checkout_facturacion").empty();
    $("#resumen_metodo_envio").empty();

}

var changeBillingAddress = function(){
    $(".direccion_facturacion").addClass("_nono").addClass('_active').removeClass("_sisi").next("div").show();
    $(".metodo_envio").addClass("_nono").removeClass('_active').removeClass("_sisi").next("div").hide();
    $(".paga_ahora").addClass("_nono").removeClass('_active').removeClass("_sisi").next("div").hide();
    $(".metodo_pago").addClass("_nono").removeClass('_active').removeClass("_sisi").next("div").hide();
    $("#resumen_checkout_facturacion").empty();
    $("#resumen_metodo_envio").empty();
}

var changeMetodoEnvio = function(){

    $(".metodo_envio").addClass("_nono").addClass('_active').removeClass("_sisi").next("div").show();
    $(".paga_ahora").addClass("_nono").removeClass('_active').removeClass("_sisi").next("div").hide();
    $(".metodo_pago").addClass("_nono").removeClass('_active').removeClass("_sisi").next("div").hide();
    $("#resumen_metodo_envio").empty();

}

var changePaimentMethod = function(){
    $(".paga_ahora").addClass("_nono").removeClass('_active').removeClass("_sisi").next("div").hide();
    $(".metodo_pago").addClass("_nono").addClass('_active').removeClass("_sisi").next("div").show();
    $("#contenedor_frm_pago").empty();
}




$(document).ready(function()
{
    $("#widget_carrito .cerrar_carrito").click(function(e){
        e.preventDefault()
        $("#widget_carrito .cont_detalle").hide();
        $("#widget_carrito .cont_vacio").hide();
    });
    $("#widget_carrito .abrir_carrito").click(function(e){
        e.preventDefault()

        if(parseInt($("#widget_carrito .abrir_carrito .count_productos").text()) > 0)
        {
            $("#widget_carrito .cont_detalle").show();
        }
        else{

            $("#widget_carrito .cont_vacio").show();
        }

    });


    $("form[name=card-form]").find("input[type=submit]").click(function(){

        $("form[name=card-form]").valid();

    });

    if($("#widget_carrito").length > 0)
    {
        llenaCarrido();
    }
});


$("input[name=id_envio]").change(function()
{
    if($(this).val() == "otra")
    {
        $("#form_pick-up").hide();
        $("#form_direccion_envio").show();
        $("form[name=frm_direccion_envio] input[name=continuar]").hide();
    }
     else if($(this).val() == "pick-up")
    {
        $("#form_direccion_envio").hide();
         $("#form_pick-up").show();
        $("form[name=frm_direccion_envio] input[name=continuar]").hide();
    }
    else
    {
        $("#form_pick-up").hide();
        $("#form_direccion_envio").hide();
        $("form[name=frm_direccion_envio] input[name=continuar]").show();
    }
});