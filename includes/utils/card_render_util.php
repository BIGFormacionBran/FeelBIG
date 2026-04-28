<?php
function render_card_item_util($item) {
    // Llamamos a la función para que nos devuelva la URL
    $detailLink = render_individual_page($item); 
    ?>
    <div class="card-item">
        <div class="thumb">
            <img src="assets/img/<?php echo $item['img']; ?>" alt="<?php echo $item['name']; ?>" class="thumb-img">
            <?php if (isset($item['badge'])): ?>
                <span class="badge"><?php echo $item['badge']; ?></span>
            <?php endif; ?>
        </div>
        <div class="card-content">
            <div class="card-info">
                <h4><?php echo $item['name']; ?></h4>
                <?php if(isset($item['fecha'])): ?>
                    <p class="card-date"><strong>Fecha:</strong> <?php echo $item['fecha']; ?></p>
                <?php endif; ?>
            </div>
            <a href="<?php echo $detailLink; ?>">
                <div class="btn-primario btn-card">MÁS INFORMACIÓN</div>
            </a>
        </div>
    </div>
    <?php
}