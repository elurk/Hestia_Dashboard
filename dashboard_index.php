<?php
use function Hestiacp\quoteshellarg\quoteshellarg;
$TAB = "DASHBOARD";

include $_SERVER["DOCUMENT_ROOT"] . "/inc/main.php";

$user = $_SESSION['user'] ?? null;
if (!$user) {
    die("No user logged in.");
}

// Get system services status
exec(HESTIA_CMD . "v-list-sys-services json", $output, $return_var);
$allServices = json_decode(implode("", $output), true);
unset($output);

// Detect services dynamically instead of hardcoding names. The old list
// pinned "apache2" and "php8.3-fpm", which breaks on nginx-only installs and
// on newer stacks (Hestia 1.10 / Ubuntu 26.04 ships PHP 8.5, so php8.3-fpm
// does not exist and PHP always showed as "unknown"). Here we pick whatever
// the box actually runs.
$services = [];
$allKeys = is_array($allServices) ? array_keys($allServices) : [];

// Web server: whichever of nginx/apache is present.
foreach (["nginx" => "Nginx", "apache2" => "Apache"] as $svc => $label) {
    if (isset($allServices[$svc])) {
        $services[$label] = $allServices[$svc]["STATE"];
    }
}

// PHP-FPM: match any phpX.Y-fpm service and label it with its version.
foreach ($allKeys as $svc) {
    if (preg_match('/^php(\d+\.\d+)-fpm$/', $svc, $m)) {
        $services["PHP " . $m[1]] = $allServices[$svc]["STATE"];
    }
}

// Fixed-name services that are stable across stacks.
foreach (["mariadb" => "Database", "exim4" => "Mail", "iptables" => "Firewall"] as $svc => $label) {
    if (isset($allServices[$svc])) {
        $services[$label] = $allServices[$svc]["STATE"];
    }
}

//Get system quick stats
exec(HESTIA_CMD . "v-list-sys-info json", $output, $return_var);
$sysinfoRaw = json_decode(implode("", $output), true);
$sysinfo = $sysinfoRaw['sysinfo'] ?? [];
unset($output);

// Calculate uptime in days/hours/mins
$uptimeMinutes = intval($sysinfo['UPTIME'] ?? 0);
$days  = floor($uptimeMinutes / 1440);
$hours = floor(($uptimeMinutes % 1440) / 60);
$mins  = $uptimeMinutes % 60;
$uptimeFormatted = "{$days}d {$hours}h {$mins}m";

// Calculate CPU usage as % of cores (Option 3)
$cpuCores = (int) trim(shell_exec("nproc"));  // total cores
$loadRaw = $sysinfo['LOADAVERAGE'] ?? '';
$loadParts = preg_split('/\s*\/\s*/', $loadRaw);
$load1 = (float) ($loadParts[0] ?? 0);
$cpuUsage = $cpuCores > 0 ? round(($load1 / $cpuCores) * 100, 1) . "%" : "N/A";

// RAM usage
$memInfo = trim(shell_exec("free -m | awk '/^Mem:/{print $2, $3}'"));
[$totalRam, $usedRam] = array_pad(array_map('intval', explode(" ", $memInfo)), 2, 0);
$ramUsagePercent = $totalRam > 0 ? round(($usedRam / $totalRam) * 100, 1) : 0;
$ramUsageFormatted = "{$usedRam}MB / {$totalRam}MB ({$ramUsagePercent}%)";

// Server Time
$serverTime = trim(shell_exec("date '+%H:%M'"));

// Get latest 4 log entries
exec(HESTIA_CMD . "v-list-user-log " . quoteshellarg($user) . " json", $output, $return_var);
$logs = json_decode(implode("", $output), true);
unset($output);

$recentLogs = [];
if (is_array($logs)) {
    $logs = array_reverse($logs); // newest first
    $recentLogs = array_slice($logs, 0, 4); // just 4 entries
} else {
    $recentLogs[] = [
        "LEVEL" => "error",
        "DATE" => date("Y-m-d"),
        "TIME" => date("H:i:s"),
        "MESSAGE" => "Unable to load logs",
        "CATEGORY" => "system",
    ];
}

// Primary domain, its SSL status, and account IP - used by panel_theme's
// General Information panel. Cheap to compute for every theme (one exec
// call), so it lives here rather than being duplicated per theme.
exec(HESTIA_CMD . "v-list-web-domains " . quoteshellarg($user) . " json", $output, $return_var);
$webDomains = json_decode(implode("", $output), true);
unset($output);

$primaryDomain = "N/A";
$primaryDomainIp = "N/A";
$sslStatus = "Not Installed";
if (is_array($webDomains) && count($webDomains) > 0) {
    $primaryDomain = array_key_first($webDomains);
    $primaryDomainIp = $webDomains[$primaryDomain]["IP"] ?? "N/A";
    $sslStatus = ($webDomains[$primaryDomain]["SSL"] ?? "no") === "yes" ? "Active" : "Not Installed";
}

$lastLoginIp = $_SERVER["REMOTE_ADDR"] ?? "N/A";

render_page($user, $TAB, "list_dashboard");