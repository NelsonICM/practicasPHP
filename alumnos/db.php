<?php
$db = new PDO('sqlite:' . __DIR__ . '/alumnos.db');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$db->exec("CREATE TABLE IF NOT EXISTS alumnos (
    id_alumno INTEGER PRIMARY KEY AUTOINCREMENT,
    nombre TEXT NOT NULL,
    apellido_paterno TEXT NOT NULL,
    apellido_materno TEXT NOT NULL
)");
?>