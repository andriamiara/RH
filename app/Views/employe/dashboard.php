<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>
<?php
if (! function_exists('dashboardTypeClass')) {
    function dashboardTypeClass(string $libelle): string
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

if (! function_exists('dashboardStatutClass')) {
    function dashboardStatutClass(string $statut): string
    {
        return match ($statut) {
            'approuvee' => 's-approuvee',
            'refusee'   => 's-refusee',
            'annulee'   => 's-annulee',
            default     => 's-attente',
        };
    }
}

$initiales = strtoupper(substr((string) ($user['prenom'] ?? 'E'), 0, 1) . substr((string) ($user['nom'] ?? 'M'), 0, 1));
$departementLabel = $departement['nom'] ?? 'Aucun departement';
?>
<div class="app-wrap">
    <aside class="sidebar">
        <div class="sidebar-brand"><div class="sidebar-logo-icon"><i class="bi bi-briefcase"></i></div><div class="sidebar-brand-name">TechMada RH<span>Espace employe</span></div></div>
        <div class="sidebar-section">Menu</div>
        <ul class="sidebar-nav">
            <li><a href="<?= site_url('/employe/dashboard') ?>" class="active"><i class="bi bi-grid-1x2"></i> Tableau de bord</a></li>
            <li><a href="<?= site_url('/employe/demandes/nouvelle') ?>"><i class="bi bi-plus-circle"></i> Nouvelle demande</a></li>
            <li><a href="<?= site_url('/employe/demandes') ?>"><i class="bi bi-calendar3"></i> Mes demandes<?php if ($stats['en_attente'] > 0): ?> <span class="nav-badge alert"><?= esc((string) $stats['en_attente']) ?></span><?php endif; ?></a></li>
            <li><a href="<?= site_url('/employe/profil') ?>"><i class="bi bi-person"></i> Mon profil</a></li>
        </ul>
        <div class="sidebar-user"><div class="s-user-row"><div class="avatar av-green"><?= esc($initiales) ?></div><div><div class="user-name"><?= esc(($user['prenom'] ?? '') . ' ' . ($user['nom'] ?? '')) ?></div><div class="user-role">Employe · <?= esc($departementLabel) ?></div></div><?= view('partials/logout_form') ?></div></div>
    </aside>
    <div class="main">
        <div class="topbar"><div><div class="topbar-title">Tableau de bord</div><div class="topbar-breadcrumb">Accueil · <?= esc($departementLabel) ?></div></div><div class="topbar-actions"><a href="<?= site_url('/employe/demandes/nouvelle') ?>" class="btn-forest" style="padding:7px 14px;font-size:.82rem"><i class="bi bi-plus-lg"></i> Nouvelle demande</a></div></div>
        <div class="content">
            <?php if (session()->getFlashdata('success')): ?><div class="flash flash-success"><i class="bi bi-check-circle-fill"></i><?= esc(session()->getFlashdata('success')) ?></div><?php endif; ?>
            <?php if (session()->getFlashdata('error')): ?><div class="flash flash-error"><i class="bi bi-exclamation-circle-fill"></i><?= esc(session()->getFlashdata('error')) ?></div><?php endif; ?>

            <div class="metrics">
                <div class="metric"><div class="metric-top"><div class="metric-icon mi-amber"><i class="bi bi-hourglass-split"></i></div></div><div class="metric-val"><?= esc((string) $stats['en_attente']) ?></div><div class="metric-label">En attente</div></div>
                <div class="metric"><div class="metric-top"><div class="metric-icon mi-green"><i class="bi bi-check-circle"></i></div></div><div class="metric-val"><?= esc((string) $stats['approuvee']) ?></div><div class="metric-label">Approuvees</div></div>
                <div class="metric"><div class="metric-top"><div class="metric-icon mi-forest"><i class="bi bi-calendar-check"></i></div></div><div class="metric-val"><?= esc((string) $totalRestant) ?></div><div class="metric-label">Jours restants</div><div class="metric-sub">tous soldes deductibles</div></div>
                <div class="metric"><div class="metric-top"><div class="metric-icon mi-blue"><i class="bi bi-person-slash"></i></div></div><div class="metric-val"><?= esc((string) $monthAbsenceDays) ?></div><div class="metric-label">Absence du mois</div><div class="metric-sub"><?= esc($currentMonthLabel) ?></div></div>
                <div class="metric"><div class="metric-top"><div class="metric-icon mi-red"><i class="bi bi-x-circle"></i></div></div><div class="metric-val"><?= esc((string) $stats['refusee']) ?></div><div class="metric-label">Refusees</div></div>
            </div>

            <div class="data-card">
                <div class="data-card-head"><h3>Mes soldes de conges - <?= esc((string) $referenceYear) ?></h3></div>
                <?php if ($soldes === []): ?>
                    <div class="empty"><i class="bi bi-piggy-bank"></i><p>Aucun solde initialise pour cet employe.</p></div>
                <?php else: ?>
                    <div style="padding:1rem 1.25rem;display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:1rem">
                        <?php foreach ($soldes as $solde): ?>
                            <?php $percent = $solde['jours_annuels'] > 0 ? max(0, min(100, ($solde['jours_restants'] / $solde['jours_annuels']) * 100)) : 0; ?>
                            <div class="solde-card" style="margin:0">
                                <div class="solde-header">
                                    <span class="solde-type"><?= esc($solde['libelle']) ?></span>
                                    <span class="solde-nums"><strong><?= esc((string) $solde['jours_restants']) ?></strong> / <?= esc((string) $solde['jours_annuels']) ?> j</span>
                                </div>
                                <div class="solde-bar"><div class="solde-fill<?= $solde['jours_restants'] <= 2 && $solde['deductible'] ? ' warn' : '' ?>" style="width:<?= esc((string) $percent) ?>%"></div></div>
                                <div class="solde-label"><?= esc((string) $solde['jours_restants']) ?> jours restants · <?= esc((string) $solde['jours_pris']) ?> pris</div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="data-card">
                <div class="data-card-head">
                    <h3>Mes dernieres demandes</h3>
                    <a href="<?= site_url('/employe/demandes') ?>" style="font-size:.8rem;color:var(--forest);text-decoration:none">Voir tout →</a>
                </div>
                <?php if ($requests === []): ?>
                    <div class="empty"><i class="bi bi-calendar-x"></i><p>Aucune demande enregistree pour le moment.</p></div>
                <?php else: ?>
                    <table class="tbl">
                        <thead><tr><th>Type</th><th>Du</th><th>Au</th><th>Duree</th><th>Statut</th><th>Action</th></tr></thead>
                        <tbody>
                            <?php foreach ($requests as $request): ?>
                                <tr>
                                    <td><span class="type-badge <?= esc(dashboardTypeClass($request['type_libelle'])) ?>"><?= esc($request['type_libelle']) ?></span></td>
                                    <td class="td-muted"><?= esc(date('d/m/Y', strtotime($request['date_debut']))) ?></td>
                                    <td class="td-muted"><?= esc(date('d/m/Y', strtotime($request['date_fin']))) ?></td>
                                    <td class="td-mono"><?= esc((string) $request['nb_jours']) ?> j</td>
                                    <td><span class="statut <?= esc(dashboardStatutClass($request['statut'])) ?>"><?= esc($request['statut']) ?></span></td>
                                    <td>
                                        <?php if (($request['statut'] ?? '') === 'en_attente'): ?>
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
        <div class="footer-app"><i class="bi bi-c-circle"></i> <?= esc((string) $currentYear) ?> <span>TechMada RH</span> - Projet CodeIgniter 4</div>
    </div>
</div>
<?= $this->endSection() ?>
