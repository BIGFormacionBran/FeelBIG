<?php
/**
 * UTIL RENDERIZADO DE CARD ITEM GENÉRICO
 * @param array $item ['name', 'img', 'link', 'badge', 'fecha' (opcional)]
 */
function render_card_item_util($item) {
    ?>
    <a href="<?php echo $item['link']; ?>" class="card-item" style="display: flex; flex-direction: column; height: 100%;">
        <div class="thumb" style="background-image: url('assets/img/<?php echo $item['img']; ?>')">
            <?php if (isset($item['badge'])): ?>
                <span class="badge"><?php echo $item['badge']; ?></span>
            <?php endif; ?>
        </div>
        <div style="padding: 15px; flex-grow: 1; display: flex; flex-direction: column; justify-content: space-between;">
            <div style="margin-bottom: 15px;">
                <h4 style="margin: 0 0 10px 0;"><?php echo $item['name']; ?></h4>
                <?php if(isset($item['fecha'])): ?>
                    <p style="font-size: 13px; color: var(--color-texto-muted); margin: 0;">
                        <strong>Fecha:</strong> <?php echo $item['fecha']; ?>
                    </p>
                <?php endif; ?>
            </div>
            <div class="btn-primario" style="font-size: 12px; padding: 10px; width: 100%; text-align: center;">
                MÁS INFORMACIÓN
            </div>
        </div>
    </a>
    <?php
}