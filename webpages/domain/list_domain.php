<div id="token" token="<?= $_SESSION["token"] ?>"></div>
<?php $tok = $_SESSION["token"]; $h = fn($v) => htmlspecialchars((string) $v); ?>

<style>
/* Estilos propios de la vista por dominio (autocontenidos, estilo Plesk).
   El breadcrumb es GLOBAL (lo pinta panel.php en todas las paginas). */
.dv-wrap { padding: .75rem 1.5rem 1.5rem; }
.dv-head { display:flex; align-items:center; gap:1rem; flex-wrap:wrap; margin-bottom:.75rem; }
.dv-head h1 { margin:0; font-size:1.6rem; font-weight:600; }
.dv-head .dv-meta { color:#6B7A88; font-size:.9rem; }
.dv-badge { display:inline-block; padding:.15rem .55rem; border-radius:999px; font-size:.75rem; font-weight:600; }
.dv-badge.ok { background:#E3F5EA; color:#1E7B45; } .dv-badge.warn { background:#FDF1DC; color:#9A6700; }
.dv-tabs { display:flex; gap:0; border-bottom:2px solid #E1E6EA; margin:1rem 0 1.25rem; flex-wrap:wrap; }
.dv-tab { padding:.6rem 1rem; cursor:pointer; color:#6B7A88; font-weight:500; border-bottom:2px solid transparent; margin-bottom:-2px; text-decoration:none; }
.dv-tab:hover { color:#1A73B8; } .dv-tab.active { color:#1A73B8; border-bottom-color:#1A73B8; }
.dv-pane { display:none; } .dv-pane.active { display:block; }
.dv-group { margin-bottom:1.5rem; } .dv-group h3 { font-size:1rem; font-weight:600; margin:0 0 .75rem; display:flex; align-items:center; gap:.6rem; flex-wrap:wrap; }
.dv-tiles { display:grid; grid-template-columns:repeat(auto-fill, minmax(230px,1fr)); gap:.6rem; }
.dv-tile { display:flex; align-items:center; gap:.75rem; padding:.7rem .85rem; background:#fff; border:1px solid #E1E6EA; border-radius:8px; text-decoration:none; color:#212529; cursor:pointer; }
.dv-tile:hover { border-color:#1A73B8; box-shadow:0 2px 8px rgba(33,37,41,.08); }
.dv-tile .ico { width:38px; height:38px; border-radius:8px; display:flex; align-items:center; justify-content:center; font-size:1.15rem; color:#fff; flex:0 0 38px; }
.dv-tile .t { font-weight:500; line-height:1.2; } .dv-tile .s { font-size:.78rem; color:#6B7A88; }
.dv-table { width:100%; border-collapse:collapse; background:#fff; border:1px solid #E1E6EA; border-radius:8px; overflow:hidden; }
.dv-table th, .dv-table td { padding:.6rem .85rem; text-align:left; border-bottom:1px solid #EEF1F4; font-size:.9rem; vertical-align:middle; }
.dv-table th { color:#6B7A88; font-weight:600; background:#F8FAFB; } .dv-table tr:last-child td { border-bottom:none; }
.dv-table a.row-link { color:#1A73B8; font-weight:600; text-decoration:none; } .dv-table a.row-link:hover { text-decoration:underline; }
.dv-actions a { color:#6B7A88; margin-left:.6rem; text-decoration:none; font-size:.85rem; } .dv-actions a:hover { color:#1A73B8; }
.dv-empty { padding:1rem; color:#6B7A88; background:#fff; border:1px solid #E1E6EA; border-radius:8px; }
.dv-btn { display:inline-block; padding:.45rem .9rem; border-radius:6px; background:#1A73B8; color:#fff !important; text-decoration:none; font-size:.85rem; font-weight:500; }
.dv-btn.sec { background:#E9EEF2; color:#212529 !important; }
.dv-fm { width:100%; height:calc(100vh - 280px); min-height:520px; border:1px solid #E1E6EA; border-radius:8px; background:#fff; }
/* --- Copias y WP Toolkit --- */
.dv-cards { display:grid; grid-template-columns:repeat(auto-fit, minmax(200px,1fr)); gap:.75rem; margin-bottom:1rem; }
.dv-card { background:#fff; border:1px solid #E1E6EA; border-radius:8px; padding:.9rem 1rem; display:flex; flex-direction:column; gap:.4rem; min-height:120px; }
.dv-card .h { display:flex; justify-content:space-between; align-items:center; font-weight:600; font-size:.95rem; }
.dv-card .h i { color:#6B7A88; } .dv-card .big { font-size:2rem; font-weight:600; line-height:1; } .dv-card .big small { font-size:.9rem; color:#6B7A88; font-weight:400; }
.dv-card .s { font-size:.8rem; color:#6B7A88; } .dv-card .f { margin-top:auto; display:flex; gap:.4rem; flex-wrap:wrap; align-items:center; }
.dv-btn.sm { padding:.3rem .65rem; font-size:.8rem; } .dv-btn.danger { background:#C0392B; } .dv-btn[disabled] { opacity:.5; cursor:default; }
button.dv-btn { border:0; cursor:pointer; font-family:inherit; }
.dv-subtabs { display:flex; gap:1.2rem; border-bottom:1px solid #E1E6EA; margin:.25rem 0 1rem; }
.dv-subtab { padding:.4rem 0; cursor:pointer; color:#6B7A88; border-bottom:2px solid transparent; margin-bottom:-1px; font-weight:500; font-size:.9rem; }
.dv-subtab.active { color:#212529; border-bottom-color:#1A73B8; }
.dv-risk { display:inline-block; min-width:110px; text-align:center; padding:.2rem .5rem; border-radius:4px; font-size:.72rem; font-weight:700; letter-spacing:.03em; background:#E9EEF2; color:#212529; }
.dv-risk.r1 { background:#FDF1DC; color:#9A6700; } .dv-risk.r2 { background:#FCE3D3; color:#B4460F; } .dv-risk.r3 { background:#F8D7DA; color:#A32633; } .dv-risk.r0 { background:#E3F5EA; color:#1E7B45; }
.dv-status { font-size:1rem; } .dv-status.ok { color:#1E7B45; } .dv-status.warn { color:#D9822B; } .dv-status.crit { color:#C0392B; } .dv-status.unknown { color:#B0BAC4; }
.dv-vulns { margin:.35rem 0 0; padding-left:1rem; font-size:.8rem; color:#6B7A88; } .dv-vulns a { color:#1A73B8; }
.dv-table td.act { text-align:right; white-space:nowrap; } .dv-table td.act a, .dv-table td.act button { color:#1A73B8; background:none; border:0; cursor:pointer; font:inherit; font-size:.85rem; margin-left:.8rem; text-decoration:none; }
.dv-table td.act a:hover, .dv-table td.act button:hover { text-decoration:underline; }
.dv-toggle { display:flex; align-items:center; gap:.7rem; padding:.55rem 0; border-bottom:1px solid #EEF1F4; font-size:.9rem; } .dv-toggle:last-child { border-bottom:0; }
.dv-toggle .sw { width:38px; height:22px; border-radius:11px; background:#CFD8E0; position:relative; cursor:pointer; flex:0 0 38px; transition:background .15s; }
.dv-toggle .sw::after { content:""; position:absolute; top:3px; left:3px; width:16px; height:16px; border-radius:50%; background:#fff; transition:left .15s; }
.dv-toggle .sw.on { background:#1A73B8; } .dv-toggle .sw.on::after { left:19px; } .dv-toggle .sw.off-dis { opacity:.4; cursor:default; }
.dv-toggle .hint { color:#6B7A88; font-size:.8rem; }
.dv-msg { padding:.6rem .85rem; border-radius:6px; font-size:.85rem; margin:.5rem 0; } .dv-msg.ok { background:#E3F5EA; color:#1E7B45; } .dv-msg.err { background:#F8D7DA; color:#A32633; } .dv-msg.info { background:#E8F1F9; color:#145C94; }
.dv-log { font-family:monospace; font-size:.78rem; white-space:pre-wrap; background:#fff; border:1px solid #E1E6EA; border-radius:6px; padding:.6rem .8rem; max-height:280px; overflow:auto; }
.dv-spin { display:inline-block; width:14px; height:14px; border:2px solid #CFD8E0; border-top-color:#1A73B8; border-radius:50%; animation:dvspin .8s linear infinite; vertical-align:middle; }
@keyframes dvspin { to { transform:rotate(360deg); } }
.dv-check { margin-right:.5rem; }
/* Modal de restauracion (doble lista tipo Plesk) */
.dv-modal-bg { position:fixed; inset:0; background:rgba(33,37,41,.45); z-index:1000; display:flex; align-items:flex-start; justify-content:center; padding:3vh 1rem; overflow:auto; }
.dv-modal { background:#F4F6F8; border-radius:10px; width:min(1000px, 100%); box-shadow:0 12px 40px rgba(0,0,0,.25); }
.dv-modal .mh { display:flex; align-items:center; justify-content:space-between; padding:1rem 1.25rem; border-bottom:1px solid #E1E6EA; background:#fff; border-radius:10px 10px 0 0; }
.dv-modal .mh h2 { margin:0; font-size:1.25rem; font-weight:600; } .dv-modal .mh .x { cursor:pointer; font-size:1.3rem; color:#6B7A88; background:none; border:0; }
.dv-modal .mb { padding:1rem 1.25rem; } .dv-modal .mf { padding:.9rem 1.25rem; border-top:1px solid #E1E6EA; display:flex; gap:.6rem; align-items:center; background:#fff; border-radius:0 0 10px 10px; }
.dv-form-row { display:grid; grid-template-columns:200px 1fr; gap:.6rem 1rem; align-items:start; margin-bottom:.8rem; font-size:.9rem; } .dv-form-row > label:first-child { color:#6B7A88; padding-top:.2rem; }
.dv-form-row select, .dv-form-row input[type=text], .dv-form-row input[type=search] { padding:.35rem .5rem; border:1px solid #CFD8E0; border-radius:5px; font:inherit; background:#fff; color:#212529; }
.dv-dual { display:grid; grid-template-columns:1fr 110px 1fr; gap:.75rem; align-items:stretch; }
.dv-dual .col h4 { margin:0 0 .4rem; font-size:.95rem; font-weight:600; } .dv-dual .col .tools { display:flex; gap:.4rem; align-items:center; margin-bottom:.4rem; } .dv-dual .col .tools input[type=search] { flex:1; }
.dv-list { background:#fff; border:1px solid #CFD8E0; border-radius:6px; min-height:230px; max-height:320px; overflow:auto; }
.dv-list .it { display:flex; align-items:center; gap:.5rem; padding:.45rem .7rem; border-bottom:1px solid #EEF1F4; cursor:pointer; font-size:.88rem; } .dv-list .it:hover { background:#E8F1F9; }
.dv-list .it .nm { flex:1; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; } .dv-list .it .sz { color:#6B7A88; font-size:.75rem; } .dv-list .it .op { color:#1A73B8; font-size:.8rem; }
.dv-list .empty { padding:1rem; color:#6B7A88; font-size:.85rem; }
.dv-dual .mid { display:flex; flex-direction:column; align-items:center; justify-content:center; text-align:center; color:#6B7A88; font-size:.75rem; gap:.5rem; }
.dv-crumb { font-family:monospace; font-size:.78rem; color:#6B7A88; margin-bottom:.35rem; word-break:break-all; } .dv-crumb a { color:#1A73B8; text-decoration:none; }
.dv-radio label { display:block; margin:.15rem 0; }
/* Modo oscuro (boton de la barra superior, html[data-elurk-theme="dark"]) */
html[data-elurk-theme="dark"] .dv-head h1 { color:#E4E8EC; }
html[data-elurk-theme="dark"] .dv-head .dv-meta, html[data-elurk-theme="dark"] .dv-tab, html[data-elurk-theme="dark"] .dv-tile .s,
html[data-elurk-theme="dark"] .dv-table th, html[data-elurk-theme="dark"] .dv-actions a, html[data-elurk-theme="dark"] .dv-empty { color:#9AA5B1; }
html[data-elurk-theme="dark"] .dv-tabs { border-bottom-color:#3A434C; }
html[data-elurk-theme="dark"] .dv-tab:hover, html[data-elurk-theme="dark"] .dv-tab.active { color:#7FB6EA; } html[data-elurk-theme="dark"] .dv-tab.active { border-bottom-color:#7FB6EA; }
html[data-elurk-theme="dark"] .dv-group h3 { color:#E4E8EC; }
html[data-elurk-theme="dark"] .dv-tile, html[data-elurk-theme="dark"] .dv-table, html[data-elurk-theme="dark"] .dv-empty, html[data-elurk-theme="dark"] .dv-fm { background:#2A3138; border-color:#3A434C; color:#E4E8EC; }
html[data-elurk-theme="dark"] .dv-tile:hover { border-color:#7FB6EA; box-shadow:0 2px 8px rgba(0,0,0,.35); }
html[data-elurk-theme="dark"] .dv-table th { background:#242B32; } html[data-elurk-theme="dark"] .dv-table th, html[data-elurk-theme="dark"] .dv-table td { border-bottom-color:#3A434C; }
html[data-elurk-theme="dark"] .dv-table td { color:#E4E8EC; }
html[data-elurk-theme="dark"] .dv-table a.row-link, html[data-elurk-theme="dark"] .dv-actions a:hover, html[data-elurk-theme="dark"] .dv-empty a { color:#7FB6EA; }
html[data-elurk-theme="dark"] .dv-btn.sec { background:#3A434C; color:#E4E8EC !important; }
html[data-elurk-theme="dark"] .dv-badge.ok { background:#1B3329; color:#7ED6B0; } html[data-elurk-theme="dark"] .dv-badge.warn { background:#3A2F1A; color:#E8B860; }
html[data-elurk-theme="dark"] .dv-card, html[data-elurk-theme="dark"] .dv-log, html[data-elurk-theme="dark"] .dv-list, html[data-elurk-theme="dark"] .dv-modal .mh, html[data-elurk-theme="dark"] .dv-modal .mf { background:#2A3138; border-color:#3A434C; color:#E4E8EC; }
html[data-elurk-theme="dark"] .dv-modal { background:#1F262C; } html[data-elurk-theme="dark"] .dv-modal .mh h2, html[data-elurk-theme="dark"] .dv-subtab.active, html[data-elurk-theme="dark"] .dv-toggle, html[data-elurk-theme="dark"] .dv-form-row { color:#E4E8EC; }
html[data-elurk-theme="dark"] .dv-form-row select, html[data-elurk-theme="dark"] .dv-form-row input { background:#1F262C; color:#E4E8EC; border-color:#3A434C; }
html[data-elurk-theme="dark"] .dv-list .it { border-bottom-color:#3A434C; } html[data-elurk-theme="dark"] .dv-list .it:hover { background:#34404B; }
html[data-elurk-theme="dark"] .dv-risk { background:#3A434C; color:#E4E8EC; } html[data-elurk-theme="dark"] .dv-subtabs { border-bottom-color:#3A434C; }
html[data-elurk-theme="dark"] .dv-toggle { border-bottom-color:#3A434C; } html[data-elurk-theme="dark"] .dv-msg.info { background:#1F3346; color:#9CC7F5; }
</style>

<div class="dv-wrap">
<?php if ($mode === "list"): ?>
    <!-- ======================= LISTA DE DOMINIOS ======================= -->
    <div class="dv-head">
        <h1><i class="fas fa-sitemap" style="color:#1A73B8;margin-right:.4rem;"></i>Dominios</h1>
        <span class="dv-meta"><?= count($domains) ?> dominio(s)<?= $isAdmin ? " · todos los usuarios" : "" ?></span>
        <span style="flex:1"></span>
        <a class="dv-btn" href="/add/web/?token=<?= $h($tok) ?>"><i class="fas fa-plus"></i> Añadir dominio</a>
    </div>
    <?php if (empty($domains)): ?>
        <div class="dv-empty">No hay dominios todavía.</div>
    <?php else: ?>
        <table class="dv-table">
            <thead><tr><th>Dominio</th><?php if ($isAdmin): ?><th>Usuario</th><?php endif; ?><th>IP</th><th>Disco</th><th>Tráfico</th><th>Estado</th></tr></thead>
            <tbody>
            <?php foreach ($domains as $d): ?>
                <tr>
                    <td><a class="row-link" href="/list/domain/?domain=<?= urlencode($d["domain"]) ?>"><?= $h($d["domain"]) ?></a>
                        <?php if ($d["ssl"]): ?> <i class="fas fa-lock" style="color:#1E7B45;font-size:.8rem;" title="SSL"></i><?php endif; ?></td>
                    <?php if ($isAdmin): ?><td><?= $h($d["user"]) ?></td><?php endif; ?>
                    <td style="font-family:monospace;font-size:.85rem;"><?= $h($d["ip"]) ?></td>
                    <td><?= $h($d["disk"]) ?> MB</td>
                    <td><?= $h($d["bw"]) ?> MB/mes</td>
                    <td><?= $d["suspended"] ? '<span class="dv-badge warn">Suspendido</span>' : '<span class="dv-badge ok">Activo</span>' ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

<?php else: ?>
    <!-- ======================= VISTA DE UN DOMINIO ======================= -->
    <?php
    $u = urlencode($domain);
    // Contexto de retorno para el breadcrumb global de las paginas nativas de
    // Hestia: from=<dominio>&tab=<pestana>. Asi "Editar buzon" sabe volver a
    // dominio.com > Correo.
    $back = fn(string $tab) => "&from=$u&tab=$tab";
    // Gestor de archivos (FileGator, router hash): el directorio va en ?cd= y es
    // RELATIVO al home del usuario conectado (su raiz). Al cargar ignora cd, asi
    // que el JS cambia el hash tras la carga para disparar su watcher de ruta.
    $fmCd  = preg_replace('#^/home/' . preg_quote($owner, '#') . '#', '', $docroot); // -> /web/dominio/public_html
    $fmUrl = "/fm/#/?cd=" . rawurlencode($fmCd);
    // El FM solo muestra los archivos del usuario CONECTADO: si un admin mira el
    // dominio de otro usuario, no podra ver sus archivos desde aqui.
    $fmForeign = ($owner !== $user);
    $editWebInfo = "/edit/web/?domain=$u" . $back("info") . "&token=" . $h($tok);
    $editWebHost = "/edit/web/?domain=$u" . $back("hosting") . "&token=" . $h($tok);
    $isSSL = (($web["SSL"] ?? "no") === "yes");
    // phpMyAdmin SSO (mismo esquema que Hestia en list_db): se firma con el
    // PROPIETARIO de la BD, igual que hace Hestia al impersonar.
    // Igual que Hestia (list_db.php): host SIN el puerto del panel (:8083) y URL
    // relativa al protocolo -> phpMyAdmin lo sirve el servidor web en el 443.
    [$__pmaHost] = explode(":", ($_SERVER["HTTP_HOST"] ?? "") . ":");
    // Si se accede al panel por IP pelada, el vhost de la IP no sirve phpMyAdmin/
    // webmail (Hestia los sirve en el vhost del HOSTNAME del servidor): usar el
    // hostname real (get_hostname() de Hestia) en ese caso.
    if (filter_var($__pmaHost, FILTER_VALIDATE_IP) && function_exists("get_hostname")) {
        $__hn = trim((string) get_hostname());
        if ($__hn !== "") { $__pmaHost = $__hn; }
    }
    $srvHost = $__pmaHost;
    $pmaBase = "//" . $__pmaHost . "/" . (!empty($_SESSION["DB_PMA_ALIAS"]) ? $_SESSION["DB_PMA_ALIAS"] : "phpmyadmin") . "/";
    $pmaSso = isset($_SESSION["PHPMYADMIN_KEY"]) && $_SESSION["PHPMYADMIN_KEY"] !== "" && function_exists("ipUsed") && !ipUsed();
    $pmaLink = function (string $dbName, array $db) use ($pmaBase, $pmaSso, $owner): string {
        if (!$pmaSso || (($db["TYPE"] ?? "") !== "mysql")) { return $pmaBase; }
        $time = time();
        $token = password_hash($dbName . $owner . $_SESSION["user_combined_ip"] . $time . $_SESSION["PHPMYADMIN_KEY"], PASSWORD_DEFAULT);
        return $pmaBase . "hestia-sso.php?" . http_build_query([
            "host" => $db["HOST"] ?? "localhost", "database" => $dbName, "user" => $owner, "exp" => $time, "hestia_token" => $token,
        ]);
    };
    ?>
    <div class="dv-head">
        <h1><?= $h($domain) ?></h1>
        <?= (($web["SUSPENDED"] ?? "no") === "yes") ? '<span class="dv-badge warn">Suspendido</span>' : '<span class="dv-badge ok">Activo</span>' ?>
        <span class="dv-meta">Usuario: <b><?= $h($owner) ?></b> · IP: <span style="font-family:monospace"><?= $h($web["IP"] ?? "") ?></span><?= $phpVersion ? " · PHP $phpVersion" : "" ?></span>
    </div>

    <div class="dv-tabs">
        <a class="dv-tab active" data-pane="info">Panel de información</a>
        <a class="dv-tab" data-pane="hosting">Hosting y DNS</a>
        <a class="dv-tab" data-pane="mail">Correo</a>
        <a class="dv-tab" data-pane="files">Archivos</a>
        <a class="dv-tab" data-pane="db">Bases de datos</a>
        <a class="dv-tab" data-pane="backup">Copias</a>
        <a class="dv-tab" data-pane="wp">WordPress</a>
    </div>

    <!-- ---- Panel de informacion ---- -->
    <div class="dv-pane active" id="pane-info" data-title="Panel de información">
        <div class="dv-group">
            <h3>Archivos y bases de datos</h3>
            <div class="dv-tiles">
                <a class="dv-tile" data-goto="files"><span class="ico" style="background:#2E9BD6"><i class="fas fa-folder-open"></i></span><span><div class="t">Archivos</div><div class="s">Gestor de archivos de este dominio</div></span></a>
                <a class="dv-tile" data-goto="db"><span class="ico" style="background:#7B4FB3"><i class="fas fa-database"></i></span><span><div class="t">Bases de datos</div><div class="s"><?= count($dbs) ?> base(s) de datos</div></span></a>
                <a class="dv-tile" href="<?= $editWebInfo ?>"><span class="ico" style="background:#4C9A2A"><i class="fas fa-right-left"></i></span><span><div class="t">FTP / conexión</div><div class="s">Usuario FTP y ruta</div></span></a>
                <a class="dv-tile" data-goto="backup"><span class="ico" style="background:#D9822B"><i class="fas fa-file-zipper"></i></span><span><div class="t">Backup y restauración</div><div class="s">Copias y restauración parcial</div></span></a>
            </div>
        </div>
        <div class="dv-group">
            <h3>Herramientas de desarrollo</h3>
            <div class="dv-tiles">
                <a class="dv-tile" href="<?= $editWebInfo ?>"><span class="ico" style="background:#5B6FBF"><i class="fab fa-php"></i></span><span><div class="t">PHP<?= $phpVersion ? " · versión $phpVersion" : "" ?></div><div class="s">Cambiar versión y ajustes</div></span></a>
                <a class="dv-tile" href="/list/log/?<?= ltrim($back("info"), "&") ?>"><span class="ico" style="background:#3C8DAD"><i class="fas fa-file-lines"></i></span><span><div class="t">Registros</div><div class="s">Logs de acceso y errores</div></span></a>
                <a class="dv-tile" href="/list/cron/?<?= ltrim($back("info"), "&") ?>"><span class="ico" style="background:#2B8A8A"><i class="fas fa-clock"></i></span><span><div class="t">Tareas programadas</div><div class="s">Cron</div></span></a>
                <a class="dv-tile" href="/list/stats/?<?= ltrim($back("info"), "&") ?>"><span class="ico" style="background:#B04A6E"><i class="fas fa-chart-line"></i></span><span><div class="t">Estadísticas</div><div class="s"><?= $h($web["U_DISK"] ?? 0) ?> MB · <?= $h($web["U_BANDWIDTH"] ?? 0) ?> MB/mes</div></span></a>
                <a class="dv-tile" data-goto="wp"><span class="ico" style="background:#21759B"><i class="fab fa-wordpress"></i></span><span><div class="t">WordPress</div><div class="s"><?= $isWP ? "Detectado en este dominio" : "No detectado" ?></div></span></a>
            </div>
        </div>
        <div class="dv-group">
            <h3>Seguridad</h3>
            <div class="dv-tiles">
                <a class="dv-tile" href="<?= $editWebInfo ?>"><span class="ico" style="background:<?= $isSSL ? '#1E7B45' : '#9A6700' ?>"><i class="fas fa-lock"></i></span><span><div class="t">Certificados SSL/TLS</div><div class="s"><?= $isSSL ? "Activo" . ((($web["LETSENCRYPT"] ?? "no") === "yes") ? " · Let's Encrypt" : "") : "Sin certificado" ?></div></span></a>
                <a class="dv-tile" data-goto="hosting"><span class="ico" style="background:#2E9BD6"><i class="fas fa-globe"></i></span><span><div class="t">DNS</div><div class="s"><?= count($dns) ?> registro(s)</div></span></a>
                <?php if ($isAdmin): ?>
                <a class="dv-tile" href="/list/security/?<?= ltrim($back("info"), "&") ?>"><span class="ico" style="background:#C0392B"><i class="fas fa-shield-halved"></i></span><span><div class="t">Fail2ban / IPs</div><div class="s">Baneos, lista blanca y negra</div></span></a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- ---- Hosting y DNS ---- -->
    <div class="dv-pane" id="pane-hosting" data-title="Hosting y DNS">
        <div class="dv-group">
            <h3>Hosting <a class="dv-btn sec" href="<?= $editWebHost ?>"><i class="fas fa-pen"></i> Editar</a></h3>
            <table class="dv-table">
                <tr><th style="width:220px">Raíz de documentos</th><td style="font-family:monospace;font-size:.85rem"><?= $h($docroot) ?></td></tr>
                <tr><th>IP</th><td><?= $h($web["IP"] ?? "") ?></td></tr>
                <tr><th>Plantilla web</th><td><?= $h($web["TPL"] ?? "") ?><?= !empty($web["PROXY"]) ? " · proxy " . $h($web["PROXY"]) : "" ?></td></tr>
                <tr><th>Backend PHP</th><td><?= $h($web["BACKEND"] ?? "") ?><?= $phpVersion ? " (PHP $phpVersion)" : "" ?></td></tr>
                <tr><th>Alias</th><td><?= $h($web["ALIAS"] ?? "—") ?></td></tr>
                <tr><th>SSL</th><td><?= $isSSL ? "Sí" : "No" ?><?= (($web["LETSENCRYPT"] ?? "no") === "yes") ? " · Let's Encrypt" : "" ?></td></tr>
                <tr><th>Estadísticas</th><td><?= $h($web["STATS"] ?? "—") ?></td></tr>
            </table>
        </div>
        <div class="dv-group">
            <h3>DNS <a class="dv-btn sec" href="/list/dns/?domain=<?= $u . $back("hosting") ?>"><i class="fas fa-pen"></i> Gestionar zona</a></h3>
            <?php if (empty($dns)): ?>
                <div class="dv-empty">Este dominio no tiene zona DNS en este servidor.</div>
            <?php else: ?>
                <table class="dv-table">
                    <thead><tr><th>Registro</th><th>Tipo</th><th>Valor</th><th>Prioridad</th></tr></thead>
                    <tbody>
                    <?php foreach ($dns as $r): ?>
                        <tr><td style="font-family:monospace"><?= $h($r["RECORD"] ?? "") ?></td><td><b><?= $h($r["TYPE"] ?? "") ?></b></td><td style="font-family:monospace;font-size:.85rem;word-break:break-all"><?= $h($r["VALUE"] ?? "") ?></td><td><?= $h($r["PRIORITY"] ?? "") ?></td></tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>

    <!-- ---- Correo ---- -->
    <div class="dv-pane" id="pane-mail" data-title="Correo">
        <div class="dv-group">
            <h3>Cuentas de correo de <?= $h($domain) ?>
                <?php if ($hasMail): ?><a class="dv-btn" href="/add/mail/?domain=<?= $u . $back("mail") ?>&token=<?= $h($tok) ?>"><i class="fas fa-plus"></i> Crear cuenta</a><a class="dv-btn sec" href="/edit/mail/?domain=<?= $u . $back("mail") ?>&token=<?= $h($tok) ?>"><i class="fas fa-gear"></i> Config. del dominio</a><?php endif; ?>
            </h3>
            <?php if (!$hasMail): ?>
                <div class="dv-empty">Este dominio no tiene correo activado. <a href="/add/mail/?<?= ltrim($back("mail"), "&") ?>&token=<?= $h($tok) ?>">Añadir dominio de correo</a>.</div>
            <?php elseif (empty($mail)): ?>
                <div class="dv-empty">Sin buzones todavía.</div>
            <?php else: ?>
                <?php
                // Webmail (Roundcube) integrado de Hestia: mismo host sin el puerto del
                // panel, alias WEBMAIL_ALIAS (por defecto "webmail"). Roundcube acepta
                // ?_user= para dejar la direccion ya rellenada en el login.
                // Igual que Hestia (list_mail_acc.php): el webmail vive en el subdominio
                // <WEBMAIL_ALIAS>.<dominio> (por defecto webmail.dominio), en http como
                // hace Hestia. Requiere que ese subdominio resuelva por DNS (en un lab
                // por IP: entrada en /etc/hosts o probar por hostname).
                $webmailBase = "http://" . (!empty($_SESSION["WEBMAIL_ALIAS"]) ? $_SESSION["WEBMAIL_ALIAS"] : "webmail") . "." . $domain . "/";
                $fmtList = function ($v) use ($h): string {
                    $v = trim((string) $v);
                    if ($v === "" || $v === "no") { return '<span style="color:#B0BAC4">—</span>'; }
                    return $h(str_replace(",", ", ", $v));
                };
                ?>
                <table class="dv-table">
                    <thead><tr><th>Dirección</th><th>Alias</th><th>Reenvío a</th><th>Cuota</th><th>Usado</th><th>Estado</th><th></th></tr></thead>
                    <tbody>
                    <?php foreach ($mail as $acc => $m): ?>
                        <?php
                        $editAcc = "/edit/mail/?" . http_build_query(["domain" => $domain, "account" => $acc, "from" => $domain, "tab" => "mail", "token" => $tok]);
                        $wmUrl   = $webmailBase . "?_user=" . rawurlencode($acc . "@" . $domain);
                        $fwdOnly = (($m["FWD_ONLY"] ?? "no") === "yes");
                        ?>
                        <tr>
                            <td><a class="row-link" href="<?= $h($editAcc) ?>" title="Editar cuenta (alias, reenvío, autorespuesta, contraseña)"><?= $h($acc) ?>@<?= $h($domain) ?></a>
                                <?php if (($m["AUTOREPLY"] ?? "no") === "yes"): ?> <i class="fas fa-reply" style="color:#6B7A88;font-size:.75rem" title="Autorespuesta activa"></i><?php endif; ?></td>
                            <td style="font-size:.85rem"><?= $fmtList($m["ALIAS"] ?? "") ?></td>
                            <td style="font-size:.85rem"><?= $fmtList($m["FWD"] ?? "") ?><?= $fwdOnly ? ' <span class="dv-badge warn" title="Solo reenvía, no guarda copia">solo reenvío</span>' : '' ?></td>
                            <td><?= ($m["QUOTA"] ?? "unlimited") === "unlimited" ? "∞" : $h($m["QUOTA"]) . " MB" ?></td>
                            <td><?= $h($m["U_DISK"] ?? 0) ?> MB</td>
                            <td><?= (($m["SUSPENDED"] ?? "no") === "yes") ? '<span class="dv-badge warn">Suspendido</span>' : '<span class="dv-badge ok">Activo</span>' ?></td>
                            <td class="dv-actions" style="text-align:right;white-space:nowrap">
                                <a href="<?= $h($editAcc) ?>" title="Editar: alias, reenvío, autorespuesta, contraseña"><i class="fas fa-pen"></i> Editar</a>
                                <a href="<?= $h($wmUrl) ?>" target="_blank" title="Abrir esta cuenta en el webmail"><i class="fas fa-envelope-open" style="color:#1A73B8"></i> Webmail</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>

    <!-- ---- Archivos: gestor embebido, posicionado en el docroot ---- -->
    <div class="dv-pane" id="pane-files" data-title="Archivos">
        <div class="dv-group">
            <h3>Archivos de <?= $h($domain) ?>
                <span class="dv-meta" style="font-weight:normal;font-family:monospace;font-size:.8rem"><?= $h($docroot) ?></span>
                <a class="dv-btn sec" href="<?= $h($fmUrl) ?>" target="_blank"><i class="fas fa-arrow-up-right-from-square"></i> Abrir aparte</a>
            </h3>
            <?php if ($fmForeign): ?>
                <div class="dv-empty" style="margin-bottom:.75rem"><i class="fas fa-circle-info"></i> El gestor de archivos muestra los archivos del usuario conectado (<b><?= $h($user) ?></b>), no los de <b><?= $h($owner) ?></b>. Para gestionar los archivos de este dominio, entra como su propietario desde <a href="/list/user/">Usuarios</a> ("iniciar sesión como").</div>
            <?php endif; ?>
            <!-- src base + hash con ?cd= aplicado tras la carga (dispara el watcher de ruta de FileGator) -->
            <iframe class="dv-fm" id="dv-fm" data-base="/fm/#/" data-hash="#/?cd=<?= $h(rawurlencode($fmCd)) ?>" title="Gestor de archivos"></iframe>
        </div>
    </div>

    <!-- ---- Bases de datos ---- -->
    <div class="dv-pane" id="pane-db" data-title="Bases de datos">
        <div class="dv-group">
            <h3>Bases de datos del usuario <?= $h($owner) ?>
                <a class="dv-btn" href="/add/db/?<?= ltrim($back("db"), "&") ?>&token=<?= $h($tok) ?>"><i class="fas fa-plus"></i> Añadir base de datos</a>
                <a class="dv-btn sec" href="<?= $h($pmaBase) ?>" target="_blank"><i class="fas fa-table"></i> phpMyAdmin</a>
            </h3>
            <p class="dv-meta" style="margin-top:0">Hestia asocia las bases de datos al usuario, no al dominio: aquí ves todas las del propietario. Pulsa el nombre para gestionar usuario y contraseña.</p>
            <?php if (empty($dbs)): ?>
                <div class="dv-empty">Sin bases de datos.</div>
            <?php else: ?>
                <table class="dv-table">
                    <thead><tr><th>Base de datos</th><th>Usuario BD</th><th>Tipo</th><th>Host</th><th>Tamaño</th><th></th></tr></thead>
                    <tbody>
                    <?php foreach ($dbs as $name => $db): ?>
                        <?php $editDb = "/edit/db/?" . http_build_query(["database" => $name, "from" => $domain, "tab" => "db", "token" => $tok]); ?>
                        <tr>
                            <td><a class="row-link" href="<?= $h($editDb) ?>" title="Editar base de datos"><?= $h($name) ?></a></td>
                            <td><?= $h($db["DBUSER"] ?? "") ?></td>
                            <td><?= $h($db["TYPE"] ?? "") ?></td>
                            <td><?= $h($db["HOST"] ?? "") ?></td>
                            <td><?= $h($db["U_DISK"] ?? 0) ?> MB</td>
                            <td class="dv-actions" style="text-align:right;white-space:nowrap">
                                <a href="<?= $h($editDb) ?>" title="Editar usuario/contraseña"><i class="fas fa-pen"></i></a>
                                <a href="<?= $h($pmaLink($name, $db)) ?>" target="_blank" title="Abrir en phpMyAdmin"><i class="fas fa-right-to-bracket" style="color:#D9822B"></i> phpMyAdmin</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>

    <!-- ---- Copias de seguridad (tipo Plesk: lista + restauracion parcial con doble lista) ---- -->
    <div class="dv-pane" id="pane-backup" data-title="Copias">
        <div class="dv-group">
            <h3>Copias de seguridad de <?= $h($owner) ?>
                <span class="dv-meta" style="font-weight:normal">las copias de Hestia son por usuario: incluyen <?= $h($domain) ?> y el resto de sus dominios</span>
                <button class="dv-btn sm" id="bk-create" type="button"><i class="fas fa-plus"></i> Crear copia</button>
                <button class="dv-btn sm" id="bk-create-restic" type="button" hidden><i class="fas fa-layer-group"></i> Crear incremental</button>
                <button class="dv-btn sec sm" id="bk-reload" type="button"><i class="fas fa-rotate"></i> Actualizar</button>
                <a class="dv-btn sec sm" href="/list/backup/?<?= ltrim($back("backup"), "&") ?>"><i class="fas fa-gear"></i> Gestión nativa</a>
            </h3>
            <div id="bk-progress" hidden>
                <div class="dv-msg info"><span class="dv-spin"></span> <span id="bk-progress-title">Tarea en curso…</span></div>
                <div class="dv-log" id="bk-log"></div>
            </div>
            <div id="bk-msg"></div>
            <p class="dv-meta" id="bk-retention" style="margin:.25rem 0 .6rem"></p>
            <?php if ($isAdmin): ?>
            <div class="dv-card" id="bk-admin" style="margin-bottom:1rem;min-height:0">
                <div class="h">Programación y retención de <?= $h($owner) ?> <i class="fas fa-calendar-days"></i></div>
                <div class="dv-form-row" style="margin-top:.5rem">
                    <label>Conservar</label>
                    <div><select id="bk-ret-sel"><option value="all">Todas las copias (las borro yo a mano)</option><option value="3">Las 3 últimas</option><option value="7">Las 7 últimas</option><option value="14">Las 14 últimas</option><option value="30">Las 30 últimas</option></select>
                        <button class="dv-btn sm" id="bk-ret-save" type="button">Guardar</button>
                        <div class="dv-meta" style="margin-top:.3rem">Cada copia completa ocupa el tamaño entero del usuario: vigila el disco si conservas todas. Si reasignas el paquete al usuario, Hestia vuelve a poner el valor del paquete.</div></div>
                </div>
                <div class="dv-form-row" style="margin-bottom:0">
                    <label>Copia programada</label>
                    <div>
                        <select id="bk-sch-freq"><option value="none">Sin programación propia</option><option value="daily">Diaria</option><option value="weekly">Semanal</option><option value="monthly">Mensual</option></select>
                        <select id="bk-sch-wday" hidden><option value="1">lunes</option><option value="2">martes</option><option value="3">miércoles</option><option value="4">jueves</option><option value="5">viernes</option><option value="6">sábado</option><option value="0">domingo</option></select>
                        <select id="bk-sch-mday" hidden></select>
                        a las <select id="bk-sch-hour"></select>:<select id="bk-sch-min"><option value="0">00</option><option value="15">15</option><option value="30">30</option><option value="45">45</option></select>
                        <select id="bk-sch-kind"><option value="full">completa</option><option value="restic" hidden>incremental (restic)</option></select>
                        <button class="dv-btn sm" id="bk-sch-save" type="button">Guardar</button>
                        <div class="dv-meta" id="bk-sch-info" style="margin-top:.3rem"></div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            <div id="bk-list"><div class="dv-empty"><span class="dv-spin"></span> Cargando copias…</div></div>
        </div>
    </div>

    <!-- ---- WordPress Toolkit (soberano): riesgo, componentes, medidas, herramientas ---- -->
    <div class="dv-pane" id="pane-wp" data-title="WordPress">
        <div class="dv-group">
            <?php if (!$isWP): ?>
                <h3>WordPress en <?= $h($domain) ?></h3>
                <div class="dv-empty">No se ha detectado WordPress en este dominio. Puedes instalarlo desde <a href="<?= $editWebInfo ?>">Editar dominio → Quick Install App</a>.</div>
            <?php else: ?>
                <h3>Estado de seguridad de WordPress
                    <span class="dv-meta" id="wp-checked" style="font-weight:normal"></span>
                    <button class="dv-btn sec sm" id="wp-refresh" type="button"><i class="fas fa-rotate"></i> Comprobar de nuevo</button>
                </h3>
                <div id="wp-msg"></div>
                <div id="wp-loading" class="dv-empty"><span class="dv-spin"></span> Analizando WordPress (WP-CLI + vulnerabilidades)… la primera vez tarda un poco.</div>
                <div id="wp-body" hidden>
                    <div class="dv-cards" id="wp-cards"></div>
                    <div class="dv-subtabs">
                        <span class="dv-subtab active" data-sub="components">Componentes vulnerables</span>
                        <span class="dv-subtab" data-sub="measures">Medidas de seguridad</span>
                        <span class="dv-subtab" data-sub="tools">Herramientas</span>
                    </div>
                    <div id="wp-sub-components"></div>
                    <div id="wp-sub-measures" hidden></div>
                    <div id="wp-sub-tools" hidden></div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Contexto para el JS de Copias/WordPress -->
    <div id="dv-ctx" hidden data-domain="<?= $h($domain) ?>" data-owner="<?= $h($owner) ?>" data-admin="<?= $isAdmin ? 1 : 0 ?>" data-wp="<?= $isWP ? 1 : 0 ?>" data-mail="<?= $hasMail ? 1 : 0 ?>"></div>

    <script>
    (function () {
        var tabs  = document.querySelectorAll(".dv-tab[data-pane]");
        var crumb = document.getElementById("dv-crumb-tab"); /* lo pinta el breadcrumb global */
        var fm    = document.getElementById("dv-fm");
        function show(name) {
            document.querySelectorAll(".dv-pane").forEach(function (p) { p.classList.toggle("active", p.id === "pane-" + name); });
            tabs.forEach(function (t) { t.classList.toggle("active", t.dataset.pane === name); });
            var pane = document.getElementById("pane-" + name);
            if (crumb && pane) { crumb.textContent = pane.dataset.title || name; }
            // Archivos: cargar el FM en su raiz y, una vez cargado, cambiar el hash a
            // #/?cd=<ruta> para que FileGator (router hash) entre en el docroot.
            if (name === "files" && fm && !fm.getAttribute("src")) {
                // Estilos inyectados en el iframe (mismo origen): tema CLARO con la
                // paleta del panel, sin la barra superior de FileGator (logo, salir...)
                // y a ancho completo. No depende del CSS del servidor ni de su cache.
                var FM_CSS = [
                    "html,body,.navbar{background:#F4F6F8!important;color:#212529!important}",
                    ".navbar{display:none!important}",
                    ".container,.section,.hero,.main{max-width:100%!important;width:100%!important;padding:.35rem .5rem!important;margin:0!important}",
                    ".table.is-hoverable tbody tr:nth-child(odd){background:#FFFFFF!important}",
                    ".table.is-hoverable tbody tr:nth-child(even){background:#FAFBFC!important}",
                    ".table.is-hoverable tbody tr:not(.is-selected):hover{background:#E8F1F9!important}",
                    ".table td,.table th{color:#212529!important;border-color:#EEF1F4!important}",
                    ".table tr.is-selected,.is-selected{background:#D6E7F5!important;color:#212529!important}",
                    ".file-row.type-file a.name:not([data-v])::before{color:#6B7A88!important}",
                    ".breadcrumb a:not([data-v]){background:#FFFFFF!important;color:#1A73B8!important;border:1px solid #E1E6EA!important}",
                    ".breadcrumb a:not([data-v]):hover{background:#E8F1F9!important;color:#145C94!important}",
                    ".breadcrumb li+li::before{color:#B0BAC4!important}",
                    ".dropdown-trigger .button{color:#212529!important}",
                    ".dropdown-trigger .button:hover{color:#1A73B8!important;background:#E8F1F9!important;border-radius:6px!important}",
                    ".button.is-primary,.tree-list .button.is-primary{background:#1A73B8!important;border-color:#1A73B8!important;color:#fff!important}",
                    ".button.is-primary:hover,.tree-list .button.is-primary:hover{background:#145C94!important}",
                    ".tree-list a:not([data-v]):hover{background:#E8F1F9!important}",
                    ".box,.progress-box .box,.modal-card,.modal-card-body,.modal-card-head,.modal-card-foot,.dropdown-content{background:#FFFFFF!important;color:#212529!important}",
                    "a,.has-text-primary{color:#1A73B8!important}",
                    /* Texto OSCURO en todo el gestor (el css oscuro de Hestia deja textos en blanco) */
                    "body,p,span,li,td,th,label,.label,.title,.subtitle,.help,.table,.file-row a,.file-row a.name,.breadcrumb li,.dropdown-item,.dropdown-content,.tree-list,.tree-list a,.pagination-link,.modal-card,.box,.card,.card-content,.navbar-item,.level-item,.column,.input,.textarea,.select select{color:#212529!important}",
                    ".input,.textarea,.select select{background:#FFFFFF!important;border-color:#CFD8E0!important}",
                    ".input::placeholder,.textarea::placeholder{color:#8A96A3!important}",
                    /* Botones del gestor: claros con texto oscuro; solo el primario azul+blanco */
                    ".button:not(.is-primary),.button:not(.is-primary) *,.dropdown-trigger .button,.dropdown-trigger .button *{background:#FFFFFF!important;color:#212529!important;border-color:#CFD8E0!important}",
                    ".button:not(.is-primary):hover{background:#E8F1F9!important;color:#1A73B8!important}",
                    ".button.is-primary,.button.is-primary *,.tag.is-primary,.tag.is-primary *,.is-selected .tag{background:#1A73B8!important;border-color:#1A73B8!important;color:#fff!important}",
                    /* Botones de accion (Anadir ficheros, Nuevo...) AZULES con texto blanco.
                       Hestia usa `#multi-actions a:not([data-v])` (un id) y ganaba a `.button`. */
                    "#multi-actions a:not([data-v]),#multi-actions .upload a:not([data-v]),#multi-actions a:not([data-v]) *,#multi-actions .upload a:not([data-v]) *{background:#1A73B8!important;border-color:#1A73B8!important;color:#FFFFFF!important}",
                    "#multi-actions a:not([data-v]):hover,#multi-actions .upload a:not([data-v]):hover,#multi-actions a:not([data-v]):hover *{background:#145C94!important;color:#FFFFFF!important}",
                    /* ...y sus opciones desplegadas, blancas con texto oscuro */
                    "#multi-actions a.dropdown-item:not([data-v]),#multi-actions a.dropdown-item:not([data-v]) *,#multi-actions .dropdown-menu a:not([data-v]),#multi-actions .dropdown-menu a:not([data-v]) *,#multi-actions a:not([data-v]) .dropdown-item,#multi-actions .dropdown-content{background:#FFFFFF!important;color:#212529!important;border-color:transparent!important}",
                    "#multi-actions a.dropdown-item:not([data-v]):hover,#multi-actions a.dropdown-item:not([data-v]):hover *,#multi-actions .dropdown-menu a:not([data-v]):hover,#multi-actions .dropdown-menu a:not([data-v]):hover *,#multi-actions a:not([data-v]) .dropdown-item:hover{background:#E8F1F9!important;color:#1A73B8!important}",
                    /* Cabecera de la tabla AZUL con texto blanco (salia negra con texto negro) */
                    ".table thead,.table thead tr,.table thead th,.b-table .table thead th,.table thead th *,.table thead .th-wrap,.table thead .th-wrap *,.table thead .icon,.table thead svg{background:#1A73B8!important;color:#FFFFFF!important;border-color:#1A73B8!important;fill:#FFFFFF!important}",
                    ".table thead th{padding:6px 4px!important}",
                    ".table thead .checkbox .check,.table thead .b-checkbox .check{border-color:#FFFFFF!important}",
                    /* Editor de codigo (vue-prism-editor) en CLARO: Hestia lo forzaba a oscuro sin condicion */
                    ":not(pre)>code[class*=\"language-\"],pre[class*=\"language-\"],.prism-editor__container,.prism-editor__editor,.prism-editor-wrapper{background:#FFFFFF!important;color:#212529!important;text-shadow:none!important}",
                    "code[class*=\"language-\"],pre[class*=\"language-\"],.prism-editor__editor,.prism-editor__editor *{color:#212529!important;text-shadow:none!important}",
                    ".prism-editor-wrapper{border:1px solid #CFD8E0!important;border-radius:6px!important}",
                    ".prism-editor__line-numbers{background:#F4F6F8!important;color:#8A96A3!important;border-right:1px solid #E1E6EA!important}",
                    ".prism-editor__line-number{color:#8A96A3!important}",
                    ".prism-editor__textarea{caret-color:#212529!important}",
                    "code[class*=\"language-\"]::selection,code[class*=\"language-\"] ::selection,pre[class*=\"language-\"]::selection,pre[class*=\"language-\"] ::selection{background:#D6E7F5!important}",
                    ".token.comment,.token.prolog,.token.doctype,.token.cdata{color:#708090!important}",
                    ".token.punctuation{color:#6B7A88!important}",
                    ".token.property,.token.tag,.token.boolean,.token.number,.token.constant,.token.symbol,.token.deleted{color:#990055!important}",
                    ".token.selector,.token.attr-name,.token.string,.token.char,.token.builtin,.token.inserted{color:#669900!important}",
                    ".token.operator,.token.entity,.token.url{color:#9A6E3A!important}",
                    ".token.atrule,.token.attr-value,.token.keyword{color:#0077AA!important}",
                    ".token.function,.token.class-name{color:#DD4A68!important}",
                    ".token.regex,.token.important,.token.variable{color:#EE9900!important}"
                ].join("");
                // Variante OSCURA del gestor (se anade tras las reglas claras cuando el
                // panel esta en html[data-elurk-theme="dark"]; misma paleta que el panel).
                var FM_DARK = [
                    "html,body{background:#1F262C!important;color:#E4E8EC!important}",
                    ".table.is-hoverable tbody tr:nth-child(odd){background:#2A3138!important}",
                    ".table.is-hoverable tbody tr:nth-child(even){background:#252C33!important}",
                    ".table.is-hoverable tbody tr:not(.is-selected):hover{background:#34404B!important}",
                    ".table tr.is-selected,.is-selected{background:#2F4A66!important}",
                    ".table,.b-table .table{border-color:#3A434C!important}",
                    ".table td,.table tbody th,.is-selected td{color:#E4E8EC!important;border-color:#3A434C!important}",
                    ".file-row.type-file a.name:not([data-v])::before{color:#9AA5B1!important}",
                    ".breadcrumb a:not([data-v]){background:#2A3138!important;color:#7FB6EA!important;border-color:#3A434C!important}",
                    ".breadcrumb a:not([data-v]):hover{background:#34404B!important;color:#9CC7F5!important}",
                    ".breadcrumb li+li::before{color:#6B7A88!important}",
                    ".box,.progress-box .box,.modal-card,.modal-card-body,.modal-card-head,.modal-card-foot,.dropdown-content{background:#2A3138!important;color:#E4E8EC!important}",
                    "body,p,span,li,td,th,label,.label,.title,.subtitle,.help,.table,.file-row a,.file-row a.name,.breadcrumb li,.dropdown-item,.dropdown-content,.tree-list,.tree-list a,.pagination-link,.modal-card,.box,.card,.card-content,.level-item,.column{color:#E4E8EC!important}",
                    ".input,.textarea,.select select{background:#1F262C!important;color:#E4E8EC!important;border-color:#3A434C!important}",
                    ".input::placeholder,.textarea::placeholder{color:#7C8792!important}",
                    ".button:not(.is-primary),.button:not(.is-primary) *,.dropdown-trigger .button,.dropdown-trigger .button *{background:#3A434C!important;color:#E4E8EC!important;border-color:#46505A!important}",
                    ".button:not(.is-primary):hover{background:#46505A!important;color:#FFFFFF!important}",
                    ".tree-list a:not([data-v]):hover{background:#34404B!important}",
                    "a,.has-text-primary{color:#7FB6EA!important}",
                    "#multi-actions a.dropdown-item:not([data-v]),#multi-actions a.dropdown-item:not([data-v]) *,#multi-actions .dropdown-menu a:not([data-v]),#multi-actions .dropdown-menu a:not([data-v]) *,#multi-actions a:not([data-v]) .dropdown-item,#multi-actions .dropdown-content{background:#2A3138!important;color:#E4E8EC!important}",
                    "#multi-actions a.dropdown-item:not([data-v]):hover,#multi-actions a.dropdown-item:not([data-v]):hover *,#multi-actions .dropdown-menu a:not([data-v]):hover,#multi-actions .dropdown-menu a:not([data-v]):hover *,#multi-actions a:not([data-v]) .dropdown-item:hover{background:#34404B!important;color:#9CC7F5!important}",
                    /* La cabecera azul y los botones de accion azules son iguales en ambos modos */
                    ".table thead,.table thead tr,.table thead th,.b-table .table thead th,.table thead th *,.table thead .th-wrap,.table thead .th-wrap *{background:#1A73B8!important;color:#FFFFFF!important;border-color:#1A73B8!important}",
                    /* Editor de codigo en OSCURO (mismo gris del panel) con sintaxis coloreada */
                    ":not(pre)>code[class*=\"language-\"],pre[class*=\"language-\"],.prism-editor__container,.prism-editor__editor,.prism-editor-wrapper{background:#1F262C!important;color:#E4E8EC!important}",
                    "code[class*=\"language-\"],pre[class*=\"language-\"],.prism-editor__editor,.prism-editor__editor *{color:#E4E8EC!important}",
                    ".prism-editor-wrapper{border-color:#3A434C!important}",
                    ".prism-editor__line-numbers{background:#2A3138!important;color:#7C8792!important;border-right-color:#3A434C!important}",
                    ".prism-editor__line-number{color:#7C8792!important}",
                    ".prism-editor__textarea{caret-color:#E4E8EC!important}",
                    "code[class*=\"language-\"]::selection,code[class*=\"language-\"] ::selection,pre[class*=\"language-\"]::selection,pre[class*=\"language-\"] ::selection{background:#2F4A66!important}",
                    ".token.comment,.token.prolog,.token.doctype,.token.cdata{color:#8292A2!important}",
                    ".token.punctuation{color:#C8D0D8!important}",
                    ".token.property,.token.tag,.token.boolean,.token.number,.token.constant,.token.symbol,.token.deleted{color:#F78FB3!important}",
                    ".token.selector,.token.attr-name,.token.string,.token.char,.token.builtin,.token.inserted{color:#A6E22E!important}",
                    ".token.operator,.token.entity,.token.url{color:#E6B455!important}",
                    ".token.atrule,.token.attr-value,.token.keyword{color:#66D9EF!important}",
                    ".token.function,.token.class-name{color:#E6DB74!important}",
                    ".token.regex,.token.important,.token.variable{color:#FD971F!important}"
                ].join("");
                function panelIsDark() { return document.documentElement.getAttribute("data-elurk-theme") === "dark"; }
                function injectFmStyle() {
                    try {
                        var d = fm.contentDocument;
                        if (!d || !d.head) { return; }
                        var css = FM_CSS + (panelIsDark() ? FM_DARK : "");
                        var st = d.getElementById("elurk-fm-embed");
                        if (st) { if (st.textContent !== css) { st.textContent = css; } return; }
                        st = d.createElement("style"); st.id = "elurk-fm-embed"; st.textContent = css;
                        d.head.appendChild(st);
                    } catch (e) {}
                }
                // Al pulsar el boton claro/oscuro del panel, el gestor cambia a la vez.
                document.addEventListener("elurk:theme", injectFmStyle);
                var firstLoad = true;
                // Listener PERSISTENTE: en cada carga del iframe comprobamos que sigue
                // siendo el gestor (/fm/). Si navega a otra pagina del panel (salir,
                // sesion caducada -> login...), la abrimos en la ventana principal en
                // vez de dejar el panel anidado dentro del iframe.
                fm.addEventListener("load", function () {
                    var p = "";
                    try { p = fm.contentWindow.location.pathname || ""; } catch (e) { return; }
                    if (!/^\/fm(\/|$)/.test(p)) {
                        try { window.location.href = fm.contentWindow.location.href; } catch (e) { window.location.href = "/"; }
                        return;
                    }
                    injectFmStyle();
                    if (firstLoad) {
                        firstLoad = false;
                        setTimeout(function () {
                            try { fm.contentWindow.location.hash = fm.dataset.hash; } catch (e) {}
                            injectFmStyle(); // por si la SPA re-renderizo el head
                        }, 400);
                    }
                });
                fm.setAttribute("src", fm.dataset.base);
            }
            try { history.replaceState(null, "", "#" + name); } catch (e) {}
        }
        tabs.forEach(function (t) { t.addEventListener("click", function (e) { e.preventDefault(); show(t.dataset.pane); }); });
        document.querySelectorAll("[data-goto]").forEach(function (el) { el.addEventListener("click", function (e) { e.preventDefault(); show(el.dataset.goto); }); });
        var h = (location.hash || "").replace("#", "");
        if (h && document.getElementById("pane-" + h)) { show(h); }
    })();
    </script>
    <script>
    /* ===================== Copias + WordPress Toolkit (vista por dominio) ===================== */
    (function () {
        var ctx = document.getElementById("dv-ctx"); if (!ctx) { return; }
        var TOKEN = document.getElementById("token").getAttribute("token");
        var DOMAIN = ctx.dataset.domain, OWNER = ctx.dataset.owner, IS_ADMIN = ctx.dataset.admin === "1", IS_WP = ctx.dataset.wp === "1";
        function esc(s) { return String(s == null ? "" : s).replace(/[&<>"']/g, function (c) { return { "&": "&amp;", "<": "&lt;", ">": "&gt;", '"': "&quot;", "'": "&#39;" }[c]; }); }
        function post(data) {
            data.token = TOKEN; data.domain = DOMAIN; data.user = OWNER;
            var body = Object.keys(data).map(function (k) { return encodeURIComponent(k) + "=" + encodeURIComponent(data[k]); }).join("&");
            return fetch("/list/domain/action.php", { method: "POST", headers: { "Content-Type": "application/x-www-form-urlencoded" }, body: body, credentials: "same-origin" })
                .then(function (r) { return r.text(); })
                .then(function (t) { try { return JSON.parse(t); } catch (e) { return { ok: false, error: "respuesta no válida del servidor" }; } })
                .catch(function () { return { ok: false, error: "error de red" }; });
        }
        function msg(el, kind, text) { if (!el) { return; } el.innerHTML = text ? '<div class="dv-msg ' + kind + '">' + esc(text) + "</div>" : ""; }
        function fmtMB(mb) { if (mb == null || mb === "") { return "—"; } mb = Number(mb); return mb >= 1024 ? (mb / 1024).toFixed(1) + " GB" : mb + " MB"; }
        function fmtBytes(b) { b = Number(b) || 0; if (b < 1024) { return b + " B"; } if (b < 1048576) { return (b / 1024).toFixed(1) + " KB"; } if (b < 1073741824) { return (b / 1048576).toFixed(1) + " MB"; } return (b / 1073741824).toFixed(2) + " GB"; }
        var onShow = {};   /* callbacks al abrir una pestana (lazy) */
        document.querySelectorAll(".dv-tab[data-pane], [data-goto]").forEach(function (t) {
            t.addEventListener("click", function () { var n = t.dataset.pane || t.dataset.goto; if (onShow[n]) { onShow[n](); } });
        });
        function initialTab() { var h = (location.hash || "").replace("#", ""); if (h && onShow[h]) { onShow[h](); } }

        /* ------------------------------------------------------------------ COPIAS ------ */
        var bkList = document.getElementById("bk-list"), bkMsg = document.getElementById("bk-msg"), bkProg = document.getElementById("bk-progress"), bkLog = document.getElementById("bk-log");
        var bkLoaded = false, bkBackups = [], pollTimer = null;
        function loadBackups() {
            bkList.innerHTML = '<div class="dv-empty"><span class="dv-spin"></span> Cargando copias…</div>';
            post({ action: "bk-list" }).then(function (r) {
                if (!r.ok) { bkList.innerHTML = '<div class="dv-empty">No se pudieron leer las copias: ' + esc(r.error || "") + "</div>"; return; }
                bkBackups = r.backups || [];
                document.getElementById("bk-create-restic").hidden = !r.restic;
                var ret = document.getElementById("bk-retention"), n = parseInt(r.retention, 10);
                if (!isNaN(n)) {
                    ret.innerHTML = "Retención: Hestia conserva las <b>" + n + "</b> última(s) copia(s) completa(s) de este usuario y borra las anteriores (paquete <b>" + esc(r.package || "") + "</b>)." +
                        (n <= 1 ? ' <span style="color:#B4460F">Con 1, cada copia nueva sustituye a la anterior.</span>' : "") +
                        (IS_ADMIN && r.package ? ' <a href="/edit/package/?package=' + encodeURIComponent(r.package) + '&token=' + encodeURIComponent(TOKEN) + '">Cambiar en el paquete</a>' : "");
                }
                if (!bkBackups.length) { bkList.innerHTML = '<div class="dv-empty">Este usuario no tiene copias todavía. Hestia las hace cada noche (según el paquete del usuario).</div>'; return; }
                var html = '<table class="dv-table"><thead><tr><th>Fecha</th><th>Tipo</th><th>Tamaño</th><th>Contenido</th><th>Este dominio</th><th></th></tr></thead><tbody>';
                bkBackups.forEach(function (b, i) {
                    var inWeb = b.web.indexOf(DOMAIN) >= 0, inMail = b.mail.indexOf(DOMAIN) >= 0, inDns = b.dns.indexOf(DOMAIN) >= 0;
                    var cont = b.kind === "incremental" ? "snapshot restic" : (b.web.length + " web · " + b.mail.length + " correo · " + b.db.length + " BBDD · " + b.dns.length + " DNS");
                    var here = b.kind === "incremental" ? '<span class="dv-badge ok">incremental</span>' : ((inWeb ? '<span class="dv-badge ok">web</span> ' : "") + (inMail ? '<span class="dv-badge ok">correo</span> ' : "") + (inDns ? '<span class="dv-badge ok">DNS</span>' : "") || '<span class="dv-badge warn">no incluido</span>');
                    html += "<tr><td><b>" + esc(b.date) + "</b> " + esc(b.time) + "</td><td>" + (b.kind === "incremental" ? "Incremental (restic)" : "Completa") + (b.local ? "" : ' <span class="dv-badge warn" title="Solo en el destino remoto">remota</span>') + "</td><td>" + fmtMB(b.size_mb) + "</td><td style=\"font-size:.85rem\">" + esc(cont) + "</td><td>" + here + "</td>" +
                        '<td class="act">' + (b.kind === "full" && b.local ? '<a href="/download/backup/?backup=' + encodeURIComponent(b.id) + '&token=' + encodeURIComponent(TOKEN) + '" title="Descargar el archivo completo"><i class="fas fa-download"></i> Descargar</a>' : "") +
                        '<button type="button" class="bk-restore-btn" data-i="' + i + '"' + (b.local ? "" : " disabled") + '><i class="fas fa-clock-rotate-left"></i> Restaurar</button>' +
                        '<button type="button" class="bk-delete-btn" data-i="' + i + '" style="color:#A32633"><i class="fas fa-trash"></i> Borrar</button></td></tr>';
                });
                bkList.innerHTML = html + "</tbody></table>";
                bkList.querySelectorAll(".bk-restore-btn").forEach(function (btn) { btn.addEventListener("click", function () { openRestore(bkBackups[Number(btn.dataset.i)]); }); });
                bkList.querySelectorAll(".bk-delete-btn").forEach(function (btn) { btn.addEventListener("click", function () {
                    var b = bkBackups[Number(btn.dataset.i)];
                    if (!window.confirm("Borrar la copia del " + b.date + " " + b.time + " (" + (b.kind === "incremental" ? "snapshot restic" : fmtMB(b.size_mb)) + "). No se puede deshacer. ¿Continuar?")) { return; }
                    btn.disabled = true; msg(bkMsg, "info", "Borrando…");
                    post({ action: "bk-delete", backup: b.id }).then(function (r) { msg(bkMsg, r.ok ? "ok" : "err", r.ok ? "Copia borrada." : (r.error || "error")); loadBackups(); });
                }); });
                if (IS_ADMIN) { var rs = document.getElementById("bk-ret-sel"); if (rs) { var n = parseInt(r.retention, 10); rs.value = (!isNaN(n) && n >= 999) ? "all" : (Array.prototype.some.call(rs.options, function (o) { return o.value === String(n); }) ? String(n) : "all"); } var rk = document.querySelector("#bk-sch-kind option[value=restic]"); if (rk) { rk.hidden = !r.restic; } }
            });
        }
        function pollStatus(force) {
            post({ action: "bk-status" }).then(function (r) {
                if (!r.ok) { return; }
                if (r.running || force) {
                    bkProg.hidden = false; bkLog.textContent = r.log || ""; bkLog.scrollTop = bkLog.scrollHeight;
                    var job = ((r.log || "").match(/^== INICIO == (.*)$/m) || [])[1] || "Tarea";
                    document.getElementById("bk-progress-title").textContent = r.running ? job + " — en curso (puedes salir de esta página, sigue en el servidor)" : (r.state === "ok" ? job + " — terminada." : job + " — terminó con errores. Revisa el registro.");
                    bkProg.querySelector(".dv-msg").className = "dv-msg " + (r.running ? "info" : (r.state === "ok" ? "ok" : "err"));
                    bkProg.querySelector(".dv-spin").style.display = r.running ? "" : "none";
                    if (r.running) { pollTimer = setTimeout(function () { pollStatus(true); }, 3000); } else { loadBackups(); }
                }
            });
        }
        onShow.backup = function () { if (!bkLoaded) { bkLoaded = true; loadBackups(); pollStatus(false); } };
        document.getElementById("bk-reload").addEventListener("click", function () { loadBackups(); pollStatus(false); });
        function createBackup(kind) {
            if (!window.confirm(kind === "restic" ? "Se crea un snapshot incremental (restic) del usuario " + OWNER + " ahora. ¿Continuar?" : "Se crea una copia completa del usuario " + OWNER + " ahora (todos sus dominios, correo y BBDD). Puede tardar según el tamaño. ¿Continuar?")) { return; }
            msg(bkMsg, "info", "Lanzando la copia…");
            post({ action: "bk-create", kind: kind }).then(function (r) {
                if (!r.ok) { msg(bkMsg, "err", r.error || "error"); return; }
                msg(bkMsg, "", ""); pollStatus(true);
            });
        }
        if (IS_ADMIN && document.getElementById("bk-admin")) {
            var hourSel = document.getElementById("bk-sch-hour"), mdaySel = document.getElementById("bk-sch-mday"), freqSel = document.getElementById("bk-sch-freq");
            for (var hh = 0; hh < 24; hh++) { hourSel.insertAdjacentHTML("beforeend", '<option value="' + hh + '">' + (hh < 10 ? "0" + hh : hh) + "</option>"); }
            for (var dd = 1; dd <= 28; dd++) { mdaySel.insertAdjacentHTML("beforeend", '<option value="' + dd + '">día ' + dd + "</option>"); }
            hourSel.value = "3";
            function schedUI() { var f = freqSel.value; document.getElementById("bk-sch-wday").hidden = f !== "weekly"; mdaySel.hidden = f !== "monthly"; ["bk-sch-hour", "bk-sch-min", "bk-sch-kind"].forEach(function (id) { document.getElementById(id).style.display = f === "none" ? "none" : ""; }); }
            freqSel.addEventListener("change", schedUI);
            function loadSchedule() {
                post({ action: "bk-schedule-get" }).then(function (r) {
                    var info = document.getElementById("bk-sch-info");
                    if (!r.ok) { info.textContent = r.error || ""; return; }
                    var sc = r.schedule;
                    if (sc) {
                        freqSel.value = sc.wday !== "*" ? "weekly" : (sc.day !== "*" ? "monthly" : "daily");
                        if (sc.wday !== "*") { document.getElementById("bk-sch-wday").value = sc.wday; }
                        if (sc.day !== "*") { mdaySel.value = sc.day; }
                        hourSel.value = String(parseInt(sc.hour, 10)); document.getElementById("bk-sch-min").value = String(parseInt(sc.min, 10)); document.getElementById("bk-sch-kind").value = sc.kind || "full";
                        info.innerHTML = "Programada como tarea cron del usuario " + esc(r.root_user || "admin") + " (nº " + esc(sc.job) + "). Además Hestia hace su copia global diaria de todos los usuarios (Cron del administrador, v-backup-users); desactívala ahí si solo quieres ésta.";
                    } else {
                        freqSel.value = "none";
                        info.innerHTML = "Sin programación propia: solo la copia global diaria de Hestia (v-backup-users en el Cron del administrador).";
                    }
                    schedUI();
                });
            }
            document.getElementById("bk-sch-save").addEventListener("click", function () {
                var f = freqSel.value, btn = this; btn.disabled = true;
                var req = f === "none" ? { action: "bk-schedule-del" } : { action: "bk-schedule-set", min: document.getElementById("bk-sch-min").value, hour: hourSel.value, day: f === "monthly" ? mdaySel.value : "*", month: "*", wday: f === "weekly" ? document.getElementById("bk-sch-wday").value : "*", kind: document.getElementById("bk-sch-kind").value };
                post(req).then(function (r) { btn.disabled = false; msg(bkMsg, r.ok ? "ok" : "err", r.ok ? "Programación guardada." : (r.error || "error")); loadSchedule(); });
            });
            document.getElementById("bk-ret-save").addEventListener("click", function () {
                var btn = this; btn.disabled = true;
                post({ action: "bk-retention", n: document.getElementById("bk-ret-sel").value }).then(function (r) { btn.disabled = false; msg(bkMsg, r.ok ? "ok" : "err", r.ok ? "Retención guardada." : (r.error || "error")); loadBackups(); });
            });
            var _onShowBackup = onShow.backup; onShow.backup = function () { var first = !bkLoaded; _onShowBackup(); if (first) { loadSchedule(); } };
        }
        document.getElementById("bk-create").addEventListener("click", function () { createBackup("full"); });
        document.getElementById("bk-create-restic").addEventListener("click", function () { createBackup("restic"); });

        /* ---- Modal de restauracion (doble lista tipo Plesk) ---- */
        var TYPES = [
            { id: "web", label: "Sitio web (archivos y configuración)", needs: "web" },
            { id: "files", label: "Archivos sueltos del sitio", needs: "web" },
            { id: "mailacc", label: "Cuentas de correo (buzones sueltos)", needs: "mail" },
            { id: "maildom", label: "Dominio de correo completo", needs: "mail" },
            { id: "db", label: "Bases de datos del usuario", needs: "db" },
            { id: "dns", label: "Zona DNS", needs: "dns" }
        ];
        function openRestore(b) {
            var bg = document.createElement("div"); bg.className = "dv-modal-bg";
            bg.innerHTML = '<div class="dv-modal" role="dialog">' +
                '<div class="mh"><h2>Restaurar la copia del ' + esc(b.date) + " " + esc(b.time) + '</h2><button type="button" class="x" title="Cerrar">&times;</button></div>' +
                '<div class="mb">' +
                '<div class="dv-form-row"><label>Detalles</label><div id="rs-details"><span class="dv-spin"></span> leyendo el contenido de la copia…</div></div>' +
                '<div class="dv-form-row"><label>¿Qué quieres restaurar?</label><div class="dv-radio"><label><input type="radio" name="rs-scope" value="sel" checked> Objetos seleccionados</label><label><input type="radio" name="rs-scope" value="all"> Todo el dominio <span class="dv-meta">(web + correo + DNS de ' + esc(DOMAIN) + ")</span></label></div></div>" +
                '<div class="dv-form-row" id="rs-type-row"><label>Tipo de objeto</label><select id="rs-type"></select></div>' +
                '<div class="dv-form-row" id="rs-dual-row"><label id="rs-dual-label">Objetos</label><div>' +
                    '<div class="dv-crumb" id="rs-crumb" hidden></div>' +
                    '<div class="dv-dual"><div class="col"><h4>Disponibles:</h4><div class="tools"><input type="checkbox" id="rs-all-av" title="Marcar todos"><input type="search" id="rs-search" placeholder="Buscar…"></div><div class="dv-list" id="rs-avail"></div></div>' +
                    '<div class="mid"><i class="fas fa-arrow-right-arrow-left"></i>Pulsa los objetos para moverlos de una lista a otra</div>' +
                    '<div class="col"><h4>Seleccionados:</h4><div class="tools"><input type="checkbox" id="rs-all-sel" title="Quitar todos"><span class="dv-meta" id="rs-sel-count">Ningún objeto seleccionado</span></div><div class="dv-list" id="rs-sel"><div class="empty">Ningún objeto seleccionado</div></div></div></div>' +
                "</div></div>" +
                '<div class="dv-form-row" id="rs-opt-row"><label>Opciones</label><div class="dv-radio" id="rs-opts"></div></div>' +
                '<div class="dv-form-row"><label>Aviso</label><div><label><input type="checkbox" id="rs-notify"> Enviar un correo al terminar (al contacto del usuario ' + esc(OWNER) + ")</label></div></div>" +
                '<div id="rs-msg"></div></div>' +
                '<div class="mf"><button type="button" class="dv-btn" id="rs-go" disabled>Restaurar</button><button type="button" class="dv-btn sec" id="rs-cancel">Cancelar</button></div></div>';
            document.body.appendChild(bg);
            var q = function (id) { return bg.querySelector("#" + id); };
            var close = function () { bg.remove(); };
            bg.querySelector(".x").addEventListener("click", close); q("rs-cancel").addEventListener("click", close);
            bg.addEventListener("click", function (e) { if (e.target === bg) { close(); } });

            var detail = null, avail = [], selected = [], filesPath = "", curType = "";
            var sel = q("rs-type");
            function renderLists() {
                var f = (q("rs-search").value || "").toLowerCase();
                var av = q("rs-avail"); av.innerHTML = "";
                var shown = avail.filter(function (it) { return selected.indexOf(it.val) < 0 && (!f || it.name.toLowerCase().indexOf(f) >= 0); });
                if (curType === "files") {
                    q("rs-crumb").hidden = false;
                    var parts = filesPath ? filesPath.split("/") : [], crumb = '<a href="#" data-p="">' + esc(DOMAIN) + "</a>";
                    parts.forEach(function (p, i) { crumb += " / " + '<a href="#" data-p="' + esc(parts.slice(0, i + 1).join("/")) + '">' + esc(p) + "</a>"; });
                    q("rs-crumb").innerHTML = crumb;
                    q("rs-crumb").querySelectorAll("a").forEach(function (a) { a.addEventListener("click", function (e) { e.preventDefault(); loadFiles(a.dataset.p); }); });
                } else { q("rs-crumb").hidden = true; }
                if (!shown.length) { av.innerHTML = '<div class="empty">' + (avail.length ? "Nada más que mostrar." : "No hay objetos de este tipo en la copia.") + "</div>"; }
                shown.forEach(function (it) {
                    var d = document.createElement("div"); d.className = "it";
                    d.innerHTML = '<input type="checkbox" class="dv-check"><span class="nm" title="' + esc(it.val) + '">' + (it.dir ? '<i class="fas fa-folder" style="color:#D9822B"></i> ' : "") + esc(it.name) + "</span>" + (it.size != null && !it.dir ? '<span class="sz">' + fmtBytes(it.size) + "</span>" : "") + (it.dir ? '<span class="op" data-open="1">Abrir ›</span>' : "");
                    d.addEventListener("click", function (e) { if (e.target.dataset.open) { e.stopPropagation(); loadFiles(it.val); return; } selected.push(it.val); renderLists(); });
                    av.appendChild(d);
                });
                var sl = q("rs-sel"); sl.innerHTML = "";
                if (!selected.length) { sl.innerHTML = '<div class="empty">Ningún objeto seleccionado</div>'; }
                selected.forEach(function (v) {
                    var d = document.createElement("div"); d.className = "it";
                    d.innerHTML = '<input type="checkbox" class="dv-check" checked><span class="nm" title="' + esc(v) + '">' + esc(v) + "</span>";
                    d.addEventListener("click", function () { selected = selected.filter(function (x) { return x !== v; }); renderLists(); });
                    sl.appendChild(d);
                });
                q("rs-sel-count").textContent = selected.length ? selected.length + " objeto(s)" : "Ningún objeto seleccionado";
                updateGo();
            }
            function updateGo() {
                var scope = bg.querySelector("input[name=rs-scope]:checked").value;
                q("rs-go").disabled = !(scope === "all" || selected.length > 0);
            }
            function setAvail(items) { avail = items; renderLists(); }
            function loadFiles(path) {
                filesPath = path || "";
                q("rs-avail").innerHTML = '<div class="empty"><span class="dv-spin"></span> leyendo…</div>';
                post({ action: "bk-files", backup: b.id, path: filesPath }).then(function (r) {
                    if (!r.ok) { q("rs-avail").innerHTML = '<div class="empty">' + esc(r.error) + "</div>"; return; }
                    if (r.building) { q("rs-avail").innerHTML = '<div class="empty"><span class="dv-spin"></span> Generando el índice de archivos de esta copia (se lee todo el sitio comprimido: puede tardar varios minutos). Se actualiza solo.</div>'; setTimeout(function () { if (document.body.contains(bg) && curType === "files") { loadFiles(filesPath); } }, 5000); return; }
                    setAvail((r.items || []).map(function (it) { return { val: it.path, name: it.name, dir: it.dir, size: it.size }; }));
                });
            }
            function renderOpts() {
                var o = q("rs-opts"), t = curType;
                if (t === "web" || t === "maildom" || t === "dns") {
                    o.innerHTML = '<label><input type="radio" name="rs-mode" value="full" checked> Configuración y contenido de los objetos seleccionados</label><label><input type="radio" name="rs-mode" value="config"> Solo la configuración (no toca archivos ni buzones)</label>';
                } else if (t === "mailacc") {
                    o.innerHTML = '<label><input type="radio" name="rs-mode" value="merge" checked> Fusionar con el buzón actual (no se borra nada de lo que hay ahora)</label><label><input type="radio" name="rs-mode" value="folder"> Dejar el correo recuperado en una carpeta <b>RESTAURADO-fecha</b> dentro del buzón</label>';
                } else if (t === "files") {
                    o.innerHTML = '<span class="dv-meta">Los archivos seleccionados se copian encima de los actuales (los que no estén en la copia no se tocan).</span>';
                } else {
                    o.innerHTML = '<span class="dv-meta">Las bases de datos se sustituyen por el volcado de la copia.</span>';
                }
            }
            function setType(t) {
                curType = t; selected = []; filesPath = ""; q("rs-search").value = "";
                renderOpts();
                if (t === "web") { setAvail(detail.has_web ? [{ val: DOMAIN, name: DOMAIN + " (sitio web)" }] : []); }
                else if (t === "dns") { setAvail(detail.has_dns ? [{ val: DOMAIN, name: DOMAIN + " (zona DNS)" }] : []); }
                else if (t === "maildom") { setAvail(detail.has_mail ? [{ val: DOMAIN, name: DOMAIN + " (todos los buzones)" }] : []); }
                else if (t === "db") { setAvail((detail.db || []).map(function (n) { return { val: n, name: n }; })); }
                else if (t === "mailacc") {
                    q("rs-avail").innerHTML = '<div class="empty"><span class="dv-spin"></span> leyendo buzones…</div>';
                    post({ action: "bk-mail-accounts", backup: b.id }).then(function (r) {
                        if (!r.ok) { setAvail([]); q("rs-avail").innerHTML = '<div class="empty">' + esc(r.error) + "</div>"; return; }
                        setAvail((r.accounts || []).map(function (a) { return { val: a.account, name: a.account + "@" + DOMAIN + (a.disk_mb ? "  (" + a.disk_mb + " MB)" : "") }; }));
                    });
                }
                else if (t === "files") { loadFiles(""); }
            }
            sel.addEventListener("change", function () { setType(sel.value); });
            q("rs-search").addEventListener("input", renderLists);
            q("rs-all-av").addEventListener("change", function () { if (this.checked) { var f = (q("rs-search").value || "").toLowerCase(); avail.forEach(function (it) { if (selected.indexOf(it.val) < 0 && (!f || it.name.toLowerCase().indexOf(f) >= 0)) { selected.push(it.val); } }); } this.checked = false; renderLists(); });
            q("rs-all-sel").addEventListener("change", function () { selected = []; this.checked = false; renderLists(); });
            bg.querySelectorAll("input[name=rs-scope]").forEach(function (r) { r.addEventListener("change", function () { var all = r.value === "all" && r.checked; q("rs-type-row").style.display = all ? "none" : ""; q("rs-dual-row").style.display = all ? "none" : ""; q("rs-opt-row").style.display = all ? "none" : ""; updateGo(); }); });

            post({ action: "bk-detail", backup: b.id }).then(function (r) {
                if (!r.ok) { q("rs-details").innerHTML = '<span style="color:#A32633">' + esc(r.error) + "</span>"; return; }
                detail = r;
                q("rs-details").innerHTML = "<b>" + (r.kind === "incremental" ? "Copia incremental (restic)" : "Copia completa") + "</b>" + (r.size_mb ? " · " + fmtMB(r.size_mb) : "") + " · " + esc(DOMAIN) + ": " + (r.has_web ? "sitio web ✓ " : "sin web ") + (r.has_mail ? "· correo ✓ " : "· sin correo ") + (r.has_dns ? "· DNS ✓" : "· sin DNS") + " · " + (r.db || []).length + " base(s) de datos del usuario";
                sel.innerHTML = "";
                TYPES.forEach(function (t) {
                    var ok = (t.needs === "web" && r.has_web) || (t.needs === "mail" && r.has_mail) || (t.needs === "dns" && r.has_dns) || (t.needs === "db" && (r.db || []).length);
                    var o = document.createElement("option"); o.value = t.id; o.textContent = t.label + (ok ? "" : " (no está en esta copia)"); o.disabled = !ok; sel.appendChild(o);
                });
                var first = sel.querySelector("option:not([disabled])"); if (first) { sel.value = first.value; setType(first.value); } else { setAvail([]); }
            });

            q("rs-go").addEventListener("click", function () {
                var scope = bg.querySelector("input[name=rs-scope]:checked").value;
                var modeEl = bg.querySelector("input[name=rs-mode]:checked"), mode = modeEl ? modeEl.value : "";
                var summary, req;
                if (scope === "all") {
                    summary = "TODO el dominio " + DOMAIN + " (web, correo y DNS) de la copia del " + b.date + ". Se sobrescribe lo actual.";
                    req = { action: "bk-restore", backup: b.id, web: detail.has_web ? DOMAIN : "", mail: detail.has_mail ? DOMAIN : "", dns: detail.has_dns ? DOMAIN : "" };
                } else if (curType === "web") {
                    if (mode === "config") { summary = "solo la CONFIGURACIÓN web de " + DOMAIN + " (no toca archivos)."; req = { action: "bk-restore-config", backup: b.id, what: "web" }; }
                    else { summary = "el sitio web " + DOMAIN + " completo (archivos + configuración). Se sobrescriben los archivos actuales."; req = { action: "bk-restore", backup: b.id, web: DOMAIN }; }
                } else if (curType === "dns") {
                    summary = "la zona DNS de " + DOMAIN + "."; req = mode === "config" ? { action: "bk-restore-config", backup: b.id, what: "dns" } : { action: "bk-restore", backup: b.id, dns: DOMAIN };
                } else if (curType === "maildom") {
                    if (mode === "config") { summary = "solo la CONFIGURACIÓN de correo de " + DOMAIN + " (cuentas, alias, contraseñas; no toca los buzones)."; req = { action: "bk-restore-config", backup: b.id, what: "mail" }; }
                    else { summary = "el dominio de correo " + DOMAIN + " completo, con TODOS sus buzones (se sobrescriben)."; req = { action: "bk-restore", backup: b.id, mail: DOMAIN }; }
                } else if (curType === "mailacc") {
                    summary = selected.length + " buzón(es): " + selected.join(", ") + (mode === "folder" ? " → en carpeta RESTAURADO-fecha." : " → fusionados con el buzón actual."); req = { action: "bk-restore-mail", backup: b.id, accounts: selected.join(","), how: mode };
                } else if (curType === "db") {
                    summary = selected.length + " base(s) de datos: " + selected.join(", ") + ". Se SUSTITUYEN por el volcado de la copia."; req = { action: "bk-restore", backup: b.id, db: selected.join(",") };
                } else if (curType === "files") {
                    summary = selected.length + " ruta(s): " + selected.join(", ") + " → se copian encima de las actuales."; req = { action: "bk-restore-files", backup: b.id, paths: selected.join(",") };
                } else { return; }
                if (!window.confirm("Vas a restaurar " + summary + "\n\n¿Continuar?")) { return; }
                req.notify = q("rs-notify").checked ? "yes" : "no";
                q("rs-go").disabled = true; msg(q("rs-msg"), "info", "Enviando…");
                post(req).then(function (r) {
                    if (!r.ok) { q("rs-go").disabled = false; msg(q("rs-msg"), "err", r.error || "error"); return; }
                    close();
                    if (r.started) { msg(bkMsg, "info", "Restauración iniciada en segundo plano: " + (r.job || "")); pollStatus(true); }
                    else { msg(bkMsg, "ok", r.summary || "Hecho."); loadBackups(); }
                });
            });
        }

        /* ------------------------------------------------------------------ WORDPRESS --- */
        if (IS_WP) {
            var wpBody = document.getElementById("wp-body"), wpLoading = document.getElementById("wp-loading"), wpMsg = document.getElementById("wp-msg"), wpLoaded = false, ST = null;
            function riskClass(s) { s = Number(s) || 0; return s <= 0 ? "r0" : s < 4 ? "r1" : s < 7 ? "r2" : "r3"; }
            function riskLabel(s) { s = Number(s) || 0; return s <= 0 ? "SIN RIESGO CONOCIDO" : s.toFixed(1) + " RIESGO"; }
            function loadWP(refresh) {
                wpLoading.hidden = false; wpBody.hidden = true; msg(wpMsg, "", "");
                post({ action: "wp-status", refresh: refresh ? "1" : "0" }).then(function (r) {
                    wpLoading.hidden = true;
                    if (!r.ok) { msg(wpMsg, "err", r.error || "error"); return; }
                    ST = r; renderWP();
                });
            }
            function renderWP() {
                var r = ST; wpBody.hidden = false;
                document.getElementById("wp-checked").textContent = "Comprobado: " + (r.checked_at || "") + (r.cached ? " (caché)" : "") + " · vulnerabilidades: " + (r.vuln_source || "");
                var core = (r.components || []).filter(function (c) { return c.type === "core"; })[0] || {};
                var upd = (r.updates || []).length, pend = r.measures_pending || 0;
                var cards = '<div class="dv-card"><div class="h">Riesgo de seguridad <i class="fas fa-circle-question" title="Puntuación CVSS más alta entre las vulnerabilidades conocidas que afectan a las versiones instaladas (0 a 10)."></i></div><div class="big">' + (Number(r.risk) || 0).toFixed(1) + "<small> /10</small></div>" + '<div class="s">' + (r.risk > 0 ? "Actualiza o desactiva los componentes marcados abajo." : "Ninguna vulnerabilidad conocida en las versiones instaladas.") + "</div></div>";
                cards += '<div class="dv-card"><div class="h">' + (upd ? "Hay actualizaciones" : "Todo actualizado") + ' <i class="fas fa-circle-up"></i></div><div class="big">' + upd + "<small> pendientes</small></div>" + '<div class="f">' + (upd ? '<button type="button" class="dv-btn sm" id="wp-update-all">Instalar ' + upd + " actualizacion" + (upd === 1 ? "" : "es") + "</button>" : "") + "</div></div>";
                cards += '<div class="dv-card"><div class="h">Medidas de seguridad <i class="fas fa-list-check"></i></div><div class="big">' + pend + "<small> por aplicar</small></div>" + '<div class="f">' + (pend ? '<button type="button" class="dv-btn sm" id="wp-apply-all">Aplicar todas</button>' : "") + '<button type="button" class="dv-btn sec sm" id="wp-recheck" title="Comprobar de nuevo"><i class="fas fa-rotate"></i></button></div></div>';
                cards += '<div class="dv-card"><div class="h">Versiones <i class="fab fa-wordpress"></i></div><div class="big" style="font-size:1.3rem">WordPress ' + esc(core.version || "?") + "</div>" + '<div class="s">PHP ' + esc(r.php || "?") + (core.update_version ? ' · <b style="color:#B4460F">WordPress ' + esc(core.update_version) + " disponible</b>" : "") + (r.wpcli ? "" : ' · <b style="color:#A32633">WP-CLI no instalado</b>') + "</div></div>";
                document.getElementById("wp-cards").innerHTML = cards;
                var ua = document.getElementById("wp-update-all"); if (ua) { ua.addEventListener("click", function () { updateAll(); }); }
                var aa = document.getElementById("wp-apply-all"); if (aa) { aa.addEventListener("click", function () { applyMeasures("all"); }); }
                document.getElementById("wp-recheck").addEventListener("click", function () { loadWP(true); });
                renderComponents(); renderMeasures(); renderTools();
                if (!r.wpcli) { msg(wpMsg, "info", "Sin WP-CLI solo se ven el núcleo y las medidas de archivos. Instala WP-CLI para el usuario " + OWNER + " para ver plugins, temas y actualizaciones." + (IS_ADMIN ? "" : " Pídeselo al administrador.")); if (IS_ADMIN) { wpMsg.firstChild.innerHTML += ' <button type="button" class="dv-btn sm" id="wp-cli-install">Instalar WP-CLI</button>'; document.getElementById("wp-cli-install").addEventListener("click", function () { this.disabled = true; post({ action: "wp-wpcli-install" }).then(function (x) { msg(wpMsg, x.ok ? "ok" : "err", x.ok ? "WP-CLI instalado. Volviendo a comprobar…" : (x.error || "error")); if (x.ok) { loadWP(true); } }); }); } }
            }
            function renderComponents() {
                var comps = ST.components || [], el = document.getElementById("wp-sub-components");
                if (!comps.length) { el.innerHTML = '<div class="dv-empty">Sin datos de componentes.</div>'; return; }
                var html = '<table class="dv-table"><thead><tr><th style="width:130px">Riesgo</th><th>Componente</th><th>Estado</th><th></th></tr></thead><tbody>';
                comps.forEach(function (c) {
                    var vul = (c.vulns || []).slice(0, 4).map(function (v) { return "<li>" + (v.link ? '<a href="' + esc(v.link) + '" target="_blank" rel="noopener">' + esc(v.name) + "</a>" : esc(v.name)) + " · CVSS " + esc(v.score) + (v.fixed_in ? " · corregido en " + esc(v.fixed_in) : "") + "</li>"; }).join("");
                    if ((c.vulns || []).length > 4) { vul += "<li>… y " + ((c.vulns || []).length - 4) + " más</li>"; }
                    html += '<tr><td><span class="dv-risk ' + riskClass(c.risk) + '">' + riskLabel(c.risk) + "</span>" + (c.vuln_checked ? "" : '<div class="dv-meta" style="font-size:.7rem">sin datos de vulnerabilidades</div>') + "</td>" +
                        "<td><b>" + esc(c.title) + "</b> " + esc(c.version) + ' <span class="dv-meta">' + (c.type === "core" ? "núcleo" : c.type === "plugin" ? "plugin" : "tema") + "</span>" + (vul ? '<ul class="dv-vulns">' + vul + "</ul>" : "") + "</td>" +
                        "<td>" + (c.type === "core" ? "" : (c.status === "active" ? '<span class="dv-badge ok">activo</span>' : '<span class="dv-badge warn">' + esc(c.status) + "</span>")) + "</td>" +
                        '<td class="act">' + (c.update_version ? '<button type="button" data-upd="' + esc(c.type) + "|" + esc(c.slug) + '">Actualizar a ' + esc(c.update_version) + "</button>" : "") + (c.type === "plugin" && c.status === "active" ? '<button type="button" data-deact="' + esc(c.slug) + '">Desactivar</button>' : "") + "</td></tr>";
                });
                el.innerHTML = html + "</tbody></table>";
                el.querySelectorAll("[data-upd]").forEach(function (b) { b.addEventListener("click", function () { var p = b.dataset.upd.split("|"); runWP(b, { action: "wp-update", kind: p[0], slug: p[1] }, "Actualizando " + p[1] + "…"); }); });
                el.querySelectorAll("[data-deact]").forEach(function (b) { b.addEventListener("click", function () { if (window.confirm("¿Desactivar el plugin " + b.dataset.deact + "?")) { runWP(b, { action: "wp-deactivate", slug: b.dataset.deact }, "Desactivando…"); } }); });
            }
            function renderMeasures() {
                var ms = ST.measures || [], el = document.getElementById("wp-sub-measures");
                var html = '<p class="dv-meta" style="margin-top:0">Las medidas críticas se aplican solas al instalar WordPress con el panel; el resto se aplican aquí. Si alguna rompe algo del sitio, revierte la que sea reversible. Haz una copia antes de asegurar.</p>' +
                    '<div style="display:flex;gap:.5rem;align-items:center;margin-bottom:.6rem;flex-wrap:wrap"><button type="button" class="dv-btn sm" id="wp-secure">Asegurar seleccionadas</button><button type="button" class="dv-btn sec sm" id="wp-check"><i class="fas fa-rotate"></i> Comprobar</button><button type="button" class="dv-btn sec sm" id="wp-revert">Revertir seleccionadas</button><span class="dv-meta">Estado comprobado: ' + esc(ST.checked_at || "") + "</span></div>" +
                    '<table class="dv-table"><thead><tr><th style="width:36px"><input type="checkbox" id="wp-m-all"></th><th>Medida de seguridad</th><th style="width:90px;text-align:center">Estado</th></tr></thead><tbody>';
                ms.forEach(function (m) {
                    var ico = m.status === "ok" ? "fa-circle-check" : m.status === "crit" ? "fa-circle-exclamation" : m.status === "warn" ? "fa-triangle-exclamation" : "fa-circle-question";
                    html += '<tr><td><input type="checkbox" class="wp-m" value="' + esc(m.id) + '"' + (m.status === "crit" ? " checked" : "") + "></td><td>" + esc(m.name) + ' <i class="fas fa-circle-info" style="color:#B0BAC4" title="' + esc(m.description) + '"></i> ' + (m.reversible ? '<span class="dv-meta">(reversible)</span>' : "") + (m.manual ? ' <span class="dv-meta">(manual)</span>' : "") + (m.info ? ' <span class="dv-meta">· ' + esc(m.info) + "</span>" : "") + '</td><td style="text-align:center"><i class="fas ' + ico + ' dv-status ' + esc(m.status) + '" title="' + esc(m.status) + '"></i></td></tr>';
                });
                el.innerHTML = html + "</tbody></table>";
                document.getElementById("wp-m-all").addEventListener("change", function () { var on = this.checked; el.querySelectorAll(".wp-m").forEach(function (c) { c.checked = on; }); });
                var selIds = function () { return Array.prototype.map.call(el.querySelectorAll(".wp-m:checked"), function (c) { return c.value; }); };
                document.getElementById("wp-secure").addEventListener("click", function () { var ids = selIds(); if (!ids.length) { msg(wpMsg, "info", "Marca al menos una medida."); return; } applyMeasures(ids.join(",")); });
                document.getElementById("wp-revert").addEventListener("click", function () { var ids = selIds(); if (!ids.length) { msg(wpMsg, "info", "Marca al menos una medida."); return; } if (!window.confirm("¿Revertir " + ids.length + " medida(s)?")) { return; } runWP(this, { action: "wp-revert", measures: ids.join(",") }, "Revirtiendo…"); });
                document.getElementById("wp-check").addEventListener("click", function () { loadWP(true); });
            }
            function applyMeasures(ids) {
                if (!window.confirm(ids === "all" ? "Se aplican todas las medidas automáticas (las no reversibles incluidas: claves de seguridad). ¿Continuar?" : "¿Aplicar las medidas seleccionadas?")) { return; }
                runWP(null, { action: "wp-apply", measures: ids }, "Aplicando medidas…");
            }
            function updateAll() {
                if (!window.confirm("Se actualizan núcleo, plugins y temas con actualización pendiente. Conviene tener una copia reciente (pestaña Copias). ¿Continuar?")) { return; }
                msg(wpMsg, "info", "Actualizando… puede tardar varios minutos.");
                var steps = [];
                if ((ST.updates || []).some(function (u) { return u.type === "plugin"; })) { steps.push({ action: "wp-update", kind: "plugin", slug: "all" }); }
                if ((ST.updates || []).some(function (u) { return u.type === "theme"; })) { steps.push({ action: "wp-update", kind: "theme", slug: "all" }); }
                if ((ST.updates || []).some(function (u) { return u.type === "core"; })) { steps.push({ action: "wp-update", kind: "core", slug: "wordpress" }); }
                var log = [];
                (function next() {
                    if (!steps.length) { msg(wpMsg, "ok", "Actualizaciones terminadas. " + log.join(" · ").slice(0, 600)); loadWP(true); return; }
                    post(steps.shift()).then(function (r) { log.push(r.ok ? (r.summary || "ok").split("\n").slice(-1)[0] : "ERROR: " + (r.error || "")); next(); });
                })();
            }
            function runWP(btn, req, working) {
                if (btn) { btn.disabled = true; }
                msg(wpMsg, "info", working);
                post(req).then(function (r) {
                    if (btn) { btn.disabled = false; }
                    if (r.ok) { msg(wpMsg, "ok", (r.summary || ((r.done || []).length ? "Hecho: " + r.done.join(", ") : "Hecho.")) + ((r.errors || []).length ? " · Avisos: " + r.errors.join("; ") : "")); }
                    else { msg(wpMsg, "err", r.error || ((r.errors || []).join("; ")) || "error"); }
                    loadWP(true);
                });
            }
            function renderTools() {
                var t = ST.tools || {}, el = document.getElementById("wp-sub-tools");
                function tog(id, on, label, hint, disabled) { return '<div class="dv-toggle"><span class="sw ' + (on ? "on" : "") + (disabled ? " off-dis" : "") + '" data-tool="' + id + '" data-on="' + (on ? 1 : 0) + '"' + (disabled ? ' data-dis="1"' : "") + "></span><span>" + label + '</span><span class="hint">' + hint + "</span></div>"; }
                var html = '<div class="dv-cards"><div class="dv-card"><div class="h">Herramientas</div>' +
                    '<div class="dv-toggle"><b>PHP</b> <span>' + esc(t.php || "?") + '</span> <a href="<?= $editWebInfo ?>" class="hint">Cambiar versión</a></div>' +
                    tog("debug", t.debug, "Depuración", "WP_DEBUG + registro en wp-content/debug.log, sin mostrar errores en pantalla") +
                    tog("httpauth", t.httpauth && t.httpauth.on, "Protección con contraseña", t.httpauth && t.httpauth.on ? "usuario(s): " + esc((t.httpauth.users || []).join(", ")) : "pide usuario y contraseña para ver el sitio (Hestia httpauth)") +
                    tog("maintenance", t.maintenance, "Modo mantenimiento", "los visitantes ven una página 503; tú sigues entrando en wp-admin") +
                    "</div>" +
                    '<div class="dv-card"><div class="h">Rendimiento</div>' +
                    tog("indexing", t.indexing === true, "Indexación por buscadores", t.indexing == null ? "necesita WP-CLI" : "ajuste 'Visibilidad en buscadores' de WordPress", t.indexing == null) +
                    tog("cache", t.cache && t.cache.on, "Caché (nginx FastCGI)", t.cache && t.cache.available ? (t.cache.on ? "activa · " + esc(t.cache.duration || "") : "acelera las páginas para visitantes no identificados") : esc((t.cache && t.cache.reason) || "no disponible"), !(t.cache && t.cache.available)) +
                    tog("wpcron", t.wpcron && t.wpcron.on, "Tomar el control de wp-cron.php", "cron real del sistema cada 5 min y DISABLE_WP_CRON; más fiable y más rápido") +
                    "</div></div>";
                el.innerHTML = html;
                el.querySelectorAll(".sw").forEach(function (sw) {
                    sw.addEventListener("click", function () {
                        if (sw.dataset.dis) { return; }
                        var tool = sw.dataset.tool, on = sw.dataset.on === "1", req = { action: "wp-toggle", tool: tool, state: on ? "off" : "on" };
                        if (tool === "httpauth" && !on) {
                            var u = window.prompt("Usuario para el acceso protegido:"); if (!u) { return; }
                            var p = window.prompt("Contraseña (mínimo 6 caracteres):"); if (!p || p.length < 6) { msg(wpMsg, "err", "Contraseña demasiado corta."); return; }
                            req.authuser = u; req.authpass = p;
                        }
                        if (tool === "maintenance" && !on && !window.confirm("El sitio mostrará una página de mantenimiento a los visitantes. ¿Activar?")) { return; }
                        msg(wpMsg, "info", "Aplicando…");
                        post(req).then(function (r) { if (r.ok) { msg(wpMsg, "ok", "Hecho."); if (r.tools) { ST.tools = r.tools; renderTools(); } else { loadWP(true); } } else { msg(wpMsg, "err", r.error || "error"); } });
                    });
                });
            }
            document.querySelectorAll(".dv-subtab").forEach(function (t) {
                t.addEventListener("click", function () {
                    document.querySelectorAll(".dv-subtab").forEach(function (x) { x.classList.toggle("active", x === t); });
                    ["components", "measures", "tools"].forEach(function (n) { document.getElementById("wp-sub-" + n).hidden = (n !== t.dataset.sub); });
                });
            });
            document.getElementById("wp-refresh").addEventListener("click", function () { loadWP(true); });
            onShow.wp = function () { if (!wpLoaded) { wpLoaded = true; loadWP(false); } };
        }
        initialTab();
    })();
    </script>

<?php endif; ?>
</div>
