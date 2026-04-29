<?php
require_once dirname(__DIR__, 2) . '/includes/managers/main_manager.php';

global $page, $routeParts;

$manager = new MainManager();
$safe_route_parts = isset($routeParts) ? $routeParts : [];
$crumbs = $manager->get_breadcrumbs($page, $safe_route_parts);
?>

<?php if ($crumbs): ?>
<div class="breadcrumb-container">
    <div class="breadcrumb-list">
        <?php foreach ($crumbs as $index => $crumb): ?>
            <div class="breadcrumb-item">
                <?php if ($crumb['link']): ?>
                    <a href="<?php echo $crumb['link']; ?>" class="enlace-personalizado">
                        <?php echo $crumb['title']; ?>
                    </a>
                <?php else: ?>
                    <span class="breadcrumb-current"><?php echo $crumb['title']; ?></span>
                <?php endif; ?>
            </div>

            <?php if ($index < count($crumbs) - 1): ?>
                <div class="breadcrumb-separator">
                    <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2" fill="none">
                        <path d="M9 18l6-6-6-6"></path>
                    </svg>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>