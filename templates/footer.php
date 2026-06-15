<?php
function render_footer($scriptPath = 'script.js')
{
    ?>
    <script src="<?= htmlspecialchars($scriptPath, ENT_QUOTES, 'UTF-8'); ?>"></script>
    <?php
}
?>
