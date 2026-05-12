<?php
function render_card_item_column($item) {
    $link = ($item['type'] === 'category') ? '/' . $item['slug'] : render_individual_page($item);
    ?>
    <div class="card-column">
        <div class="card-img-wrapper">
            <img src="/assets/img/<?php echo $item['img']; ?>" alt="<?php echo $item['name']; ?>">
            <?php if (isset($item['badge'])): ?>
                <span class="card-badge-tag"><?php echo $item['badge']; ?></span>
            <?php endif; ?>
        </div>
        <div class="card-body-col">
            <h4><?php echo $item['name']; ?></h4>
            <p class="card-desc"><?php echo isset($item['description']) ? $item['description'] : ''; ?></p>
            <a href="<?php echo $link; ?>" class="btn-primario btn-card-col">MÁS INFORMACIÓN</a>
        </div>
    </div>
    <?php
}

function render_card_item_row($item) {
    $link = ($item['type'] === 'category') ? '/' . $item['slug'] : render_individual_page($item);
    ?>
    <div class="card-row">
        <img src="/assets/img/<?php echo $item['img']; ?>" alt="<?php echo $item['name']; ?>" class="card-row-img">
        
        <div class="card-row-content">
            <h4><?php echo $item['name']; ?></h4>
            <p class="card-desc"><?php echo isset($item['description']) ? $item['description'] : ''; ?></p>
        </div>

        <div class="card-row-action">
            <a href="<?php echo $link; ?>" class="btn-primario btn-card-row">MÁS INFORMACIÓN</a>
        </div>
    </div>
    <?php
}