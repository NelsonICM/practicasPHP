<?php
include("db.php");
header('Content-Type: application/json');

try {
    $accion = $_POST['accion'] ?? '';

    switch ($accion) {
        case 'buscar':
            $nombre = $_POST['nombre_buscar'] ?? '';
            $query = "SELECT * FROM alumnos WHERE nombre LIKE ?";
            $stmt = $db->prepare($query);
            $stmt->execute(["%$nombre%"]);
            $alumnos = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (empty($alumnos)) {
                echo "<div class='alert alert-info'>No hay registros</div>";
            } else {
                echo generarTabla($alumnos);
            }
            break;

        case 'obtener':
            $id = $_POST['id_alumno'] ?? 0;
            $stmt = $db->prepare("SELECT * FROM alumnos WHERE id_alumno = ?");
            $stmt->execute([$id]);
            echo json_encode($stmt->fetch(PDO::FETCH_ASSOC));
            break;

        case 'insertar':
        case 'modificar':
            $datos = [
                'nombre' => $_POST['nombre'] ?? '',
                'apellido_paterno' => $_POST['apellido_paterno'] ?? '',
                'apellido_materno' => $_POST['apellido_materno'] ?? ''
            ];

            if (in_array('', $datos)) {
                throw new Exception("Todos los campos son obligatorios");
            }

            if ($accion == 'insertar') {
                $stmt = $db->prepare("INSERT INTO alumnos (nombre, apellido_paterno, apellido_materno) VALUES (?, ?, ?)");
                $stmt->execute(array_values($datos));
            } else {
                $stmt = $db->prepare("UPDATE alumnos SET nombre=?, apellido_paterno=?, apellido_materno=? WHERE id_alumno=?");
                $stmt->execute([...array_values($datos), $_POST['id_alumno']]);
            }

            echo json_encode(['status' => 'OK']);
            break;

        case 'eliminar':
            $stmt = $db->prepare("DELETE FROM alumnos WHERE id_alumno = ?");
            $stmt->execute([$_POST['id_alumno']]);
            echo json_encode(['status' => 'OK']);
            break;

        default:
            throw new Exception("Acción no válida");
    }
} catch (Exception $e) {
    echo json_encode(['status' => 'ERROR', 'message' => $e->getMessage()]);
}

function generarTabla($alumnos) {
    $html = '<table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Apellido Paterno</th>
                        <th>Apellido Materno</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>';

    foreach ($alumnos as $alumno) {
        $html .= '<tr>
                    <td>' . htmlspecialchars($alumno['id_alumno']) . '</td>
                    <td>' . htmlspecialchars($alumno['nombre']) . '</td>
                    <td>' . htmlspecialchars($alumno['apellido_paterno']) . '</td>
                    <td>' . htmlspecialchars($alumno['apellido_materno']) . '</td>
                    <td>
                        <button class="btn btn-sm btn-warning" onclick="abrirModal(' . $alumno['id_alumno'] . ')">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-sm btn-danger" onclick="eliminarAlumno(' . $alumno['id_alumno'] . ')">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>';
    }

    $html .= '</tbody></table>';
    return $html;
}
?>