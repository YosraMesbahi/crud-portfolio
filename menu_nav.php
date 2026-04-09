<?php
// Fonction pour récupérer les éléments du menu
function getMenuItems($conn) {
    $query = "SELECT * FROM nav_menu ORDER BY ordre ASC";
    $result = mysqli_query($conn, $query);
    if ($result && mysqli_num_rows($result) > 0) {
        return $result;
    }
    return false;
}
?>