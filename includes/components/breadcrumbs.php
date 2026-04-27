<?php
// Obtenemos los breadcrumbs dinámicos
$crumbs = get_breadcrumbs_manager($page);
?>
<nav class="breadcrumb-container">
    <ul class="breadcrumb-list">
        <?php if ($crumbs): ?>
            <?php foreach ($crumbs as $crumb): ?>
                <li class="breadcrumb-item">
                    <?php if ($crumb['link']): ?>
                        <a href="<?php echo $crumb['link']; ?>"><?php echo $crumb['title']; ?></a>
                        <span class="breadcrumb-separator">
                            <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2" fill="none"><path d="M9 18l6-6-6-6"></path></svg>
                        </span>
                    <?php else: ?>
                        <span class="breadcrumb-current"><?php echo $crumb['title']; ?></span>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        <?php endif; ?>
    </ul>
</nav>