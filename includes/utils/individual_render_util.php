<?php
function render_individual_view_util($data) {
    ?>
    <div class="creiss-single-wrapper">
        <div class="creiss-single-header">
            <h1 class="creiss-title"><?php echo $data['title']; ?></h1>
        </div>

        <?php if (isset($data['extra_info'])): ?>
        <div class="creiss-meta-flex">
            <?php foreach ($data['extra_info'] as $label => $value): ?>
                <div class="meta-item-box">
                    <strong class="meta-label"><?php echo $label; ?></strong>
                    <span class="meta-value"><?php echo $value; ?></span>
                </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <div class="creiss-featured-image">
            <img src="assets/img/<?php echo $data['img']; ?>" alt="<?php echo $data['title']; ?>">
            <?php if (isset($data['badge'])): ?>
                <span class="badge-float"><?php echo $data['badge']; ?></span>
            <?php endif; ?>
        </div>

        <div class="creiss-body-content">
            <div class="creiss-custom-block">
                <div class="text-area">
                    <?php echo $data['description']; ?>
                </div>
            </div>
            
            <div class="creiss-footer-actions">
                <a href="javascript:history.back()" class="btn-primario btn-back">VOLVER</a>
            </div>
        </div>
    </div>
    <?php
}