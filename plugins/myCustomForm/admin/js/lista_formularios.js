jQuery(document).ready(function($){

    //console.log(solicitudAjax);

    $("#btnNuevo").click(function(){
        $('#modalNuevo').modal({backdrop: 'static', keyboard: false})
        $("#modalNuevo").modal("show");        
    });

    var i = 1;

    $("#add").click(function(){
        i++;
        $("#camposDinamicos").append('<tr id="row'+i+'">'+
                    '<td>'+
                        '<label for="txtLabel" class="col-form-label" style="margin-right: 7px;">Label'+i+': </label>'+
                    '</td>'+
                    '<td>'+
                        '<input type="text" name="name[]" id="name" class="form-control name_list">'+
                    '</td>'+
                    '<td>'+
                        '<select name="type[]" id="type"class="form-control type_list" style="margin-left: 7px;">'+
                            '<option value="text">[text]</option>'+
                            '<option value="email">[email]</option>'+
                            '<option value="tel">[tel]</option>'+
                            '<option value="number">[number]</option>'+
                            '<option value="textarea">[text-area]</option>'+
                        '</select>'+
                    '</td>'+
                    '<td>'+
                        '<button name="remove" id="'+i+'" class="btn btn-danger btn_remove" style="margin-left: 15px;">X</button>'+
                    '</td>'+
                '</tr>');
        return false;
    });

    //Eliminar los elementos del Detalle 
    $(document).on('click', '.btn_remove', function(){
        var buttonId = $(this).attr('id');
        //alert(buttonId);
        $('#row'+buttonId+"").remove();
    });

    //Borrar Formulario

    $(document).on('click', 'a[data-id]',function(){
        $('#confirm-delete').modal('show');
        var id = this.dataset.id;
        
        $('.btnBorrar').click(function(){
            var url = solicitudAjax.url;
            $.ajax({
                type: "POST",
                url: url,
                data:{
                    action : "eliminapeticion",
                    nonce : solicitudAjax.seguridad,
                    id : id,
                },
                success:function(){
                    location.reload();
                }
            });
        });
    });

    //modal: mostrar registro de cada formulario
    $(".detCodigo").click(function(){
        $('#modalDetalleContacto').modal({backdrop: 'static', keyboard: false})
        $("#modalDetalleContacto").modal("show");  

        var idCliForm = $(this).attr('id');
        var nomForm = $(this).attr('data-form');
        $("#nomFomullario").text(nomForm);

        var url = 'http://localhost/wordpress/wp-json/myCustomForm/v1/formcliente/'+idCliForm;
        $.ajax({
            type: "GET",
            url: url,
            success:function(data){
                console.log(data[1]['label']);
                $.each(data, function(idx, obj) {
                    //alert(obj.datos);
                    $("#datosInfo").append('<label for="txtlabel" class="col-sm-5 col-form-label labelInfo '+
                                            'text-right" >'+obj.label+': </label>'+            
                                            '<div class="col-sm-7">'+
                                            '<label for="label" class="col-form-label labelTipo">'+obj.datos+'</label>'+
                                            '</div> ');
                                });
            }
        });
        
        return false;      
    });

    $(".cerrarModal").click(function(){
        $("#datosInfo").html('');

    });

});