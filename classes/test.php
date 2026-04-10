<?php

include_once(__DIR__ . '/Database.php');
include_once(__DIR__ . '/Projet.php');

$db = new Database();
$conn = $db->connect();

$projet = new Projet($conn);

$liste = $projet->getAll();

while ($p = $liste->fetch_assoc()) {
    echo $p['titre'] . "<br>";
}