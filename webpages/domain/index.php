<?php
// Vista por dominio (estilo Plesk) - controlador.
// Se despliega en /usr/local/hestia/web/list/domain/index.php
//   /list/domain/              -> lista de dominios visibles para el usuario
//   /list/domain/?domain=X     -> vista de ESE dominio con pestanas
use function Hestiacp\quoteshellarg\quoteshellarg;
$TAB = "DOMAIN";

include $_SERVER["DOCUMENT_ROOT"] . "/inc/main.php";

$user = $_SESSION["user"] ?? null;
if (!$user) {
    header("Location: /login/");
    exit();
}
$isAdmin = (($_SESSION["userContext"] ?? "") === "admin");

// Ejecuta un comando Hestia y devuelve su JSON como array (o [] si falla).
function hst_json(string $cmd): array {
    exec($cmd, $out, $rc);
    $data = json_decode(implode("", $out), true);
    return ($rc === 0 && is_array($data)) ? $data : [];
}

$domain = trim($_GET["domain"] ?? "");
if ($domain !== "" && !preg_match('/^[a-z0-9.-]{1,253}$/i', $domain)) {
    $domain = "";
}

if ($domain === "") {
    // ---------------- MODO LISTA ----------------
    $mode = "list";
    $domains = [];
    $users = $isAdmin ? array_keys(hst_json(HESTIA_CMD . "v-list-users json")) : [$user];
    foreach ($users as $u) {
        $webs = hst_json(HESTIA_CMD . "v-list-web-domains " . quoteshellarg($u) . " json");
        foreach ($webs as $d => $info) {
            $domains[] = [
                "domain"    => $d,
                "user"      => $u,
                "ip"        => $info["IP"] ?? "",
                "ssl"       => ($info["SSL"] ?? "no") === "yes",
                "disk"      => (int) ($info["U_DISK"] ?? 0),
                "bw"        => (int) ($info["U_BANDWIDTH"] ?? 0),
                "suspended" => ($info["SUSPENDED"] ?? "no") === "yes",
            ];
        }
    }
    usort($domains, fn($a, $b) => strcmp($a["domain"], $b["domain"]));
} else {
    // ---------------- MODO DETALLE ----------------
    $mode = "detail";

    // Propietario real del dominio (lo dice Hestia, no la URL)
    exec(HESTIA_CMD . "v-search-domain-owner " . quoteshellarg($domain), $o, $rc);
    $owner = trim(implode("", $o));
    unset($o);
    if ($rc !== 0 || $owner === "") {
        header("Location: /list/domain/");
        exit();
    }
    // SEGURIDAD: un usuario normal solo puede ver dominios que le pertenecen.
    if (!$isAdmin && $owner !== $user) {
        header("Location: /list/domain/");
        exit();
    }

    $webAll = hst_json(HESTIA_CMD . "v-list-web-domain " . quoteshellarg($owner) . " " . quoteshellarg($domain) . " json");
    $web    = $webAll[$domain] ?? [];
    $dns    = hst_json(HESTIA_CMD . "v-list-dns-records " . quoteshellarg($owner) . " " . quoteshellarg($domain) . " json");
    $mailDomains = hst_json(HESTIA_CMD . "v-list-mail-domains " . quoteshellarg($owner) . " json");
    $hasMail = isset($mailDomains[$domain]);
    $mail   = $hasMail
        ? hst_json(HESTIA_CMD . "v-list-mail-accounts " . quoteshellarg($owner) . " " . quoteshellarg($domain) . " json")
        : [];
    $dbs    = hst_json(HESTIA_CMD . "v-list-databases " . quoteshellarg($owner) . " json");

    $docroot = $web["DOCUMENT_ROOT"] ?? "/home/$owner/web/$domain/public_html";

    // Version PHP a partir del backend (p.ej. "PHP-8_3" -> "8.3")
    $phpVersion = "";
    if (preg_match('/PHP-?(\d+)[_.-](\d+)/i', $web["BACKEND"] ?? "", $m)) {
        $phpVersion = $m[1] . "." . $m[2];
    }

    // Deteccion de WordPress en este dominio (via wrapper acotado, como root)
    $isWP = false;
    $wpList = hst_json("sudo /usr/local/hestia/bin/v-wp-manage list 2>/dev/null");
    foreach (($wpList["sites"] ?? []) as $s) {
        if (($s["domain"] ?? "") === $domain) { $isWP = true; break; }
    }
}

render_page($user, $TAB, "list_domain");
