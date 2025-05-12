<?php
// Définir la variable $root_path en fonction de l'emplacement du fichier actuel
$current_path = $_SERVER['PHP_SELF'];
$path_parts = explode('/', $current_path);

// Si nous sommes dans le dossier pages ou admin, remonter d'un niveau
if (in_array('pages', $path_parts) || in_array('admin', $path_parts)) {
    $root_path = '../';
} else {
    $root_path = '';
}
?>
