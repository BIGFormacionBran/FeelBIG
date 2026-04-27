<?php
$title = "Minijuegos Saludables";
$viewAllLink = "index.php?page=minijuegos";
// El link ahora se genera automáticamente en el renderizador usando el ID o un slug
$items = [
    ["id" => "1", "name" => "Salud Canarias Gaming", "img" => "game1.jpg", "badge" => "Interactivo"],
    ["id" => "2", "name" => "Reto Nutrición", "img" => "game2.jpg", "badge" => "Interactivo"],
    ["id" => "3", "name" => "BiG Memory", "img" => "game3.jpg", "badge" => "Memoria"]
];

include __DIR__ . '/../carousel_template.php';