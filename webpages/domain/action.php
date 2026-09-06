<?php
// Vista por dominio - endpoint de acciones (JSON) para las pestanas Copias y WordPress.
// Se despliega en /usr/local/hestia/web/list/domain/action.php
//
// Seguridad: sesion iniciada + CSRF (verify_csrf) + el dominio debe pertenecer al usuario
// conectado (o ser admin). Todo el trabajo real lo hacen los wrappers acotados por
// sudoers (v-elurk-backup, v-wp-manage), que vuelven a validar cada argumento.
use function Hestiacp\quoteshellarg\quoteshellarg;

include $_SERVER["DOCUMENT_ROOT"] . "/inc/main.php";

header("Content-Type: application/json");

function dv_out(array $d): void { echo json_encode($d, JSON_UNESCAPED_UNICODE); exit(); }
function dv_fail(string $msg, int $code = 400): void { http_response_code($code); dv_out(["ok" => false, "error" => $msg]); }

$user = $_SESSION["user"] ?? "";
if ($user === "") { dv_fail("sesion caducada", 403); }
if (!verify_csrf($_POST, true)) { dv_fail("bad token", 403); }
$isAdmin = (($_SESSION["userContext"] ?? "") === "admin");

$action = (string) ($_POST["action"] ?? "");
$domain = trim((string) ($_POST["domain"] ?? ""));
if (!preg_match('/^[a-z0-9.-]{1,253}$/i', $domain) || strpos($domain, "..") !== false) { dv_fail("dominio invalido"); }

// Propietario real del dominio segun Hestia. Si el dominio ya no existe (se borro y se
// quiere restaurar de una copia), solo el admin puede indicar el propietario.
exec(HESTIA_CMD . "v-search-domain-owner " . quoteshellarg($domain), $o, $rc);
$owner = trim(implode("", $o));
unset($o);
if ($rc !== 0 || $owner === "") {
    $owner = trim((string) ($_POST["user"] ?? ""));
    if (!$isAdmin || !preg_match('/^[a-zA-Z0-9_-]{1,32}$/', $owner)) { dv_fail("dominio no encontrado", 404); }
} elseif (!$isAdmin && $owner !== $user) {
    dv_fail("forbidden", 403);
}

// Ejecuta un wrapper y devuelve su JSON tal cual (o un error si no respondio).
function dv_run(string $cmd): void {
    exec($cmd . " 2>/dev/null", $out, $rc);
    $raw = implode("", $out);
    if ($raw === "") { dv_out(["ok" => false, "error" => "sin respuesta del servidor (revisa sudoers)"]); }
    echo $raw;
    exit();
}
$q = fn($v) => quoteshellarg((string) $v);
$bk = "sudo /usr/local/hestia/bin/v-elurk-backup";
$wp = "sudo /usr/local/hestia/bin/v-wp-manage";

// Identificador de copia: nombre del tar o id de snapshot restic.
$backup = trim((string) ($_POST["backup"] ?? ""));
$needsBackup = in_array($action, ["bk-detail", "bk-mail-accounts", "bk-files", "bk-index", "bk-restore", "bk-restore-mail", "bk-restore-files", "bk-restore-config"], true);
if ($needsBackup && !preg_match('/^([a-zA-Z0-9_-]+\.\d{4}-\d{2}-\d{2}_\d{2}-\d{2}-\d{2}\.tar|[0-9a-f]{8,64})$/', $backup)) { dv_fail("copia invalida"); }
// Listas separadas por comas (objetos, cuentas, rutas): sin comillas ni caracteres de shell.
$list = function (string $key, int $max = 4000): string {
    $v = trim((string) ($_POST[$key] ?? ""));
    if ($v === "" || strlen($v) > $max) { return ""; }
    if (preg_match('/[\x00-\x1f"\'`$;|&<>*?\\\\]/', $v)) { dv_fail("lista invalida: $key"); }
    return $v;
};

switch ($action) {
    // ------------------------------------------------------------ Copias ----
    case "bk-list":
        dv_run("$bk list " . $q($owner));
    case "bk-status":
        dv_run("$bk status " . $q($owner));
    case "bk-detail":
        dv_run("$bk detail " . $q($owner) . " " . $q($backup) . " " . $q($domain));
    case "bk-mail-accounts":
        dv_run("$bk mail-accounts " . $q($owner) . " " . $q($backup) . " " . $q($domain));
    case "bk-files":
        $sub = $list("path", 400);
        dv_run("$bk files " . $q($owner) . " " . $q($backup) . " " . $q($domain) . ($sub !== "" ? " " . $q($sub) : ""));
    case "bk-index":
        dv_run("$bk index " . $q($owner) . " " . $q($backup) . " " . $q($domain));
    case "bk-restore":
        // Objetos completos: web/mail/dns/db (listas o "*"). Se delega en v-restore-user.
        $args = "";
        foreach (["web", "mail", "dns", "db"] as $k) {
            $v = $list($k, 600);
            if ($v !== "") { $args .= " --$k " . $q($v); }
        }
        if ($args === "") { dv_fail("nada seleccionado"); }
        // Un usuario normal solo restaura objetos de ESTE dominio (web/mail/dns); las BBDD son suyas.
        if (!$isAdmin) {
            foreach (["web", "mail", "dns"] as $k) {
                $v = $list($k, 600);
                if ($v !== "" && $v !== $domain) { dv_fail("solo puedes restaurar objetos de $domain", 403); }
            }
        }
        $notify = (($_POST["notify"] ?? "") === "yes") ? "yes" : "no";
        dv_run("$bk restore " . $q($owner) . " " . $q($backup) . $args . " --notify $notify");
    case "bk-restore-mail":
        $acc = $list("accounts", 2000);
        if ($acc === "") { dv_fail("sin cuentas"); }
        $how = (($_POST["how"] ?? "") === "folder") ? "folder" : "merge";
        dv_run("$bk restore-mail-account " . $q($owner) . " " . $q($backup) . " " . $q($domain) . " " . $q($acc) . " $how");
    case "bk-restore-files":
        $paths = $list("paths", 6000);
        if ($paths === "") { dv_fail("sin rutas"); }
        dv_run("$bk restore-files " . $q($owner) . " " . $q($backup) . " " . $q($domain) . " " . $q($paths));
    case "bk-restore-config":
        $what = (string) ($_POST["what"] ?? "");
        if (!in_array($what, ["web", "mail", "dns"], true)) { dv_fail("tipo invalido"); }
        dv_run("$bk restore-config " . $q($owner) . " " . $q($backup) . " " . $q($domain) . " $what");
    case "bk-hosts":
        if (!$isAdmin) { dv_fail("forbidden", 403); }
        dv_run("$bk hosts");

    // --------------------------------------------------------- WordPress ----
    case "wp-status":
        $refresh = (($_POST["refresh"] ?? "") === "1") ? " --refresh" : "";
        dv_run("$wp status " . $q($owner) . " " . $q($domain) . $refresh);
    case "wp-apply":
    case "wp-revert":
        $m = trim((string) ($_POST["measures"] ?? ""));
        if (!preg_match('/^([a-z_]{1,32}(,[a-z_]{1,32})*|all)$/', $m)) { dv_fail("medidas invalidas"); }
        dv_run("$wp " . substr($action, 3) . " " . $q($owner) . " " . $q($domain) . " " . $q($m));
    case "wp-update":
        $kind = (string) ($_POST["kind"] ?? ""); $slug = trim((string) ($_POST["slug"] ?? ""));
        if (!in_array($kind, ["core", "plugin", "theme"], true) || !preg_match('/^([a-zA-Z0-9._-]{1,100}|all)$/', $slug)) { dv_fail("argumentos invalidos"); }
        dv_run("$wp update " . $q($owner) . " " . $q($domain) . " $kind " . $q($slug));
    case "wp-deactivate":
        $slug = trim((string) ($_POST["slug"] ?? ""));
        if (!preg_match('/^[a-zA-Z0-9._-]{1,100}$/', $slug)) { dv_fail("slug invalido"); }
        dv_run("$wp deactivate " . $q($owner) . " " . $q($domain) . " " . $q($slug));
    case "wp-toggle":
        $tool = (string) ($_POST["tool"] ?? ""); $state = (string) ($_POST["state"] ?? "");
        if (!in_array($tool, ["debug", "maintenance", "indexing", "cache", "wpcron", "httpauth"], true) || !in_array($state, ["on", "off"], true)) { dv_fail("argumentos invalidos"); }
        $extra = "";
        if ($tool === "httpauth" && $state === "on") {
            $au = trim((string) ($_POST["authuser"] ?? "")); $ap = (string) ($_POST["authpass"] ?? "");
            if (!preg_match('/^[a-zA-Z0-9._-]{1,32}$/', $au) || strlen($ap) < 6 || strlen($ap) > 128) { dv_fail("usuario o contraseña no validos (contraseña de 6 a 128 caracteres)"); }
            $extra = " " . $q($au) . " " . $q($ap);
        }
        dv_run("$wp toggle " . $q($owner) . " " . $q($domain) . " $tool $state$extra");
    case "wp-wpcli-install":
        if (!$isAdmin) { dv_fail("forbidden", 403); }
        dv_run("$wp wpcli-install " . $q($owner));
    case "wp-vulns-refresh":
        if (!$isAdmin) { dv_fail("forbidden", 403); }
        dv_run("$wp vulns-refresh " . $q($owner) . " " . $q($domain));
}

dv_fail("accion desconocida");
