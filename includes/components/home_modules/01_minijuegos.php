<?php
// Datos del componente (Más adelante vendrán de IONOS)
$minijuegos = [
    ["name" => "Salud Canarias Gaming", "img" => "game1.jpg", "link" => "https://saludcanariasgaming.bigformacion.com/"],
    ["name" => "Reto Nutrición", "img" => "game2.jpg", "link" => "#"],
    ["name" => "BiG Memory", "img" => "game3.jpg", "link" => "#"]
];
?>

<div class="home-module-wrapper">
    <div class="module-header">
        <h2>Minijuegos Saludables</h2>
        <a href="index.php?page=minijuegos" class="btn-ver-mas">Ver todos</a>
    </div>

    <div class="netflix-slider">
        <?php foreach ($minijuegos as $game): ?>
            <a href="<?php echo $game['link']; ?>" target="_blank" class="card-item">
                <div class="thumb" style="background-image: url('assets/img/<?php echo $game['img']; ?>')">
                    <span class="badge">Interactivo</span>
                </div>
                <h4><?php echo $game['name']; ?></h4>
            </a>
        <?php endforeach; ?>
    </div>
</div>