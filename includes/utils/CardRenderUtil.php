<?php
function renderCardItemColumn($item) {
    $link = ($item['type'] === 'category') ? '/' . $item['slug'] : renderIndividualPage($item);
    $buttonText = ($item['type'] === 'category') ? 'VER CONTENIDO' : 'MÁS INFORMACIÓN';
    ?>
    <div class="card-column">
        <div class="card-img-wrapper">
            <img src="<?php echo $item['img']; ?>" alt="<?php echo $item['name']; ?>" loading="lazy" decoding="async">
            <?php if (isset($item['badge'])): ?>
                <span class="card-badge-tag"><?php echo $item['badge']; ?></span>
            <?php endif; ?>
        </div>
        <div class="card-body-col">
            <h4><?php echo $item['name']; ?></h4>
            <p class="card-desc"><?php echo isset($item['description']) ? $item['description'] : ''; ?></p>
            <a href="<?php echo $link; ?>" class="btn-primario btn-card-col"><?php echo $buttonText; ?></a>
        </div>
    </div>
    <?php
}

function renderCardItemRow($item) {
    $link = ($item['type'] === 'category') ? '/' . $item['slug'] : renderIndividualPage($item);
    $buttonText = ($item['type'] === 'category') ? 'VER CONTENIDO' : 'MÁS INFORMACIÓN';
    ?>
    <div class="card-row">
        <img src="<?php echo $item['img']; ?>" alt="<?php echo $item['name']; ?>" class="card-row-img">
        
        <div class="card-row-content">
            <h4><?php echo $item['name']; ?></h4>
            <?php if (isset($item['description'])): ?>
                <p class="card-desc"><?php echo $item['description']; ?></p>
            <?php endif; ?>
        </div>

        <div class="card-row-action">
            <a href="<?php echo $link; ?>" class="btn-primario btn-card-row"><?php echo $buttonText; ?></a>
        </div>
    </div>
    <?php
}