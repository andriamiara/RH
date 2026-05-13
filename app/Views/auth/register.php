<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>
<div class="auth-page geo-bg">
    <div class="auth-split" style="grid-template-columns:1fr 520px;max-width:1040px">
        <div class="auth-left">
            <div>
                <p class="auth-left-brand">TechMada RH<span>Creation de compte employe</span></p>
                <p class="auth-left-text" style="margin-top:2rem">
                    <strong>Inscription fonctionnelle.</strong>
                    Cette page cree un compte employe en session CI4 native, stocke le mot de passe avec <code>password_hash()</code> et initialise automatiquement les soldes annuels.
                </p>
            </div>
            <div class="auth-roles">
                <div class="role-pill"><i class="bi bi-shield-lock"></i><div><div class="role-pill-name">Regles appliquees</div><div class="role-pill-cred">CSRF sur POST · role employe par defaut</div></div></div>
                <div class="role-pill"><i class="bi bi-piggy-bank"></i><div><div class="role-pill-name">Initialisation</div><div class="role-pill-cred">Soldes crees selon les types de conge</div></div></div>
            </div>
        </div>

        <div class="auth-right">
            <p class="auth-title">Inscription</p>
            <p class="auth-sub">Creez un compte employe de demonstration.</p>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="flash flash-error"><i class="bi bi-exclamation-circle-fill"></i><?= esc(session()->getFlashdata('error')) ?></div>
            <?php endif; ?>

            <form method="post" action="<?= site_url('/register') ?>">
                <?= csrf_field() ?>
                <div class="form-grid-2">
                    <div class="f-group">
                        <label class="f-label">Prenom</label>
                        <input type="text" name="prenom" class="f-input" value="<?= esc(old('prenom')) ?>">
                        <?php if (session('errors.prenom')): ?><div class="f-error"><?= esc(session('errors.prenom')) ?></div><?php endif; ?>
                    </div>
                    <div class="f-group">
                        <label class="f-label">Nom</label>
                        <input type="text" name="nom" class="f-input" value="<?= esc(old('nom')) ?>">
                        <?php if (session('errors.nom')): ?><div class="f-error"><?= esc(session('errors.nom')) ?></div><?php endif; ?>
                    </div>
                    <div class="f-group">
                        <label class="f-label">Email</label>
                        <input type="email" name="email" class="f-input" value="<?= esc(old('email')) ?>">
                        <?php if (session('errors.email')): ?><div class="f-error"><?= esc(session('errors.email')) ?></div><?php endif; ?>
                    </div>
                    <div class="f-group">
                        <label class="f-label">Date d'embauche</label>
                        <input type="date" name="date_embauche" class="f-input" value="<?= esc(old('date_embauche', date('Y-m-d'))) ?>">
                        <?php if (session('errors.date_embauche')): ?><div class="f-error"><?= esc(session('errors.date_embauche')) ?></div><?php endif; ?>
                    </div>
                    <div class="f-group">
                        <label class="f-label">Departement</label>
                        <select name="departement_id" class="f-select">
                            <option value="">-- Choisir --</option>
                            <?php foreach ($departements as $departement): ?>
                                <option value="<?= esc($departement['id']) ?>" <?= old('departement_id') == $departement['id'] ? 'selected' : '' ?>><?= esc($departement['nom']) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <?php if (session('errors.departement_id')): ?><div class="f-error"><?= esc(session('errors.departement_id')) ?></div><?php endif; ?>
                    </div>
                    <div class="f-group">
                        <label class="f-label">Role</label>
                        <input type="text" class="f-input" value="employe" disabled>
                    </div>
                    <div class="f-group">
                        <label class="f-label">Mot de passe</label>
                        <input type="password" name="password" class="f-input">
                        <?php if (session('errors.password')): ?><div class="f-error"><?= esc(session('errors.password')) ?></div><?php endif; ?>
                    </div>
                    <div class="f-group">
                        <label class="f-label">Confirmation</label>
                        <input type="password" name="password_confirm" class="f-input">
                        <?php if (session('errors.password_confirm')): ?><div class="f-error"><?= esc(session('errors.password_confirm')) ?></div><?php endif; ?>
                    </div>
                </div>
                <button type="submit" class="btn-primary" style="margin-top:.5rem">Creer le compte <i class="bi bi-person-plus"></i></button>
            </form>

            <div class="auth-footer">
                Deja un compte ?
                <a href="<?= site_url('/login') ?>">Connexion</a>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
