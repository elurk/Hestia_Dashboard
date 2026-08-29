<div id="token" token="<?= $_SESSION["token"] ?>"></div>

<div class="dashboard-container" style="padding:1.5rem;">
    <h1 style="margin-bottom:.25rem;"><?= _("Security") ?> · Fail2ban</h1>
    <p style="opacity:.75;margin-top:0;">
        IPs bloqueadas por fail2ban. Marca las que quieras y aplica una accion.
    </p>

    <?php
    $totalBanned = 0;
    if (!empty($fail2ban["ok"]) && !empty($fail2ban["jails"])) {
        foreach ($fail2ban["jails"] as $j) { $totalBanned += count($j["banned"] ?? []); }
    }
    ?>

    <!-- Barra de acciones (opera sobre las IPs marcadas) -->
    <div class="card" style="padding:.75rem 1rem;margin:1rem 0;display:flex;gap:.5rem;flex-wrap:wrap;align-items:center;">
        <label style="display:flex;align-items:center;gap:.35rem;">
            <input type="checkbox" id="check-all"> <span style="opacity:.8;">Marcar todo</span>
        </label>
        <span style="flex:1;"></span>
        <button class="button" id="act-unban">Desbanear</button>
        <button class="button" id="act-whitelist">Anadir a lista blanca</button>
        <button class="button" id="act-blacklist">Anadir a lista negra</button>
        <span id="bulk-msg" style="opacity:.85;margin-left:.5rem;"></span>
    </div>

    <?php if (empty($fail2ban["ok"])): ?>
        <div class="card" style="padding:1rem;">
            No se pudo leer el estado de fail2ban. Comprueba que este instalado y que
            el sudoers permite <code>v-fail2ban-action</code>.
        </div>
    <?php elseif ($totalBanned === 0): ?>
        <div class="card" style="padding:1rem;">
            <i class="fas fa-check-circle"></i> Ninguna IP baneada ahora mismo.
        </div>
    <?php else: ?>
        <?php foreach ($fail2ban["jails"] as $jail): ?>
            <?php $banned = $jail["banned"] ?? []; if (empty($banned)) continue; ?>
            <div class="card" style="padding:1rem;margin-bottom:1rem;">
                <h3 style="margin-top:0;text-transform:capitalize;">
                    <?= htmlspecialchars($jail["jail"]) ?>
                    <span style="opacity:.6;font-weight:normal;">(<?= count($banned) ?>)</span>
                </h3>
                <table style="width:100%;border-collapse:collapse;">
                    <?php foreach ($banned as $ip): ?>
                        <tr class="ban-row" style="border-top:1px solid rgba(128,128,128,.2);">
                            <td style="padding:.5rem;width:2rem;">
                                <input type="checkbox" class="ban-check"
                                       data-jail="<?= htmlspecialchars($jail["jail"]) ?>"
                                       data-ip="<?= htmlspecialchars($ip) ?>">
                            </td>
                            <td style="padding:.5rem;font-family:monospace;"><?= htmlspecialchars($ip) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<script>
(function () {
    var token = document.getElementById("token").getAttribute("token");
    var msg = document.getElementById("bulk-msg");

    function post(data) {
        data.token = token;
        var body = Object.keys(data).map(function (k) {
            return encodeURIComponent(k) + "=" + encodeURIComponent(data[k]);
        }).join("&");
        return fetch("/list/security/action.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: body
        }).then(function (r) { return r.json(); }).catch(function () {
            return { ok: false, error: "error de red" };
        });
    }

    function selected() {
        return Array.prototype.slice.call(document.querySelectorAll(".ban-check:checked"));
    }

    // Marcar todo
    var checkAll = document.getElementById("check-all");
    if (checkAll) {
        checkAll.addEventListener("change", function () {
            document.querySelectorAll(".ban-check").forEach(function (c) { c.checked = checkAll.checked; });
        });
    }

    // Ejecuta una accion sobre cada IP marcada (en serie), y refresca al final.
    function runBulk(action) {
        var items = selected();
        if (items.length === 0) { msg.textContent = "No hay IPs marcadas."; return; }
        msg.textContent = "Procesando " + items.length + "...";
        var i = 0, okc = 0, errc = 0;
        function next() {
            if (i >= items.length) {
                msg.textContent = "Hecho: " + okc + " ok, " + errc + " error(es).";
                setTimeout(function () { location.reload(); }, 800);
                return;
            }
            var c = items[i++];
            var data = { action: action, ip: c.dataset.ip };
            if (action === "unban") { data.jail = c.dataset.jail; }
            post(data).then(function (res) {
                if (res.ok) { okc++; } else { errc++; }
                next();
            });
        }
        next();
    }

    document.getElementById("act-unban").addEventListener("click", function () { runBulk("unban"); });
    document.getElementById("act-whitelist").addEventListener("click", function () { runBulk("whitelist"); });
    document.getElementById("act-blacklist").addEventListener("click", function () {
        if (confirm("Anadir a la lista negra (DROP en firewall) las IPs marcadas?")) { runBulk("blacklist"); }
    });
})();
</script>
