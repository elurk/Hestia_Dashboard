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
    // Gestor de archivos posicionado en el docroot (deep-link por hash; se
    // ajusta segun el formato de URL real del FM de Hestia).
    $fmUrl = "/fm/#" . $docroot;
    $editWebInfo = "/edit/web/?domain=$u" . $back("info") . "&token=" . $h($tok);
    $editWebHost = "/edit/web/?domain=$u" . $back("hosting") . "&token=" . $h($tok);
    $isSSL = (($web["SSL"] ?? "no") === "yes");
    // phpMyAdmin SSO (mismo esquema que Hestia en list_db): se firma con el
    // PROPIETARIO de la BD, igual que hace Hestia al impersonar.
    $pmaBase = "https://" . ($_SERVER["HTTP_HOST"] ?? "") . "/" . ($_SESSION["DB_PMA_ALIAS"] ?? "phpmyadmin") . "/";
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
                <a class="dv-tile" href="/list/backup/?<?= ltrim($back("info"), "&") ?>"><span class="ico" style="background:#D9822B"><i class="fas fa-file-zipper"></i></span><span><div class="t">Backup y restauración</div><div class="s">Copias de seguridad</div></span></a>
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
                <table class="dv-table">
                    <thead><tr><th>Dirección</th><th>Cuota</th><th>Usado</th><th>Reenvío</th><th>Estado</th><th></th></tr></thead>
                    <tbody>
                    <?php foreach ($mail as $acc => $m): ?>
                        <?php $editAcc = "/edit/mail/?" . http_build_query(["domain" => $domain, "account" => $acc, "from" => $domain, "tab" => "mail", "token" => $tok]); ?>
                        <tr>
                            <td><a class="row-link" href="<?= $h($editAcc) ?>" title="Editar cuenta"><?= $h($acc) ?>@<?= $h($domain) ?></a></td>
                            <td><?= ($m["QUOTA"] ?? "unlimited") === "unlimited" ? "∞" : $h($m["QUOTA"]) . " MB" ?></td>
                            <td><?= $h($m["U_DISK"] ?? 0) ?> MB</td>
                            <td><?= $h($m["FWD"] ?? "—") ?></td>
                            <td><?= (($m["SUSPENDED"] ?? "no") === "yes") ? '<span class="dv-badge warn">Suspendido</span>' : '<span class="dv-badge ok">Activo</span>' ?></td>
                            <td class="dv-actions" style="text-align:right;white-space:nowrap"><a href="<?= $h($editAcc) ?>" title="Editar"><i class="fas fa-pen"></i></a><a href="/webmail/" target="_blank" title="Webmail"><i class="fas fa-envelope-open"></i></a></td>
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
            <iframe class="dv-fm" id="dv-fm" data-src="<?= $h($fmUrl) ?>" title="Gestor de archivos"></iframe>
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

    <!-- ---- WordPress ---- -->
    <div class="dv-pane" id="pane-wp" data-title="WordPress">
        <div class="dv-group">
            <h3>WordPress en <?= $h($domain) ?></h3>
            <?php if ($isWP): ?>
                <div class="dv-empty" style="color:#212529"><i class="fab fa-wordpress" style="color:#21759B"></i> WordPress <b>detectado</b> en este dominio.
                    <?php if ($isAdmin): ?><a class="dv-btn" style="margin-left:.6rem" href="/list/wp/?<?= ltrim($back("wp"), "&") ?>">Endurecer / Escanear</a><?php endif; ?>
                </div>
            <?php else: ?>
                <div class="dv-empty">No se ha detectado WordPress en este dominio. Puedes instalarlo desde <a href="<?= $editWebInfo ?>">Editar dominio → Quick Install App</a>.</div>
            <?php endif; ?>
        </div>
    </div>

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
            if (name === "files" && fm && !fm.getAttribute("src")) { fm.setAttribute("src", fm.dataset.src); }
            try { history.replaceState(null, "", "#" + name); } catch (e) {}
        }
        tabs.forEach(function (t) { t.addEventListener("click", function (e) { e.preventDefault(); show(t.dataset.pane); }); });
        document.querySelectorAll("[data-goto]").forEach(function (el) { el.addEventListener("click", function (e) { e.preventDefault(); show(el.dataset.goto); }); });
        var h = (location.hash || "").replace("#", "");
        if (h && document.getElementById("pane-" + h)) { show(h); }
    })();
    </script>
<?php endif; ?>
</div>
