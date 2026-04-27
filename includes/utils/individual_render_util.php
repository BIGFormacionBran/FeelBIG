<?php
/**
 * UTIL GENERADOR DE VISTA INDIVIDUAL GLOBAL
 * @param array $data ['title', 'img', 'description', 'badge', 'extra_info' => []]
 */
function render_individual_view_util($data) {
    ?>
    <div class="container-page">
        <div class="individual-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 40px; align-items: start;">
            <div class="individual-media">
                <div class="thumb" style="background-image: url('assets/img/<?php echo $data['img']; ?>'); height: 450px; border-radius: var(--radio); position: relative; background-size: cover; background-position: center;">
                    <?php if (isset($data['badge'])): ?>
                        <span class="badge"><?php echo $data['badge']; ?></span>
                    <?php endif; ?>
                </div>
            </div>
            <div class="individual-info">
                <h1 class="titulo-acceso" style="text-align: left; margin-bottom: 10px;"><?php echo $data['title']; ?></h1>
                <div class="description-text" style="color: var(--color-texto-muted); margin-bottom: 25px; line-height: 1.8;">
                    <?php echo $data['description']; ?>
                </div>
                
                <?php if (isset($data['extra_info'])): ?>
                    <div class="extra-data-box" style="background: var(--color-fondo); padding: 20px; border-radius: 8px; margin-bottom: 25px;">
                        <?php foreach ($data['extra_info'] as $label => $value): ?>
                            <p style="margin: 5px 0;"><strong><?php echo $label; ?>:</strong> <?php echo $value; ?></p>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <a href="javascript:history.back()" class="btn-primario" style="max-width: 200px;">VOLVER</a>
            </div>
        </div>
    </div>
    <?php
}