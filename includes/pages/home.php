<?php
// Simulamos las categorías del proyecto Feel BiG
$categorias = [
    [
        "id" => "big-fit",
        "titulo" => "BiG Fit - Actividad Física",
        "clase" => "blue-section",
        "items" => [
            ["name" => "GerontoAVD", "img" => "geronto.jpg", "type" => "Programa"],
            ["name" => "Formación Actividad", "img" => "formacion.jpg", "type" => "Video"]
        ]
    ],
    [
        "id" => "alimentacion",
        "titulo" => "Alimentación Saludable",
        "clase" => "white-section",
        "items" => [
            ["name" => "Recetas Iván", "img" => "receta1.jpg", "type" => "Píldora"],
            ["name" => "Planificador", "img" => "plan.jpg", "type" => "Web Tool"]
        ]
    ]
];
?>

<section class="hero-feelbig">
    <h1>Bienvenido a Feel BiG</h1>
    <p>Soluciones de bienestar para empresas y familias.</p>
</section>

<?php foreach ($categorias as $cat): ?>
    <div class="category-row <?php echo $cat['clase']; ?>">
        <h2><?php echo $cat['titulo']; ?></h2>
        <div class="netflix-slider">
            <?php foreach ($cat['items'] as $item): ?>
                <div class="card-item">
                    <div class="thumb" style="background-image: url('assets/img/<?php echo $item['img']; ?>')">
                        <span class="badge"><?php echo $item['type']; ?></span>
                    </div>
                    <h4><?php echo $item['name']; ?></h4>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
<?php endforeach; ?>