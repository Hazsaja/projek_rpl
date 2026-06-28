<?php
function render_navbar($namaPengguna = 'Warga')
{
    ?>
    <header class="top-header">
        <div class="top-header-left">
            <button class="menu-toggle-btn" type="button" aria-label="Buka atau tutup menu">
                    <svg id="menu-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x w-5 h-5">
                        <path d="M18 6 6 18"></path>
                        <path d="m6 6 12 12"></path>
                    </svg>
            </button>
        </div>
        <div class="top-header-right">
            <div class="notifications">
                Halo, <?= htmlspecialchars($namaPengguna, ENT_QUOTES, 'UTF-8'); ?>
            </div>
            <div class="user-profile">
                <img src="asset/default_avatar.png" alt="Profil pengguna" class="profile-img">
                <i class="icon-status-active"></i>
            </div>
        </div>
    </header>
    <?php
}
?>
