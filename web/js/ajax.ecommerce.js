function EliminarRegistroEcommerce(funcion,id,status,msg,callback)
{

    var var_query = {
                        function: funcion,
                        vars_ajax:[id,status]
                    };

    if(!callback || callback.length == 0)
    {
        callback = "EliminarRegistroPanel";
    }

    if(status == 1)
    {
        $("#"+id).attr("href","#desactivar").html("<i class='icon  icon-r-eliminar'> </i>").trigger("eliminar-registro")
        pasarelaAjax('GET',var_query,callback,var_query.vars_ajax);
        return true;
    }

    var now = $.now();
    if(!msg || msg.length == 0)
    {

        msg = "¿Realmente quiere eliminar este registro?";
    }


    var confirm = $('<div id="dialog-confirm'+now+'" title="Advertencia">\
    <p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>'+msg+'</p>\
    </div>');


    $(function() {
        $( confirm ).dialog({
            resizable: false,
            height:140,
            modal: true,
            buttons: {
                "Aceptar": function() {
                    $("#"+id).attr("href","#activar").html("<i class='icon  icon-c-encender'> </i>").trigger("eliminar-registro")


                    pasarelaAjax('GET',var_query,callback,var_query.vars_ajax);
                    $( this ).dialog( "close" );

                },
                "Cancelar": function() {
                    $( this ).dialog( "close" );
                }
            }
        });
    });

}

function eliminarProductoCarrito(id)
{
    EliminarRegistroEcommerce("eliminarProductoCarrito",id,0,'¿Realmente quiere eliminar el producto?',"eliminarProductoCarritoHTML");
}


function eliminarProductoCarritoHTML(response,id)
{
    var respuesta = null;
    if(response != "null")
    {
        respuesta = JSON.parse(response);

        if(respuesta["error"])
        {
            _alert(respuesta["message"],"Error");
        }
        else{

            $(".contenedor_producto_"+id).fadeOut('fast',function(){
                $(this).remove();
                setQTYProductoCarridoHTML(response);
                addProductoCarritoHTML(response,1);
                getInfoTotalsCheckout();
            });
            
            

        }

    }
    return true;
}

function llenaCarrido()
{
     var var_query = {
          "function": "getInfoCarrito",
          "vars_ajax":[]
    };

    pasarelaAjax('GET',var_query,"addProductoCarritoHTML",[0],null);
}


function addProductoCarrito(id,qty)
{
    var caracteristicas = {};
     var var_query = {
          "function": "addProductoCarrito",
          "vars_ajax":[id,qty,caracteristicas]
    };

    pasarelaAjax('GET',var_query,"addProductoCarritoHTML",[1]);
}


function addProductoCarritoHTML(response,show)
{
    var respuesta = null;

    $("#widget_carrito .cont_detalle .productos").empty();
    if(response != "null")
    {
        respuesta = JSON.parse(response);

        if(!respuesta["error"])
        {
            if(respuesta["productos"])
            {
                for(var i=0; i < respuesta["productos"].length; i++)
                {

                    $("#widget_carrito .cont_detalle .productos")
                    .append("<div class='w-xxxx-12 _minicart_product contenedor_producto_"+respuesta["productos"][i]["id"]+"'>\
                                <div class='w-xxxx-3 w-x-3 _minicart_image'>\
                                    <img src='"+respuesta["productos"][i]["img"]+"'>\
                                    </div>\
                                    <div class='w-xxxx-8 w-x-8 _minicart_prices'><div>"+
                                        "<h3>"+
                                            respuesta["productos"][i]["nombre"]+
                                        "</h3>"+
                                        "</div><div class='_x_qty'> x <span>"+respuesta["productos"][i]["qty"]+
                                        "</span> <span class='_x_qty'> = <span> <span class='_unit_price'>"+respuesta["productos"][i]["precio"]+
                                "</span></div>\
                                </div>\
                                <div class='w-xxxx-1 w-x-1 _minicart_delete'>\
                                <a class=\"_btn_eliminar\" href=\"javascript:void(0);\" onclick=\"eliminarProductoCarrito('"+respuesta["productos"][i]["id"]+"')\" ></a>\
                                <div>\
                            </div>");

                }
            
                $("#widget_carrito .cont_detalle .productos")
                .append("<div class='w-xxxx-12 _minicart_resume'>\
                    <div class='_subtotal'>Subtotal: "+respuesta["subtotal"]+"</div>\
                      <div class='_subtotal'>IVA: "+respuesta["iva"]+"</div>\
                    <div class='_total'>Total: "+respuesta["total"]+"</div>\
                </div>");
                $(".cont_detalle").hide();
                if(parseInt(show)== 1)
                {
                    $(".cont_detalle").show();
                }
                $('.cont_vacio').hide();
            }
            else{
                $('.cont_vacio').hide();
                if(parseInt(show)== 1)
                {
                    $('.cont_vacio').show();
                }
                
                $(".cont_detalle").hide();
            }
            $("#widget_carrito .abrir_carrito .count_productos").text(respuesta["qty"]);
                
        }
        else
        {
            _alert(respuesta["message"],"Error");
        }

    }

    return true;
}

function setQTYProductoCarrido(id,qty)
{

     var var_query = {
          "function": "setQTYProductoCarrido",
          "vars_ajax":[id,qty]
    };

    var var_function = [id];

    pasarelaAjax('GET',var_query,"setQTYProductoCarridoHTML",var_function);
}


function setQTYProductoCarridoHTML(response,id)
{
    
    var respuesta = null;
    if(response != "null")
    {
        respuesta = JSON.parse(response);

        if(!respuesta["error"])
        {
            
            if(respuesta["productos"])
            {
                for(var i=0; i < respuesta["productos"].length; i++)
                {
                    $(".contenedor_producto_"+respuesta["productos"][i]["id"]).find('.subtotal_producto').html(respuesta["productos"][i]["subtotal"]);
                    $(".contenedor_producto_"+respuesta["productos"][i]["id"]).find('.precio_producto').html(respuesta["productos"][i]["precio"]);
                }
                getInfoTotalsCheckout();
                addProductoCarritoHTML(response,0)
            }
            else
            {
                $(".content_carrito").html("El carrito esta vacio");
            }


        }
        else
        {
            _alert(respuesta["message"],"Error")
        }

    }
    return true;

}

function eliminarCuponCarrito(cupon)
{

    EliminarRegistro("eliminarCuponCarrito",cupon,0,'¿Realmente quiere eliminar el cupon?',"eliminarCuponCarritoHTML");

}


function eliminarCuponCarritoHTML(response)
{
    var respuesta = null;
    if(response != "null")
    {
        respuesta = JSON.parse(response);

        if(!respuesta.error)
        {
            window.location.reload;
        }
        else
        {
            _alert(respuesta["message"],"Error")
        }

    }
    return true;

}




function setFacturacionCheckout()
{
    var id_facturacion= $("input[name=id_facturacion]:checked").val();

    var var_query = {
        function: "setFacturacionCheckout",
        vars_ajax:[id_facturacion]
    };
    var var_function = [];
    pasarelaAjax('GET',var_query,"setFacturacionCheckoutHTML",var_function);
}


function setFacturacionCheckoutHTML(response)
{
    var respuesta = null;
    if(response != "null")
    {
        respuesta = JSON.parse(response);

        if(!respuesta.error)
        {
            $(".direccion_facturacion").next("div").hide();
            $(".direccion_facturacion").toggleClass("_nono").toggleClass("_sisi").toggleClass('_active');
            $(".metodo_envio").toggleClass('_active').next("div").show();
            $("#resumen_checkout_facturacion").html(respuesta.resumen_facturacion);
            loadMetodosEnvio();
        }
        else
        {
            _alert(respuesta["message"],"Error")
        }

    }
    return true;
}

function setNuevaFacturacionCheckout()
{
    var var_query = {
        function: "setNuevaFacturacionCheckout",
        vars_ajax:[JSON.stringify($("form[name=frmdirecciones_facturacion]").serializeArray())]
    };
    var var_function = [];
    pasarelaAjax('GET',var_query,"setNuevaFacturacionCheckoutHTML",var_function);
}



function setNuevaFacturacionCheckoutHTML(response)
{
    var respuesta = null;
    if(response != "null")
    {
        respuesta = JSON.parse(response);

        if(!respuesta.error)
        {
            $(".direccion_facturacion").next("div").hide();
            $(".direccion_facturacion").toggleClass("_nono").toggleClass("_sisi").toggleClass('_active');
            $(".metodo_envio").toggleClass('_active').next("div").show();
            $("#resumen_checkout_facturacion").html(respuesta.resumen_facturacion);
            loadMetodosEnvio();
        }
        else
        {
            _alert(respuesta["message"],"Error")
        }

    }
    return true;
}

function setNuevaDireccionCheckout()
{
    var var_query = {
        function: "setNuevaDireccionCheckout",
        vars_ajax:[JSON.stringify($("form[name=frmdirecciones]").serializeArray())]
    };
    var var_function = [];
    pasarelaAjax('GET',var_query,"setNuevaDireccionCheckoutHTML",var_function);
}



function setNuevaDireccionCheckoutHTML(response)
{
    var respuesta = null;
    if(response != "null")
    {
        respuesta = JSON.parse(response);

        if(!respuesta.error)
        {
            $(".direccion_entrega").next("div").hide();
            $(".direccion_entrega").toggleClass("_nono").toggleClass("_sisi").toggleClass('_active');
            $(".direccion_facturacion").toggleClass('_active').next("div").show();
            $("#resumen_checkout_envio").html(respuesta.resumen_envio);
        }
        else
        {
            _alert(respuesta["message"],"Error")
        }

    }
    return true;
}


function setPickUpCheckout()
{
    var var_query = {
        function: "setPickUpCheckout",
        vars_ajax:[$("form[name=frmpickup]").find('select[name=id_pickup]').val()]
    };
    var var_function = [];
    pasarelaAjax('GET',var_query,"setPickUpCheckoutHTML",var_function);
}



function setPickUpCheckoutHTML(response)
{
    var respuesta = null;
    if(response != "null")
    {
        respuesta = JSON.parse(response);

        if(!respuesta.error)
        {
            $(".metodo_envio").next("div").hide();
            $(".metodo_envio").toggleClass("_nono").toggleClass("_sisi").toggleClass('_active');
            $(".metodo_pago").toggleClass('_active').next("div").show();
            $("#resumen_metodo_envio").html(respuesta.resumen_envio);
            getInfoTotalsCheckout2();
            loadMetodosPago();
        }
        else
        {
            _alert(respuesta["message"],"Error")
        }

    }
    return true;
}



function setDireccionCheckout()
{
    var id_envio= $("input[name=id_envio]:checked").val();

    var var_query = {
        function: "setDireccionCheckout",
        vars_ajax:[id_envio]
    };
    var var_function = [];
    pasarelaAjax('GET',var_query,"setDireccionCheckoutHTML",var_function);
}


function setDireccionCheckoutHTML(response)
{
    var respuesta = null;
    if(response != "null")
    {
        respuesta = JSON.parse(response);

        if(!respuesta.error)
        {
            $(".direccion_entrega").next("div").hide();
            $(".direccion_entrega").toggleClass("_nono").toggleClass("_sisi").toggleClass('_active');
            $(".direccion_facturacion").toggleClass('_active').next("div").show();
            $("#resumen_checkout_envio").html(respuesta.resumen_envio);
        }
        else
        {
            _alert(respuesta["message"],"Error")
        }

    }
    return true;
}


function loadMetodosEnvio()
{
    var var_query = {
        function: "loadMetodosEnvio",
        vars_ajax:[]
    };
    var var_function = [];
    pasarelaAjax('GET',var_query,"loadMetodosEnvioHTML",var_function);
}


function loadMetodosEnvioHTML(response)
{
    var respuesta = null;
    if(response != "null")
    {
        respuesta = JSON.parse(response);

        if(respuesta.envio_requerido == 1)
        {
            $("#content_metodo_envio").html(respuesta.html);
            $( "#frm_metodo_envio" ).validate({
                submitHandler: function(form)
                {
                     setMetodoEnvioCheckout();
                     return false;
                }
            });
            $( "#frmpickup" ).validate({
                submitHandler: function(form)
                {
                     setPickUpCheckout();
                     return false;
                }
            });

            
            $("input[name=id_metodo_envio]").change(function()
            {
                if($(this).parent().find('.envio_name').text() == respuesta.labelpickup)
                {
                    
                     $("#form_pick-up").show();
                    $("form[name=frm_metodo_envio] input[name=continuar]").hide();
                }
                else
                {
                    $("#form_pick-up").hide();
                    $("#frm_metodo_envio").show();
                    $("form[name=frm_metodo_envio] input[name=continuar]").show();
                }
            });


        }
        else{
            $(".metodo_envio").next("div").hide();
            $(".metodo_envio").toggleClass("_nono").toggleClass("_sisi").toggleClass('_active');
            $(".metodo_pago").toggleClass('_active').next("div").show();
            loadMetodosPago();
        }

    }
    return true;
}


function setMetodoEnvioCheckout()
{
    var id_metodo_envio= $("input[name=id_metodo_envio]:checked").val();

    var var_query = {
        function: "setMetodoEnvioCheckout",
        vars_ajax:[id_metodo_envio]
    };
    var var_function = [];
    pasarelaAjax('GET',var_query,"setMetodoEnvioCheckoutHTML",var_function);
}


function setMetodoEnvioCheckoutHTML(response)
{
    var respuesta = null;
    if(response != "null")
    {
        respuesta = JSON.parse(response);

        if(!respuesta.error)
        {
            $(".metodo_envio").next("div").hide();
            $(".metodo_envio").toggleClass("_nono").toggleClass("_sisi").toggleClass('_active');
            $(".metodo_pago").toggleClass('_active').next("div").show();
            $("#resumen_metodo_envio").html(respuesta.resumen_metodo_envio);
            getInfoTotalsCheckout2();
            loadMetodosPago();
        }
        else
        {
            _alert(respuesta["message"],"Error")
        }

    }
    return true;
}


function loadMetodosPago()
{
    var var_query = {
        function: "loadMetodosPago",
        vars_ajax:[]
    };
    var var_function = [];
    pasarelaAjax('GET',var_query,"loadMetodosPagoHTML",var_function);
}


function loadMetodosPagoHTML(response)
{
    var respuesta = null;
    if(response != "null")
    {
        respuesta = JSON.parse(response);


        $("#content_metodo_pago").html(respuesta.html);
        $( "#frm_pago" ).validate({
                submitHandler: function(form)
                {
                     setconfigPago();
                     return false;
                }
        });
        $('input[name=id_pago]').each(function(index,val){
          $(this).next('span').addClass($(this).val());
        });

    }
    return true;
}


function setconfigPago()
{

    var id_pago = $('input:radio[name=id_pago]:checked').val();

    if(id_pago != "")
    {

        var var_query = {
            "function": "setconfigPago",
            "vars_ajax":[id_pago]
        };

        var var_function = [id_pago];

        pasarelaAjax('GET',var_query,"setconfigPagoHTML",var_function);
    }

}


function setconfigPagoHTML(response,id_pago)
{
    var respuesta = null;
    if(response != "null")
    {
        respuesta = JSON.parse(response);

        if(!respuesta.error)
        {
            $(".paga_ahora").next("div").show();
            $(".metodo_pago").toggleClass("_nono").toggleClass("_sisi").toggleClass('_active');
            $(".metodo_pago").next("div").hide();
            $(".paga_ahora").toggleClass('_active');
            getFrmPago(id_pago)
        }
        else
        {
            _alert(respuesta["message"],"Error")
        }

    }
    return true;
}

function getFrmPago(id_pago)
{

        var var_query = {
              "function": id_pago
        };

        var var_function = [];

        pasarelaAjax('GET',var_query,"getFrmPagoHTML",var_function);


}


function getFrmPagoHTML(response)
{
    var respuesta = null;
    if(response != "null")
    {
        respuesta = JSON.parse(response);

        if(!respuesta.error)
        {

            $("#contenedor_frm_pago").html(respuesta.html);
            if(respuesta.js)
            {
                eval(respuesta.js)
            }
        }
        else
        {
            _alert(respuesta["message"],"Error")
        }

    }
    return true;
}


function ecommerce_setCupon(cupon){
    var var_query = {
              function: "ecommerce_setCupon",
              vars_ajax:[cupon]
    };

    pasarelaAjax('GET',var_query,"ecommerce_setCuponHTML",var_query.vars_ajax);
}


function ecommerce_setCuponHTML(response,cupon)
{
    var respuesta = null;

    if(response != "null")
    {
        respuesta = JSON.parse(response);

        if(!respuesta.error)
        {
            $('.content_cupon_activo').html(respuesta.html);
            $('.content_form_cupon').hide();
            $('#frmcupon').trigger('reset');

            $('.content_cupon_activo').find('a.remove').click(function(e){
                e.preventDefault();
                ecommerce_removeCupon();
            });


            getInfoTotalsCheckout();

        }
        else
        {
             _alert(respuesta["message"],"Error");
        }

    }
}




function ecommerce_removeCupon(){
    var var_query = {
              function: "ecommerce_removeCupon",
              vars_ajax:[]
    };

    pasarelaAjax('GET',var_query,"ecommerce_removeCuponHTML",[]);
}


function ecommerce_removeCuponHTML(response)
{
    $('.content_cupon_activo').empty();
    $('.content_form_cupon').show();
    getInfoTotalsCheckout();
}

function getInfoTotalsCheckout(){
    var var_query = {
              function: "getInfoTotalsCheckout",
              vars_ajax:[]
    };

    pasarelaAjax('GET',var_query,"getInfoTotalsCheckoutHTML",[]);
}


function getInfoTotalsCheckoutHTML(response)
{
    var respuesta = null;

    if(response != "null")
    {
        respuesta = JSON.parse(response);
        $(".resumen_page_carrito .subtotal").html(respuesta.subtotal);
        $(".resumen_page_carrito .iva").html(respuesta.iva);
        $(".resumen_page_carrito .total").html(respuesta.total);
        if(respuesta.descuento){
            $(".resumen_page_carrito .descuento").html(respuesta.descuento);
            $(".resumen_page_carrito ._descuento").show();
        }
        else{
            $(".resumen_page_carrito .descuento").html('');
            $(".resumen_page_carrito ._descuento").hide();
        }
    }
}

/*** Cupon checkout ****/


function ecommerce_setCuponCheckout(cupon){
    var var_query = {
              function: "ecommerce_setCupon",
              vars_ajax:[cupon]
    };

    pasarelaAjax('GET',var_query,"ecommerce_setCuponCheckoutHTML",var_query.vars_ajax);
}


function ecommerce_setCuponCheckoutHTML(response,cupon)
{
    var respuesta = null;

    if(response != "null")
    {
        respuesta = JSON.parse(response);

        if(!respuesta.error)
        {
            $('.content_cupon_activo').html(respuesta.html);
            $('.content_form_cupon').hide();
            $('#frmcupon').trigger('reset');

            $('.content_cupon_activo').find('a.remove').click(function(e){
                e.preventDefault();
                ecommerce_removeCuponCheckout();
            });


            getInfoTotalsCheckout2();

        }
        else
        {
             _alert(respuesta["message"],"Error");
        }

    }
}




function ecommerce_removeCuponCheckout(){
    var var_query = {
              function: "ecommerce_removeCupon",
              vars_ajax:[]
    };

    pasarelaAjax('GET',var_query,"ecommerce_removeCuponCheckoutHTML",[]);
}


function ecommerce_removeCuponCheckoutHTML(response)
{
    $('.content_cupon_activo').empty();
    $('.content_form_cupon').show();
    getInfoTotalsCheckout2();
}

function getInfoTotalsCheckout2(){
    var var_query = {
              function: "getInfoTotalsCheckout2",
              vars_ajax:[]
    };

    pasarelaAjax('GET',var_query,"getInfoTotalsCheckout2HTML",[]);
}


function getInfoTotalsCheckout2HTML(response)
{
    var respuesta = null;

    if(response != "null")
    {
        respuesta = JSON.parse(response);


        $("._checkout_subtotal").children('.price').html(respuesta.subtotal);
        $("._checkout_iva").children('.price').html(respuesta.iva_html);
        $("._checkout_total").children('.price').html(respuesta.gran_total);

         if(respuesta.monto_envio){
            $("._checkout_envio").children('.price').html(respuesta.monto_envio);
            $("._checkout_envio").show();
        }
        else{
            $("._checkout_envio").children('.price').empty();
            $("._checkout_envio").hide();
        }
        if(respuesta.descuento){
            $("._checkout_descuento").children('.price').html(respuesta.descuento);
            $("._checkout_descuento").show();
        }
        else{
            $("._checkout_descuento").children('.price').empty();
            $("._checkout_descuento").hide();
        }
    }
}
