<?php
/**
 * Template reutilizable para carruseles
 * @var array $items (name, img, link, badge)
 * @var string $title
 * @var string $viewAllLink
 */
?>
<div class="home-module-wrapper">
    <div class="module-header">
        <h2><?php echo $title; ?></h2>
        <?php if (isset($viewAllLink)): ?>
            <a href="<?php echo $viewAllLink; ?>" class="btn-ver-mas">Ver todos</a>
        <?php endif; ?>
    </div>

    <div class="content-carousel">
        <?php foreach ($items as $item): ?>
            <a href="<?php echo $item['link']; ?>" target="_blank" class="card-item">
                <div class="thumb" style="background-image: url('assets/img/<?php echo $item['img']; ?>')">
                    <?php if (isset($item['badge'])): ?>
                        <span class="badge"><?php echo $item['badge']; ?></span>
                    <?php endif; ?>
                </div>
                <h4><?php echo $item['name']; ?></h4>
            </a>
        <?php endforeach; ?>
    </div>
</div>