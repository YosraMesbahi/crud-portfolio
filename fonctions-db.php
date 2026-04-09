<?php
include_once('connexion.php');

// Trouver UN élément par son id
function db_findOne($table, $id) {
    global $conn;
    $table = mysqli_real_escape_string($conn, $table);
    $id = intval($id);
    $query = "SELECT * FROM $table WHERE id = $id LIMIT 1";
    $result = mysqli_query($conn, $query);
    return mysqli_fetch_assoc($result);
}

// Trouver TOUS les éléments d'une table
function db_findAll($table) {
    global $conn;
    $table = mysqli_real_escape_string($conn, $table);
    $query = "SELECT * FROM $table";
    $result = mysqli_query($conn, $query);
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

// Supprimer un élément par son id
function db_delete($table, $id) {
    global $conn;
    $table = mysqli_real_escape_string($conn, $table);
    $id = intval($id);
    $requete = "DELETE FROM $table WHERE id = $id";
    return mysqli_query($conn, $requete);
}
?>