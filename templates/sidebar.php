<?php
function render_sidebar($activeMenu = '', $role = 'user', $brand = 'Jaya')
{
    $isAdmin = $role === 'admin';
    ?>
    <nav class="sidebar">
        <div class="sidebar-header">
            <div class="logo-box">
                <span class="logo-text"><?= htmlspecialchars($brand, ENT_QUOTES, 'UTF-8'); ?></span>
                <button class="menu-toggle-btn" type="button" aria-label="Buka atau tutup menu">Menu</button>
            </div>
        </div>

        <ul class="nav-links">
            <?php if ($isAdmin): ?>
                <li class="nav-item <?= $activeMenu === 'admin' ? 'active-gradient' : ''; ?>">
                    <a href="admin.php" class="nav-link">
                        <i class="icon-dashboard"></i>
                        <span>Admin Control</span>
                    </a>
                </li>
            <?php else: ?>
                <li class="nav-item <?= $activeMenu === 'pembuatan' ? 'active-gradient' : ''; ?>">
                    <a href="menu.php" class="nav-link">
                        <i class="icon-create-document"></i>
                        <span>Pembuatan Surat</span>
                    </a>
                </li>
                <li class="nav-item <?= $activeMenu === 'riwayat' ? 'active-gradient' : ''; ?>">
                    <a href="menu_riwayat.php" class="nav-link history-link">
                        <i class="icon-history"></i>
                        <span>Riwayat Surat</span>
                    </a>
                </li>
            <?php endif; ?>

            <li class="nav-item">
                <a href="logout.php" class="nav-link history-link">
                    <i class="icon-history"></i>
                    <span>Keluar</span>
                </a>
            </li>
        </ul>
    </nav>
    <?php
}
?>
