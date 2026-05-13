<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>
<?php
if (! function_exists('formatStatutClass')) {
    function formatStatutClass(string $statut): string
    {
        return match ($statut) {
            'approuvee' => 's-approuvee',
            'refusee'   => 's-refusee',
            'annulee'   => 's-annulee',
            default     => 's-attente',
        };
    }
}

if (! function_exists('formatTypeClass')) {
    function formatTypeClass(string $libelle): string
    {
        $value = strtolower($libelle);

        return match (true) {
            str_contains($value, 'maladie') => 't-maladie',
            str_contains($value, 'special') => 't-special',
            str_contains($value, 'sans')    => 't-sans-solde',
            default                         => 't-annuel',
        };
    }
}
?>
<div class="app-wrap">
    <aside class="sidebar">
        <div class="sidebar-brand"><div class="sidebar-logo-icon"><i class="bi bi-briefcase"></i></div><div class="sidebar-brand-name">TechMada RH<span>Espace employe</span></div></div>
        <ul class="sidebar-nav" style="margin-top:1rem">
            <li><a href="<?= site_url('/employe/dashboard') ?>"><i class="bi bi-grid-1x2"></i> Tableau de bord</a></li>
            <li><a href="<?= site_url('/employe/demandes/nouvelle') ?>"><i class="bi bi-plus-circle"></i> Nouvelle demande</a></li>
            <li><a href="<?= site_url('/employe/demandes') ?>" class="active"><i class="bi bi-calendar3"></i> Mes demandes</a></li>
            <li><a href="<?= site_url('/employe/profil') ?>"><i class="bi bi-person"></i> Mon profil</a></li>
        </ul>
        <div class="sidebar-user"><div class="s-user-row"><div class="avatar av-green">SR</div><div><div class="user-name">Soa Rakoto</div><div class="user-role">Employe · IT</div></div><?= view('partials/logout_form') ?></div></div>
    </aside>
    <div class="main">
        <div class="topbar"><div><div class="topbar-title">Mes demandes de conge</div><div class="topbar-breadcrumb"><a href="<?= site_url('/employe/dashboard') ?>">Accueil</a><i class="bi bi-chevron-right" style="font-size:.6rem"></i> Mes demandes</div></div><div class="topbar-actions"><a href="<?= site_url('/employe/demandes/nouvelle') ?>" class="btn-forest" style="padding:7px 14px;font-size:.82rem"><i class="bi bi-plus-lg"></i> Nouvelle demande</a></div></div>
        <div class="content">
            <?php if (session()->getFlashdata('success')): ?><div class="flash flash-success"><i class="bi bi-check-circle-fill"></i><?= esc(session()->getFlashdata('success')) ?></div><?php endif; ?>
            <?php if (session()->getFlashdata('error')): ?><div class="flash flash-error"><i class="bi bi-exclamation-circle-fill"></i><?= esc(session()->getFlashdata('error')) ?></div><?php endif; ?>

            <div class="data-card">
                <div class="data-card-head"><h3>Toutes mes demandes</h3></div>
                <?php if ($requests === []): ?>
                    <div class="empty"><i class="bi bi-calendar-x"></i><p>Aucune demande enregistree pour le moment.</p></div>
                <?php else: ?>
                    <table class="tbl">
                        <thead><tr><th>Type</th><th>Debut</th><th>Fin</th><th>Duree</th><th>Statut</th><th>Commentaire RH</th><th>Action</th></tr></thead>
                        <tbody>
                            <?php foreach ($requests as $request): ?>
                                <tr>
                                    <td><span class="type-badge <?= esc(formatTypeClass($request['type_libelle'])) ?>"><?= esc($request['type_libelle']) ?></span></td>
                                    <td class="td-muted"><?= esc(date('d/m/Y', strtotime($request['date_debut']))) ?></td>
                                    <td class="td-muted"><?= esc(date('d/m/Y', strtotime($request['date_fin']))) ?></td>
                                    <td class="td-mono"><?= esc((string) $request['nb_jours']) ?> j</td>
                                    <td><span class="statut <?= esc(formatStatutClass($request['statut'])) ?>"><?= esc($request['statut']) ?></span></td>
                                    <td class="td-muted"><?= esc($request['commentaire_rh'] ?: '-') ?></td>
                                    <td>
                                        <?php if ($request['statut'] === 'en_attente'): ?>
                                            <form method="post" action="<?= site_url('/employe/demandes/' . $request['id'] . '/annuler') ?>" style="display:inline">
                                                <?= csrf_field() ?>
                                                <button class="btn-sm btn-cancel" type="submit"><i class="bi bi-x"></i> Annuler</button>
                                            </form>
                                        <?php else: ?>
                                            <span class="td-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>
        <div class="footer-app"><i class="bi bi-c-circle"></i> 2025 <span>TechMada RH</span></div>
    </div>
</div>
<?= $this->endSection() ?>
