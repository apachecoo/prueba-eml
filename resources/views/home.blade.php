@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Registro de Usuarios</div>

                <div class="card-body">
                    @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                    @endif

                    <!-- Button trigger modal -->
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#UsuarioModal">
                        Agregar Usuario
                    </button>
                    
                   


                    <!-- Modal -->
                    <div class="modal fade" id="UsuarioModal" tabindex="-1" role="dialog"
                        aria-labelledby="UsuarioModalLabel" aria-hidden="true">
                        <div class="modal-dialog " role="document">
                            <div class="modal-content">
                                <form method="POST" action="{{ route('usuario.store') }}" id="form-usuario"
                                    enctype="multipart/form-data">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="UsuarioModalLabel">Formulario agregar usuario</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">

                                        @csrf
                                        <input type="hidden" name="hidden_id" id="hidden_id">
                                        <div class="form-row">
                                            <div class="form-group col-md-12">
                                                <label for="name">Nombres *</label>
                                                <input type="text" class="form-control" name="name" id="name">
                                                <span class="invalid-feedback hide" role="alert"></span>
                                                <div class="valid-feedback">ok!</div>
                                            </div>
                                        </div>

                                        <div class="form-row">
                                            <div class="form-group col-md-12">
                                                <label for="last_name">Apellidos *</label>
                                                <input type="text" class="form-control" name="last_name" id="last_name">
                                                <span class="invalid-feedback hide" role="alert"></span>
                                                <div class="valid-feedback">ok!</div>
                                            </div>
                                        </div>

                                        <div class="form-row">
                                            <div class="form-group col-md-12">
                                                <label for="telephone">Teléfono *</label>
                                                <input type="text" class="form-control" name="telephone" id="telephone">
                                                <span class="invalid-feedback hide" role="alert"></span>
                                                <div class="valid-feedback">ok!</div>
                                            </div>
                                        </div>

                                        <div class="form-row">
                                            <div class="form-group col-md-12">
                                                <label for="email">Correo electrónico *</label>
                                                <input type="text" class="form-control" name="email" id="email">
                                                <span class="invalid-feedback hide" role="alert"></span>
                                                <div class="valid-feedback">ok!</div>
                                            </div>
                                        </div>

                                        <div class="form-row">
                                            <div class="form-group col-md-12">
                                                <label for="password">Password *</label>
                                                <input type="password" class="form-control" name="password" id="password">
                                                <span class="invalid-feedback hide" role="alert"></span>
                                                <div class="valid-feedback">ok!</div>
                                            </div>
                                        </div>


                                        



                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-dismiss="modal">Cerrar</button>
                                        <button type="button" class="btn btn-primary"
                                            id="btn-guardar-usuario">Guardar</button>
                                    </div>

                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- fin Modal -->
                    


                    <br><br><br>
                    <div class="row">
                        <div class=" col-md-12">
                        <div class="table-responsive">
                            <table class="table">
                                <thead class="thead-dark">
                                    <tr>
                                        <th scope="col">ID</th>
                                        <th scope="col">Nombres</th>
                                        <th scope="col">Apellidos</th>
                                        <th scope="col">Telefono</th>
                                        <th scope="col">Fecha registro</th>
                                        <th scope="col">Fecha última modificación</th>
                                        <th scope="col">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($datos as $usuario)
                                    <tr>
                                        <th scope="row">{{ $usuario->id }}</th>
                                        <td>{{ $usuario->name }}</td>
                                        <td>{{ $usuario->last_name }}</td>
                                        <td>{{ $usuario->telephone }}</td>
                                        <td>{{ $usuario->created_at }}</td>
                                        <td>{{ $usuario->updated_at }}</td>
                                        
                                    
                                        <td>
                                            <button type="button" class="btn btn-success"
                                                onclick="mostrarModalEditar({{$usuario->id}})">
                                                Editar
                                            </button>
                                            <button type="button" class="btn btn-danger"
                                                onclick="eliminarUsuario({{$usuario->id}})">
                                                x
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach


                                </tbody>
                            </table>
</div>

                            {{ $datos->links('pagination::bootstrap-4')}}
                        </div>


                    </div>



                </div>
            </div>
        </div>
    </div>
</div>

<script>
    
function getCamposForm(idForm) {
    var camposForm = [];
    $("#" + idForm)
        .find("input[type='text'],input[type='number'],file,select,textarea")
        .each(function() {
            if ($(this).attr("name")) {
                camposForm.push($(this).attr("name"));
            }
        });
    return camposForm;
}

function limpiarFormulario(idForm) {
    $("#" + idForm)[0].reset();
    camposForm = getCamposForm(idForm);

    for (b in camposForm) {
        campo = camposForm[b];
        $campoo = $("#" + campo);
        $campoo.removeClass("is-invalid");
        $campoo.removeClass("is-valid");

        $campoo
            .siblings("span")
            .removeClass("show")
            .addClass("hide")
            .text("Campo obligatorio");
    }
}

$("#btn-guardar-usuario").click(function() {
    
    var data = $("#form-usuario").serialize();
    var camposForm = getCamposForm("form-usuario");
    var url = $("#form-usuario").attr("action");

    $.ajax({
        async: true,
        cache: false,
        type: "POST",
        url: url + "?" + data,
        contentType: false,
        processData: false,
        beforeSend: function() {
           
        }
    })
        .done(function(reply) {
            if (reply.created == false) {
                var camposError = [];
                errors = reply.errors;

                for (a in errors) {
                    campo = errors[a];
                    for (b in campo) {
                        campo2 = campo[b];
                        camposError.push(b);

                        $("#" + b)
                            .removeClass("is-invalid")
                            .addClass("is-invalid");
                        $("#" + b)
                            .siblings("span")
                            .removeClass("hide")
                            .addClass("show")
                            .text(campo[b]);
                    }
                }

                var camposOk = [];

                for (c in camposForm) {
                    if (camposError.indexOf(camposForm[c]) == -1) {
                        camposOk.push(camposForm[c]);
                    }
                }

                for (d in camposOk) {
                    $("#" + camposOk[d])
                        .removeClass("is-invalid")
                        .addClass("is-valid");
                    $("#" + camposOk[d])
                        .siblings("span")
                        .removeClass("show")
                        .addClass("hide")
                        .text("");
                }
            }

            if (reply.created == true) {
                limpiarFormulario("form-usuario");
                $("#UsuarioModal").modal("hide");
                toastr.success(reply.message);
                setTimeout(() => {
                    window.location.href = base_path + "/home";
                }, 1000);
            }
        })
        .fail(function(jqXHR, ajaxOptions, thrownError) {
            alert("El servidor no responde");
        });
});

function eliminarUsuario(id) {
    $.ajax({
        async: true,
        cache: false,
        type: "DELETE",
        url: base_path + "/usuario/" + id,
        data: { _token: "{{ csrf_token() }}" },
        beforeSend: function() {
            console.log("cargando...");
        }
    })
        .done(function(res) {
            if (res.deleted == true) {
                toastr.success("Registro eliminado");
                window.location.href = base_path + "/home";
            }

            if (res.deleted == false) {
                toastr.error(res.details);
            }
        })
        .fail(function(jqXHR, ajaxOptions, thrownError) {
            toastr.error("El servidor no responde");
        });
}

function mostrarModalEditar(id){

    $.ajax({
        async: true,
        cache: false,
        type: "GET",
        url: base_path + "/usuario/" + id,
        beforeSend: function() {
            console.log("cargando...");
        }
    })
        .done(function(resp) {

            console.log(resp);
            for (i in resp) {               
                 if($("#" + i).length >0){
                  $("#" + i).val(resp[i]);
                 }              
              }            
        })
        .fail(function(jqXHR, ajaxOptions, thrownError) {
            toastr.error("El servidor no responde");
        });

    $("#UsuarioModal").modal("show");
    $("#hidden_id").val(id);
}

</script>
@endsection