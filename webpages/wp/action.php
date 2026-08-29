<?php
// Pestana WordPress - endpoint de acciones (JSON).
// Se despliega en /usr/local/hestia/web/list/wp/action.php
use function Hestiacp\quoteshellarg\quoteshellarg;

include $_SERVER["DOCUMENT_ROOT"] . "/inc/main.php";

header("Content-Type: application/json");

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
$user   = $_POST["user"] ?? "";
$domain = $_POST["domain"] ?? "";
$bin = "sudo /usr/local/hestia/bin/v-wp-manage";

if ($action === "harden" || $action === "scan") {
    exec("$bin " . quoteshellarg($action) . " " . quoteshellarg($user) . " " . quoteshellarg($domain) . " 2>/dev/null", $out, $rc);
    echo implode("", $out) ?: json_encode(["ok" => false, "error" => "sin respuesta"]);
    exit();
}

http_response_code(400);
echo json_encode(["ok" => false, "error" => "accion desconocida"]);
