<!-- App Capsule -->
<?php
// ini_set('display_errors', '1');
// BD
include('inc/conn.php');
// Play
if (isset($_GET['p']) && $_GET['p'] == "tv" && isset($_GET['c']) || isset($_GET['evento']) || isset($_GET['img']) || isset($_GET['r']) || isset($_GET['s']) || isset($_GET['f']) || isset($_GET['nbalp'])) {
    include('play.php');
    exit();
}
// Header
include('inc/header.php');
?>
<div class="container" id="appCapsule">
    <?php
    // Navegación
    if (isset($_GET['p'])) {
        // Escapar caracteres peligrosos
        $paginaSolicitada = basename($_GET['p']);
        // Ruta al directorio de páginas
        $rutaDirectorio = __DIR__ . '/';
        // Verificar
        if (file_exists($rutaDirectorio . $paginaSolicitada . ".php")) {
            // Si existe, cárgala
            include($rutaDirectorio . $paginaSolicitada . ".php");
        } else {
            // Si no existe, 404.php
            echo "No existe";
            //include("404.php");
        }
    } else {
        // Si no se proporciona ningún parámetro, carga la página predeterminada (index.php)
        include("home.php");
    } ?>
</div>
<?php
// Footer
include('inc/navbar.php');
?>