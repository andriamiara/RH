<form method="post" action="<?= site_url('/logout') ?>" style="margin:0 0 0 auto">
    <?= csrf_field() ?>
    <button type="submit" style="background:none;border:none;color:rgba(255,255,255,.25);font-size:1.1rem;padding:0" title="Deconnexion">
        <i class="bi bi-box-arrow-right"></i>
    </button>
</form>
