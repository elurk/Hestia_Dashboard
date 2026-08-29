<?php
// Pestana Seguridad (Fail2ban) - controlador de render.
// Se despliega en /usr/local/hestia/web/list/security/index.php
$TAB = "SECURITY";

include $_SERVER["DOCUMENT_ROOT"] . "/inc/main.php";

// Herramienta de servidor: solo admin.
if (($_SESSION["userContext"] ?? "") !== "admin") {
    header("Location: /");
    exit();
}

$user = $_SESSION["user"] ?? null;

// Leer jails y baneados via el wrapper acotado (sudo).
$fail2ban = ["ok" => false, "jails" => []];
exec("sudo /usr/local/hestia/bin/v-fail2ban-action list 2>/dev/null", $out, $rc);
if ($rc === 0) {
    $decoded = json_decode(implode("", $out), true);
    if (is_array($decoded)) {
        $fail2ban = $decoded;
    }
}
unset($out);

render_page($user, $TAB, "list_security");
