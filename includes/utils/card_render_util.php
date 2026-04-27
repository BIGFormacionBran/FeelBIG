<?php
function render_card_item_util($item) {
    ?>
    <a href="<?php echo $item['link']; ?>" class="card-item">
        <div class="thumb" style="background-image: url('assets/img/<?php echo $item['img']; ?>')">
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
            <div class="btn-primario btn-card">MÁS INFORMACIÓN</div>
        </div>
    </a>
    <?php
}