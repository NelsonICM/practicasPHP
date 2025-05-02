<?php include("db.php"); ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catálogo de Alumnos</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootswatch/5.3.3/cyborg/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
</head>
<body>
    <div class="container py-4">
        <h1 class="text-center mb-4">Catálogo de Alumnos</h1>
        
        <!-- Buscador -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="input-group">
                    <input type="text" id="nombre_buscar" class="form-control" placeholder="Buscar por nombre...">
                    <button id="btnBuscar" class="btn btn-success"><i class="fas fa-search me-2"></i>Buscar</button>
                    <button id="btnNuevo" class="btn btn-primary"><i class="fas fa-plus me-2"></i>Nuevo</button>
                </div>
            </div>
        </div>

        <!-- Tabla -->
        <div class="row">
            <div class="col-12" id="tablita">
                <!-- Cargando... -->
                <div class="text-center my-5">
                    <div class="spinner-border text-primary" role="status"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="modalAlumno">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Nuevo Alumno</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="formAlumno">
                        <input type="hidden" id="id_alumno">
                        <div class="mb-3">
                            <label>Nombre:</label>
                            <input type="text" id="nombre" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Apellido Paterno:</label>
                            <input type="text" id="apellido_paterno" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Apellido Materno:</label>
                            <input type="text" id="apellido_materno" class="form-control" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button id="btnGuardar" class="btn btn-success"><i class="fas fa-save me-2"></i>Guardar</button>
                    <button class="btn btn-danger" data-bs-dismiss="modal"><i class="fas fa-times me-2"></i>Cancelar</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            buscar();

            $("#btnBuscar").click(buscar);
            $("#btnNuevo").click(() => abrirModal());
            $("#btnGuardar").click(guardarAlumno);
        });

        function buscar() {
            $.ajax({
                type: "POST",
                url: "operaciones.php",
                data: {
                    accion: "buscar",
                    nombre_buscar: $("#nombre_buscar").val()
                },
                success: function(respuesta) {
                    $("#tablita").html(respuesta);
                },
                error: function(xhr) {
                    $("#tablita").html('<div class="alert alert-danger">Error al cargar datos</div>');
                    console.error(xhr.responseText);
                }
            });
        }

        function abrirModal(id = null) {
            if (id) {
                $("#modalTitle").text("Editar Alumno");
                $.ajax({
                    type: "POST",
                    url: "operaciones.php",
                    data: { accion: "obtener", id_alumno: id },
                    dataType: "json",
                    success: function(data) {
                        $("#id_alumno").val(data.id_alumno);
                        $("#nombre").val(data.nombre);
                        $("#apellido_paterno").val(data.apellido_paterno);
                        $("#apellido_materno").val(data.apellido_materno);
                        $("#modalAlumno").modal("show");
                    }
                });
            } else {
                $("#modalTitle").text("Nuevo Alumno");
                $("#formAlumno")[0].reset();
                $("#id_alumno").val("");
                $("#modalAlumno").modal("show");
            }
        }

        function guardarAlumno() {
            const datos = {
                accion: $("#id_alumno").val() ? "modificar" : "insertar",
                id_alumno: $("#id_alumno").val(),
                nombre: $("#nombre").val(),
                apellido_paterno: $("#apellido_paterno").val(),
                apellido_materno: $("#apellido_materno").val()
            };

            if (!datos.nombre || !datos.apellido_paterno || !datos.apellido_materno) {
                alert("Todos los campos son obligatorios");
                return;
            }

            $.ajax({
                type: "POST",
                url: "operaciones.php",
                data: datos,
                dataType: "json",
                success: function(respuesta) {
                    if (respuesta.status === "OK") {
                        $("#modalAlumno").modal("hide");
                        buscar();
                    } else {
                        alert("Error: " + (respuesta.message || "Error desconocido"));
                    }
                },
                error: function(xhr) {
                    alert("Error en la solicitud");
                    console.error(xhr.responseText);
                }
            });
        }

        function eliminarAlumno(id) {
            if (confirm("¿Eliminar este alumno?")) {
                $.ajax({
                    type: "POST",
                    url: "operaciones.php",
                    data: { accion: "eliminar", id_alumno: id },
                    dataType: "json",
                    success: function(respuesta) {
                        if (respuesta.status === "OK") {
                            buscar();
                        } else {
                            alert("Error: " + (respuesta.message || "Error al eliminar"));
                        }
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                    }
                });
            }
        }
    </script>
</body>
</html>