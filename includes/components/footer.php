<footer class="main-footer">
    <div class="footer-container">
        <div class="footer-content">
            <p>&copy; <?php echo date('Y'); ?> <strong>Feel BiG</strong> - Todos los derechos reservados.</p>
        </div>
        
        <?php 
        // Reutilizamos la firma corporativa definida en firma.php
        if(function_exists('renderInfoFooter')) {
            echo renderInfoFooter(); 
        }
        ?>
    </div>
</footer>