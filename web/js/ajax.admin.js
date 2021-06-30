function EliminarDireccionEcommerce(id,status)
{

    if(status == 0)
    {
        EliminarRegistro("EliminarDireccionEcommerce",id,status,'¿Realmente quiere eliminar esta direccion?')
    }
    else
    {
        var var_query = {
          "function": "EliminarDireccionEcommerce",
          "vars_ajax":[id,status]
        };

        pasarelaAjax('GET',var_query,"EliminarRegistroPanel","");
    }

}


function EliminarDireccionFacturacionEcommerce(id,status)
{

    if(status == 0)
    {
        EliminarRegistro("EliminarDireccionFacturacionEcommerce",id,status,'¿Realmente quiere eliminar esta direccion?')
    }
    else
    {
        var var_query = {
          "function": "EliminarDireccionFacturacionEcommerce",
          "vars_ajax":[id,status]
        };

        pasarelaAjax('GET',var_query,"EliminarRegistroPanel","");
    }

}


function EliminarTarjetaEcommerce(id)
{
    EliminarRegistro("EliminarTarjetaEcommerce",id,0,'¿Realmente quiere eliminar la tarjeta?',"EliminarTarjetaEcommerceHTML");
}

function EliminarTarjetaEcommerceHTML(response,id)
{
    var respuesta = null;

    if(response != "null")
    {
        respuesta = JSON.parse(response);

        if(respuesta["message"] == "success")
        {

            $("#tarjeta"+id).fadeOut(500,function(){
                $("#tarjeta"+id).remove();
            });
        }
        else
        {
             _alert(respuesta["message"],"Error")
        }

    }
}



function SetStatusPagoEcommerce()
{

    var now = $.now();
    var msg = "¿Realmente quieres cambiar el estatus del pedido?";



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


                  var var_query = {
                    "function": "SetStatusPagoEcommerce",
                    "vars_ajax":[$("form[name=frmStatus]").find('input[name=id]').val(),
                        $("form[name=frmStatus]").find('select[name=status]').val(),
                        $("form[name=frmStatus]").find('textarea[name=nota]').val(),
                        $("form[name=frmStatus]").find('input[name=cantidad]').val()]
                  };

                  pasarelaAjax('POST',var_query,"SetStatusPagoEcommerceHTML",var_query.vars_ajax);
                    $( this ).dialog( "close" );

                },
                "Cancelar": function() {
                    $( this ).dialog( "close" );
                }
            }
        });
    });

}


function SetStatusPagoEcommerceHTML(response,id,status,nota,cantidad)
{
    var respuesta = null;

    if(response != "null")
    {
        respuesta = JSON.parse(response);

        if(!respuesta.error)
        {
            _alert(respuesta.message,"success");
            $('.set-status').text(respuesta.status);
            $("form[name=frmStatus]").find('textarea[name=nota]').val('');
            $("form[name=frmStatus]").find('input[name=cantidad]').val('');
            $('.content_monto').hide();
            $('.content_nota').hide();
            if(status == 'canceled')
            {
                $( "form[name=frmStatus]" ).remove();
            }
        }
        else
        {
             _alert(respuesta["message"],"Error");
        }

    }
}


function ajax_setInputsConfigPromo(id,promocion)
{

        var var_query = {
          "function": "ajax_setInputsConfigPromo",
          "vars_ajax":[id,promocion]
        };

        pasarelaAjax('GET',var_query,"ajax_setInputsConfigPromoHTML",var_query.vars_ajax,'.content_config_promo');
    

}

function ajax_setInputsConfigPromoHTML(response,id,promocion)
{
    var respuesta = null;

    if(response != "null")
    {
        respuesta = JSON.parse(response);

        if(!respuesta.error)
        {
            $('.content_config_promo').html(respuesta.html);
             $( "#frmcupones" ).validate();
        }
        else
        {
             _alert(respuesta["message"],"Error");
        }

    }
}