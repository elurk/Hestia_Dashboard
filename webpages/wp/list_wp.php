<div id="token" token="<?= $_SESSION["token"] ?>"></div>

<div class="dashboard-container" style="padding:1.5rem;">
    <h1 style="margin-bottom:.25rem;">WordPress</h1>
    <p style="opacity:.75;margin-top:0;">
        WordPress detectados en los sitios alojados. Endurece permisos y configuracion
        con un clic (equivalente al WP Toolkit de Plesk).
    </p>

    <?php $sites = $wp["sites"] ?? []; ?>

    <?php if (empty($wp["ok"])): ?>
        <div class="card" style="padding:1rem;">
            No se pudo listar. Comprueba que el sudoers permite <code>v-wp-manage</code>.
        </div>
    <?php elseif (empty($sites)): ?>
        <div class="card" style="padding:1rem;">
            No se ha detectado ningun WordPress en los sitios alojados.
        </div>
    <?php else: ?>
        <div class="card" style="padding:1rem;">
            <table style="width:100%;border-collapse:collapse;">
                <thead>
                    <tr style="text-align:left;opacity:.7;">
                        <th style="padding:.5rem;">Dominio</th>
                        <th style="padding:.5rem;">Usuario</th>
                        <th style="padding:.5rem;">Estado</th>
                        <th style="padding:.5rem;text-align:right;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($sites as $s): ?>
                        <tr style="border-top:1px solid rgba(128,128,128,.2);">
                            <td style="padding:.5rem;font-weight:bold;"><?= htmlspecialchars($s["domain"]) ?></td>
                            <td style="padding:.5rem;"><?= htmlspecialchars($s["user"]) ?></td>
                            <td style="padding:.5rem;">
                                <?php if (!empty($s["hardened"])): ?>
                                    <span style="color:#3fb950;"><i class="fas fa-shield-alt"></i> Endurecido</span>
                                <?php else: ?>
                                    <span style="color:#d29922;"><i class="fas fa-exclamation-triangle"></i> Sin endurecer</span>
                                <?php endif; ?>
                            </td>
                            <td style="padding:.5rem;text-align:right;white-space:nowrap;">
                                <button class="button wp-btn" data-act="harden"
                                        data-user="<?= htmlspecialchars($s["user"]) ?>"
                                        data-domain="<?= htmlspecialchars($s["domain"]) ?>">Endurecer</button>
                                <button class="button wp-btn" data-act="scan"
                                        data-user="<?= htmlspecialchars($s["user"]) ?>"
                                        data-domain="<?= htmlspecialchars($s["domain"]) ?>">Escanear</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div id="wp-output" class="card" style="padding:1rem;margin-top:1rem;display:none;font-family:monospace;white-space:pre-wrap;font-size:.85rem;"></div>
    <?php endif; ?>
</div>

<script>
(function () {
    var token = document.getElementById("token").getAttribute("token");
    var output = document.getElementById("wp-output");

    function post(data, cb) {
        data.token = token;
        var body = Object.keys(data).map(function (k) {
            return encodeURIComponent(k) + "=" + encodeURIComponent(data[k]);
        }).join("&");
        fetch("/list/wp/action.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: body
        }).then(function (r) { return r.json(); }).then(cb).catch(function () {
            cb({ ok: false, error: "error de red" });
        });
    }

    document.querySelectorAll(".wp-btn").forEach(function (btn) {
        btn.addEventListener("click", function () {
            var act = btn.dataset.act;
            btn.disabled = true;
            var label = btn.textContent;
            btn.textContent = "...";
            if (output) { output.style.display = "block"; output.textContent = "Ejecutando " + act + " en " + btn.dataset.domain + "..."; }
            post({ action: act, user: btn.dataset.user, domain: btn.dataset.domain }, function (res) {
                btn.disabled = false;
                btn.textContent = label;
                if (output) {
                    output.textContent = (res.ok ? "[OK] " : "[ERROR] ") + (res.summary || res.error || "");
                }
            });
        });
    });
})();
</script>
