<?php
function render_sidebar($activeMenu = '', $role = 'user', $brand = 'Jaya')
{
    $isAdmin = $role === 'admin';
    ?>
    <nav class="sidebar">
        <div class="sidebar-header">
            <div class="logo-box">
                <img class="sidebar-logo" src="/asset/logo_web.png" alt="Logo">
                <span class="brand-text">SisDelDes</span>
            </div>
        </div>

        <ul class="nav-links">
            <?php if ($isAdmin): ?>
                <li class="nav-item <?= $activeMenu === 'admin' ? 'active-gradient' : ''; ?>">
                    <a href="admin.php" class="nav-link">
                        <img class="icon-history" src="/asset/admin_control.png" alt="Admin Kontrol">
                        <span>Admin Control</span>
                    </a>
                </li>
            <?php else: ?>
                <li class="nav-item <?= $activeMenu === 'pembuatan' ? 'active-gradient' : ''; ?>">
                    <a href="menu.php" class="nav-link">
                        <img class="icon-create-document" src="/asset/home_page.png" alt="Pembuatan Surat">
                        <span>Pembuatan Surat</span>
                    </a>
                </li>
                <li class="nav-item <?= $activeMenu === 'riwayat' ? 'active-gradient' : ''; ?>">
                    <a href="menu_riwayat.php" class="nav-link history-link">
                        <img class="icon-history" src="/asset/logo_email.png" alt="Riwayat Surat">
                        <span>Riwayat Surat</span>
                    </a>
                </li>
            <?php endif; ?>
            <!-- ini pengaturan saya kurang tau mau diisi apa-->
            <!-- <li class="nav-item <?= $activeMenu === 'pengaturan' ? 'active-gradient' : '';?>">
                <a href="setting.php" class="nav-link history-link">
                    <img class="icon-history" src="/asset/setting_icon.png" alt="Pengaturan">
                    <span>Pengaturan</span>
                </a>
            </li> -->
            <li class="nav-item">
                <a href="logout.php" class="nav-link history-link">
                    <img class="icon-history" src="/asset/icon_logout.png" alt="Keluar">
                    <span>Keluar</span>
                </a>
            </li>
        </ul>
    </nav>
    <?php
}
?>
