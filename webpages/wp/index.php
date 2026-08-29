<?php
// Pestana WordPress - controlador de render.
// Se despliega en /usr/local/hestia/web/list/wp/index.php
$TAB = "WORDPRESS";

include $_SERVER["DOCUMENT_ROOT"] . "/inc/main.php";

if (($_SESSION["userContext"] ?? "") !== "admin") {
    header("Location: /");
    exit();
}

$user = $_SESSION["user"] ?? null;

$wp = ["ok" => false, "sites" => []];
exec("sudo /usr/local/hestia/bin/v-wp-manage list 2>/dev/null", $out, $rc);
if ($rc === 0) {
    $decoded = json_decode(implode("", $out), true);
    if (is_array($decoded)) {
        $wp = $decoded;
    }
}
unset($out);

render_page($user, $TAB, "list_wp");
