<?php
// Pestana Seguridad (Fail2ban) - endpoint de acciones (JSON).
// Se despliega en /usr/local/hestia/web/list/security/action.php
use function Hestiacp\quoteshellarg\quoteshellarg;

include $_SERVER["DOCUMENT_ROOT"] . "/inc/main.php";

header("Content-Type: application/json");

// Solo admin y con CSRF valido.
if (($_SESSION["userContext"] ?? "") !== "admin") {
    http_response_code(403);
    echo json_encode(["ok" => false, "error" => "forbidden"]);
    exit();
}
if (!verify_csrf($_POST, true)) {
    http_response_code(403);
    echo json_encode(["ok" => false, "error" => "bad token"]);
    exit();
}

$action = $_POST["action"] ?? "";
$bin = "sudo /usr/local/hestia/bin/v-fail2ban-action";

if ($action === "unban") {
    $jail = $_POST["jail"] ?? "";
    $ip   = $_POST["ip"] ?? "";
    exec("$bin unban " . quoteshellarg($jail) . " " . quoteshellarg($ip) . " 2>/dev/null", $out, $rc);
    echo implode("", $out) ?: json_encode(["ok" => false, "error" => "sin respuesta"]);
    exit();
}

if ($action === "whitelist") {
    $ip = $_POST["ip"] ?? "";
    exec("$bin whitelist-add " . quoteshellarg($ip) . " 2>/dev/null", $out, $rc);
    echo implode("", $out) ?: json_encode(["ok" => false, "error" => "sin respuesta"]);
    exit();
}

if ($action === "blacklist") {
    $ip = $_POST["ip"] ?? "";
    exec("$bin blacklist-add " . quoteshellarg($ip) . " 2>/dev/null", $out, $rc);
    echo implode("", $out) ?: json_encode(["ok" => false, "error" => "sin respuesta"]);
    exit();
}

http_response_code(400);
echo json_encode(["ok" => false, "error" => "accion desconocida"]);
