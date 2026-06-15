<?php
function render_navbar($namaPengguna = 'Warga', $badgeCount = 1)
{
    ?>
    <header class="top-header">
        <div class="top-header-right">
            <div class="notifications">
                Halo, <?= htmlspecialchars($namaPengguna, ENT_QUOTES, 'UTF-8'); ?>!
                <i class="icon-bell"></i>
                <span class="notification-badge"><?= (int) $badgeCount; ?></span>
            </div>
            <div class="user-profile">
                <img src="asset/akun.png" alt="Profil pengguna" class="profile-img">
                <i class="icon-status-active"></i>
            </div>
        </div>
    </header>
    <?php
}
?>
