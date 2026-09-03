<div id="token" token="<?= $_SESSION["token"] ?>"></div>
<script>
// Anti-anidamiento (Dashboard Manager): la vista de dominio embebe SOLO el gestor
// de archivos (/fm/). Si cualquier otra pagina del panel acaba dentro de un iframe
// (p.ej. al salir del gestor embebido), se saca al nivel superior para evitar
// panel-dentro-de-panel en bucle.
(function () {
	try {
		if (window.self !== window.top && !/^\/fm(\/|$)/.test(window.location.pathname)) {
			window.top.location.href = window.location.href;
		}
	} catch (e) {}
})();
</script>

<header class="app-header">

	<div class="top-bar">
		<div class="container top-bar-inner">

			<!-- Logo / Usage Statistics wrapper -->
			<div class="top-bar-left">

				<!-- Logo / Home Button -->
				<a href="/" class="top-bar-logo" title="<?= htmlentities($_SESSION["APP_NAME"]) ?>">
					<img src="/images/logo-header.svg" alt="<?= htmlentities($_SESSION["APP_NAME"]) ?>" width="54" height="29">
				</a>

				<!-- Usage Statistics -->
				<div class="top-bar-usage">
					<?php if ($_SESSION["look"] !== "") {
     	$user_icon = "fa-binoculars";
     } elseif ($_SESSION["userContext"] === "admin") {
     	$user_icon = "fa-user-tie";
     } else {
     	$user_icon = "fa-user";
     } ?>
					<div class="top-bar-usage-inner">
						<span class="top-bar-usage-item">
							<i class="fas <?= $user_icon ?>" title="<?= _("Logged in as") ?>: <?= htmlspecialchars($panel[$user]["NAME"]) ?>"></i>
							<span class="u-text-bold">
								<?= htmlspecialchars($user) ?>
							</span>
						</span>
						<span class="top-bar-usage-item">
							<i class="fas fa-hard-drive" title="<?= _("Disk") ?>: <?= humanize_usage_size($panel[$user]["U_DISK"]) ?> <?= humanize_usage_measure($panel[$user]["U_DISK"]) ?>"></i>
							<span class="u-text-bold">
								<?= humanize_usage_size($panel[$user]["U_DISK"]) ?>
							</span>
							<?= humanize_usage_measure($panel[$user]["U_DISK"]) ?>
							/
							<span class="u-text-bold">
							<?= humanize_usage_size($panel[$user]["DISK_QUOTA"]) ?>
							</span>
							<?= humanize_usage_measure($panel[$user]["DISK_QUOTA"]) ?>
						</span>
						<span class="top-bar-usage-item">
							<i class="fas fa-right-left" title="<?= _("Bandwidth") ?>: <?= humanize_usage_size($panel[$user]["U_BANDWIDTH"]) ?> <?= humanize_usage_measure($panel[$user]["U_BANDWIDTH"]) ?>"></i>
							<span class="u-text-bold">
								<?= humanize_usage_size($panel[$user]["U_BANDWIDTH"]) ?>
							</span>
							<?= humanize_usage_measure($panel[$user]["U_BANDWIDTH"]) ?>
							/
							<span class="u-text-bold">
								<?= humanize_usage_size($panel[$user]["BANDWIDTH"]) ?>
							</span>
							<?= humanize_usage_measure($panel[$user]["BANDWIDTH"]) ?>
						</span>
					</div>
				</div>

			</div>

			<!-- Notifications / Menu wrapper -->
			<div class="top-bar-right">

				<!-- Notifications -->
				<?php
    $impersonatingAdmin = $_SESSION["userContext"] === "admin" && ($_SESSION["look"] !== "" && $user == "admin");
    // Do not show notifications panel when impersonating 'admin' user
    if (!$impersonatingAdmin) { ?>
					<div x-data="notifications" class="top-bar-notifications">
						<button
							x-on:click="toggle()"
							x-bind:class="open && 'active'"
							class="top-bar-menu-link"
							type="button"
							title="<?= _("Notifications") ?>"
						>
							<i
								x-bind:class="{
									'animate__animated animate__swing icon-orange': (!initialized && <?= $panel[$user]["NOTIFICATIONS"] == "yes" ? "true" : "false" ?>) || notifications.length != 0,
									'fas fa-bell': true
								}"
							></i>
							<span class="u-hidden"><?= _("Notifications") ?></span>
						</button>
						<div
							x-cloak
							x-show="open"
							x-on:click.outside="open = false"
							class="top-bar-notifications-panel"
						>
							<template x-if="!initialized">
								<div class="top-bar-notifications-empty">
									<i class="fas fa-circle-notch fa-spin icon-dim"></i>
									<p><?= _("Loading...") ?></p>
								</div>
							</template>
							<template x-if="initialized && notifications.length == 0">
								<div class="top-bar-notifications-empty">
									<i class="fas fa-bell-slash icon-dim"></i>
									<p><?= _("No notifications") ?></p>
								</div>
							</template>
							<template x-if="initialized && notifications.length > 0">
								<ul>
									<template x-for="notification in notifications" :key="notification.ID">
										<li
											x-bind:id="`notification-${notification.ID}`"
											x-bind:class="notification.ACK && 'unseen'"
											class="top-bar-notification-item"
											x-data="{ open: true }"
											x-show="open"
											x-collapse
										>
											<div class="top-bar-notification-inner">
												<div class="top-bar-notification-header">
													<p x-text="notification.TOPIC" class="top-bar-notification-title"></p>
													<button
														x-on:click="open = false; setTimeout(() => remove(notification.ID), 300);"
														type="button"
														class="top-bar-notification-delete"
														title="<?= _("Delete notification") ?>"
													>
														<i class="fas fa-xmark"></i>
														<span class="u-hidden-visually"><?= _("Delete notification") ?></span>
													</button>
												</div>
												<div class="top-bar-notification-content" x-html="notification.NOTICE"></div>
												<p class="top-bar-notification-timestamp">
													<time
														:datetime="`${notification.TIMESTAMP_ISO}`"
														x-bind:title="`${notification.TIMESTAMP_TITLE}`"
														x-text="`${notification.TIMESTAMP_TEXT}`"
													></time>
												</p>
											</div>
										</li>
									</template>
								</ul>
							</template>
							<template x-if="initialized && notifications.length > 2">
								<button
									x-on:click="removeAll()"
									type="button"
									class="top-bar-notifications-delete-all"
								>
									<i class="fas fa-check"></i>
									<?= _("Delete all notifications") ?>
								</button>
							</template>
						</div>
					</div>
				<?php }
    ?>

				<!-- Menu -->
				<nav x-data="{ open: false }" class="top-bar-menu">

					<button
						type="button"
						class="top-bar-menu-link u-hide-tablet"
						x-on:click="open = !open">
						<i class="fas fa-bars"></i>
						<span class="u-hidden" x-text="open ? '<?= _("Close menu") ?>' : '<?= _("Open menu") ?>'">
							<?= _("Open menu") ?>
						</span>
					</button>

					<div x-cloak x-show="open" x-on:click.outside="open = false" class="top-bar-menu-panel">
						<ul class="top-bar-menu-list">

							<!-- File Manager -->
							<?php if (isset($_SESSION["FILE_MANAGER"]) && !empty($_SESSION["FILE_MANAGER"]) && $_SESSION["FILE_MANAGER"] == "true") { ?>
								<?php if ($_SESSION["userContext"] === "admin" && $_SESSION["look"] === "admin" && $_SESSION["POLICY_SYSTEM_PROTECTED_ADMIN"] == "yes") { ?>
									<!-- Hide file manager when impersonating admin-->
								<?php } else { ?>
									<li class="top-bar-menu-item">
										<a title="<?= _("File manager") ?>" class="top-bar-menu-link <?php if ($TAB == "FM") {
	echo "active";
} ?>" href="/fm/">
											<i class="fas fa-folder-open"></i>
											<span class="top-bar-menu-link-label u-hide-desktop"><?= _("File manager") ?></span>
										</a>
									</li>
								<?php } ?>
							<?php } ?>

							<!-- Web Terminal -->
							<?php if (isset($_SESSION["WEB_TERMINAL"]) && !empty($_SESSION["WEB_TERMINAL"]) && $_SESSION["WEB_TERMINAL"] == "true") { ?>
								<?php if ($_SESSION["userContext"] === "admin" && $_SESSION["look"] === "admin" && $_SESSION["POLICY_SYSTEM_PROTECTED_ADMIN"] == "yes") { ?>
									<!-- Hide web terminal when impersonating admin -->
								<?php } elseif ($_SESSION["login_shell"] != "nologin") { ?>
									<li class="top-bar-menu-item">
										<a title="<?= _("Web terminal") ?>" class="top-bar-menu-link <?php if ($TAB == "TERMINAL") {
	echo "active";
} ?>" href="/list/terminal/">
											<i class="fas fa-terminal"></i>
											<span class="top-bar-menu-link-label u-hide-desktop"><?= _("Web terminal") ?></span>
										</a>
									</li>
								<?php } ?>
							<?php } ?>

							<!-- Server Settings -->
							<?php if (($_SESSION["userContext"] === "admin" && $_SESSION["POLICY_SYSTEM_HIDE_SERVICES"] !== "yes") || $_SESSION["user"] === "admin") { ?>
								<?php if ($_SESSION["userContext"] === "admin" && $_SESSION["look"] !== "") { ?>
									<!-- Hide 'Server Settings' button when impersonating 'admin' or other users -->
								<?php } else { ?>
									<li class="top-bar-menu-item">
										<a title="<?= _("Server settings") ?>" class="top-bar-menu-link <?php if (in_array($TAB, ["SERVER", "IP", "RRD", "FIREWALL"])) {
	echo "active";
} ?>" href="/list/server/">
											<i class="fas fa-gear"></i>
											<span class="top-bar-menu-link-label u-hide-desktop"><?= _("Server settings") ?></span>
										</a>
									</li>
								<?php } ?>
							<?php } ?>

							<!-- Edit User -->
							<?php if ($_SESSION["userContext"] === "admin" && ($_SESSION["look"] !== "" && $user == "admin")) { ?>
								<!-- Hide 'edit user' entry point from other administrators for default 'admin' account-->
								<li class="top-bar-menu-item">
									<a title="<?= _("Logs") ?>" class="top-bar-menu-link <?php if ($TAB == "LOG") {
	echo "active";
} ?>" href="/list/log/">
										<i class="fas fa-clock-rotate-left"></i>
										<span class="top-bar-menu-link-label u-hide-desktop"><?= _("Logs") ?></span>
									</a>
								</li>
							<?php } else { ?>
								<?php if ($panel[$user]["SUSPENDED"] === "no") { ?>
									<li class="top-bar-menu-item">
										<a title="<?= htmlspecialchars($user) ?> (<?= htmlspecialchars($panel[$user]["NAME"]) ?>)" class="top-bar-menu-link" href="/edit/user/?user=<?= $user ?>&token=<?= $_SESSION["token"] ?>">
											<i class="fas fa-circle-user"></i>
											<span class="top-bar-menu-link-label u-hide-desktop"><?= htmlspecialchars($user) ?> (<?= htmlspecialchars($panel[$user]["NAME"]) ?>)</span>
										</a>
									</li>
								<?php } ?>
							<?php } ?>
							<!-- Statistics -->
							<li class="top-bar-menu-item">
								<a title="<?= _("Statistics") ?>" class="top-bar-menu-link <?php if ($TAB == "STATS") {
	echo "active";
} ?>" href="/list/stats/">
									<i class="fas fa-chart-line"></i>
									<span class="top-bar-menu-link-label u-hide-desktop"><?= _("Statistics") ?></span>
								</a>
							</li>
							<?php if ($_SESSION["HIDE_DOCS"] !== "yes") { ?>
								<!-- Help / Documentation -->
								<li class="top-bar-menu-item">
									<a title="<?= _("Help") ?>" class="top-bar-menu-link" href="https://hestiacp.com/docs/" target="_blank" rel="noopener">
										<i class="fas fa-circle-question"></i>
										<span class="top-bar-menu-link-label u-hide-desktop"><?= _("Help") ?></span>
									</a>
								</li>
							<?php } ?>
							<!-- Logout -->
							<?php if (isset($_SESSION["look"]) && !empty($_SESSION["look"])) { ?>
								<li class="top-bar-menu-item">
									<a title="<?= _("Log out") ?> (<?= $user ?>)" class="top-bar-menu-link top-bar-menu-link-logout" href="/logout/?token=<?= $_SESSION["token"] ?>">
										<i class="fas fa-circle-up"></i>
										<span class="top-bar-menu-link-label u-hide-desktop"><?= _("Log out") ?> (<?= $user ?>)</span>
									</a>
								</li>
							<?php } else { ?>
								<li class="top-bar-menu-item">
									<a title="<?= _("Log out") ?>" class="top-bar-menu-link top-bar-menu-link-logout" href="/logout/?token=<?= $_SESSION["token"] ?>">
										<i class="fas fa-right-from-bracket"></i>
										<span class="top-bar-menu-link-label u-hide-desktop"><?= _("Log out") ?></span>
									</a>
								</li>
							<?php } ?>

						</ul>
					</div>
				</nav>

			</div>

		</div>
	</div>

	<nav x-data="{ open: false }" class="main-menu">
		<div class="container">
			<button x-on:click="open = !open" type="button" class="main-menu-toggle">
				<i class="fas fa-bars"></i>
				<span
					x-text="open ? '<?= _("Collapse main menu") ?>' : '<?= _("Expand main menu") ?>'"
					class="main-menu-toggle-label"
				>
					<?= _("Expand main menu") ?>
				</span>
			</button>
			<ul x-cloak x-show="open" class="main-menu-list">

				<!-- Tablero (dashboard) - Dashboard Manager -->
				<li class="main-menu-item">
					<a class="main-menu-item-link <?php if ($TAB == "DASHBOARD") { echo "active"; } ?>" href="/list/dashboard/" title="Tablero">
						<p class="main-menu-item-label">Tablero<i class="fas fa-gauge"></i></p>
					</a>
				</li>

				<!-- Users tab -->
				<?php if ($_SESSION["userContext"] == "admin" && $_SESSION["look"] === "") { ?>
					<?php if ($_SESSION["user"] !== "admin" && $_SESSION["POLICY_SYSTEM_HIDE_ADMIN"] === "yes") {
     	$user_count = $panel[$user]["U_USERS"] - 1;
     } else {
     	$user_count = $panel[$user]["U_USERS"];
     } ?>
					<li class="main-menu-item">
						<a class="main-menu-item-link <?php if (in_array($TAB, ["USER", "LOG"])) {
      	echo "active";
      } ?>" href="/list/user/" title="<?= _("Users") ?>: <?= $user_count ?>&#13;<?= _("Suspended") ?>: <?= $panel[$user]["SUSPENDED_USERS"] ?>">
							<p class="main-menu-item-label"><?= _("USER") ?><i class="fas fa-users"></i></p>
							<ul class="main-menu-stats">
								<li>
									<?= _("Users") ?>: <?= htmlspecialchars($user_count) ?>
								</li>
								<li>
									<?= _("Suspended") ?>: <?= $panel[$user]["SUSPENDED_USERS"] ?>
								</li>
							</ul>
						</a>
					</li>
				<?php } ?>

				<!-- Web tab -->
				<?php if (isset($_SESSION["WEB_SYSTEM"]) && !empty($_SESSION["WEB_SYSTEM"])) { ?>
					<?php if ($panel[$user]["WEB_DOMAINS"] != "0") { ?>
						<li class="main-menu-item">
							<a class="main-menu-item-link <?php if ($TAB == "WEB") {
       	echo "active";
       } ?>" href="/list/web/" title="<?= _("Domains") ?>: <?= $panel[$user]["U_WEB_DOMAINS"] ?>&#13;<?= _("Aliases") ?>: <?= $panel[$user]["U_WEB_ALIASES"] ?>&#13;<?= _("Limit") ?>: <?= $panel[
	$user
]["WEB_DOMAINS"] == "unlimited"
	? "∞"
	: $panel[$user]["WEB_DOMAINS"] ?>&#13;<?= _("Suspended") ?>: <?= $panel[$user]["SUSPENDED_WEB"] ?>">
								<p class="main-menu-item-label"><?= _("WEB") ?><i class="fas fa-earth-americas"></i></p>
								<ul class="main-menu-stats">
									<li>
										<?= _("Domains") ?>: <?= $panel[$user]["U_WEB_DOMAINS"] ?> / <?= $panel[$user]["WEB_DOMAINS"] == "unlimited" ? "<span class=\"u-text-bold\">∞</span>" : $panel[$user]["WEB_DOMAINS"] ?> (<?= $panel[
 	$user
 ]["SUSPENDED_WEB"] ?>)
									</li>
									<li>
										<?= _("Aliases") ?>: <?= $panel[$user]["U_WEB_ALIASES"] ?> / <?= $panel[$user]["WEB_ALIASES"] == "unlimited" || $panel[$user]["WEB_DOMAINS"] == "unlimited"
 	? "<span class=\"u-text-bold\">∞</span>"
 	: $panel[$user]["WEB_ALIASES"] * $panel[$user]["WEB_DOMAINS"] ?>
									</li>
								</ul>
							</a>
						</li>
					<?php } ?>
				<?php } ?>

				<!-- DNS tab -->
				<?php if (isset($_SESSION["DNS_SYSTEM"]) && !empty($_SESSION["DNS_SYSTEM"])) { ?>
					<?php if ($panel[$user]["DNS_DOMAINS"] != "0") { ?>
						<li class="main-menu-item">
							<a class="main-menu-item-link <?php if ($TAB == "DNS") {
       	echo "active";
       } ?>" href="/list/dns/" title="<?= _("Domains") ?>: <?= $panel[$user]["U_DNS_DOMAINS"] ?>&#13;<?= _("Limit") ?>: <?= $panel[$user]["DNS_DOMAINS"] == "unlimited"
	? "∞"
	: $panel[$user]["DNS_DOMAINS"] ?>&#13;<?= _("Suspended") ?>: <?= $panel[$user]["SUSPENDED_DNS"] ?>">
								<p class="main-menu-item-label"><?= _("DNS") ?><i class="fas fa-book-atlas"></i></p>
								<ul class="main-menu-stats">
									<li>
										<?= _("Zones") ?>: <?= $panel[$user]["U_DNS_DOMAINS"] ?> / <?= $panel[$user]["DNS_DOMAINS"] == "unlimited" ? "<span class=\"u-text-bold\">∞</span>" : $panel[$user]["DNS_DOMAINS"] ?> (<?= $panel[
 	$user
 ]["SUSPENDED_DNS"] ?>)
									</li>
									<li>
										<?= _("Records") ?>: <?= $panel[$user]["U_DNS_RECORDS"] ?> / <?= $panel[$user]["DNS_RECORDS"] == "unlimited" || $panel[$user]["DNS_DOMAINS"] == "unlimited"
 	? "<span class=\"u-text-bold\">∞</span>"
 	: $panel[$user]["DNS_RECORDS"] * $panel[$user]["DNS_DOMAINS"] ?>
									</li>
								</ul>
							</a>
						</li>
					<?php } ?>
				<?php } ?>

				<!-- Mail tab -->
				<?php if (isset($_SESSION["MAIL_SYSTEM"]) && !empty($_SESSION["MAIL_SYSTEM"])) { ?>
					<?php if ($panel[$user]["MAIL_DOMAINS"] != "0") { ?>
						<li class="main-menu-item">
							<a class="main-menu-item-link <?php if ($TAB == "MAIL") {
       	echo "active";
       } ?>" href="/list/mail/" title="<?= _("Domains") ?>: <?= $panel[$user]["U_MAIL_DOMAINS"] ?>&#13;<?= _("Limit") ?>: <?= $panel[$user]["MAIL_DOMAINS"] == "unlimited"
	? "∞"
	: $panel[$user]["MAIL_DOMAINS"] ?>&#13;<?= _("Suspended") ?>: <?= $panel[$user]["SUSPENDED_MAIL"] ?>">
								<p class="main-menu-item-label"><?= _("MAIL") ?><i class="fas fa-envelopes-bulk"></i></p>
								<ul class="main-menu-stats">
									<li>
										<?= _("Domains") ?>: <?= $panel[$user]["U_MAIL_DOMAINS"] ?> / <?= $panel[$user]["MAIL_DOMAINS"] == "unlimited" ? "<span class=\"u-text-bold\">∞</span>" : $panel[$user]["MAIL_DOMAINS"] ?> (<?= $panel[
 	$user
 ]["SUSPENDED_MAIL"] ?>)
									</li>
									<li>
										<?= _("Accounts") ?>: <?= $panel[$user]["U_MAIL_ACCOUNTS"] ?> / <?= $panel[$user]["MAIL_ACCOUNTS"] == "unlimited" || $panel[$user]["MAIL_DOMAINS"] == "unlimited"
 	? "<span class=\"u-text-bold\">∞</span>"
 	: $panel[$user]["MAIL_ACCOUNTS"] * $panel[$user]["MAIL_DOMAINS"] ?>
									</li>
								</ul>
							</a>
						</li>
					<?php } ?>
				<?php } ?>

				<!-- Databases tab -->
				<?php if (isset($_SESSION["DB_SYSTEM"]) && !empty($_SESSION["DB_SYSTEM"])) { ?>
					<?php if ($panel[$user]["DATABASES"] != "0") { ?>
						<li class="main-menu-item">
							<a class="main-menu-item-link <?php if ($TAB == "DB") {
       	echo "active";
       } ?>" href="/list/db/" title="<?= _("Databases") ?>: <?= $panel[$user]["U_DATABASES"] ?>&#13;<?= _("Limit") ?>: <?= $panel[$user]["DATABASES"] == "unlimited"
	? "∞"
	: $panel[$user]["DATABASES"] ?>&#13;<?= _("Suspended") ?>: <?= $panel[$user]["SUSPENDED_DB"] ?>">
								<p class="main-menu-item-label"><?= _("DB") ?><i class="fas fa-database"></i></p>
								<ul class="main-menu-stats">
									<li>
										<?= _("Databases") ?>: <?= $panel[$user]["U_DATABASES"] ?> / <?= $panel[$user]["DATABASES"] == "unlimited" ? "<span class=\"u-text-bold\">∞</span>" : $panel[$user]["DATABASES"] ?> (<?= $panel[$user][
 	"SUSPENDED_DB"
 ] ?>)
									</li>
								</ul>
							</a>
						</li>
					<?php } ?>
				<?php } ?>

				<!-- Cron tab -->
				<?php if (isset($_SESSION["CRON_SYSTEM"]) && !empty($_SESSION["CRON_SYSTEM"])) { ?>
					<?php if ($panel[$user]["CRON_JOBS"] != "0") { ?>
						<li class="main-menu-item">
							<a class="main-menu-item-link <?php if ($TAB == "CRON") {
       	echo "active";
       } ?>" href="/list/cron/" title="<?= _("Jobs") ?>: <?= $panel[$user]["U_WEB_DOMAINS"] ?>&#13;<?= _("Limit") ?>: <?= $panel[$user]["CRON_JOBS"] == "unlimited"
	? "∞"
	: $panel[$user]["CRON_JOBS"] ?>&#13;<?= _("Suspended") ?>: <?= $panel[$user]["SUSPENDED_CRON"] ?>">
								<p class="main-menu-item-label"><?= _("CRON") ?><i class="fas fa-clock"></i></p>
								<ul class="main-menu-stats">
									<li>
										<?= _("Jobs") ?>: <?= $panel[$user]["U_CRON_JOBS"] ?> / <?= $panel[$user]["CRON_JOBS"] == "unlimited" ? "<span class=\"u-text-bold\">∞</span>" : $panel[$user]["CRON_JOBS"] ?> (<?= $panel[$user][
 	"SUSPENDED_CRON"
 ] ?>)
									</li>
								</ul>
							</a>
						</li>
					<?php } ?>
				<?php } ?>

				<!-- Backups tab -->
				<?php if (isset($_SESSION["BACKUP_SYSTEM"]) && !empty($_SESSION["BACKUP_SYSTEM"])) { ?>
					<?php if ($panel[$user]["BACKUPS"] != "0" || $panel[$user]["U_BACKUPS"] != "0" || $panel[$user]["BACKUPS_INCREMENTAL"] == "yes") { ?>
						<li class="main-menu-item">
							<a class="main-menu-item-link <?php if ($TAB == "BACKUP") {
       	echo "active";
       } ?>" href="/list/backup/" title="<?= _("Backups") ?>: <?= $panel[$user]["U_BACKUPS"] ?>&#13;<?= _("Limit") ?>: <?= $panel[$user]["BACKUPS"] == "unlimited" ? "∞" : $panel[$user]["BACKUPS"] ?>">
								<p class="main-menu-item-label"><?= _("BACKUP") ?><i class="fas fa-file-zipper"></i></p>
								<ul class="main-menu-stats">
									<li>
										<?= _("Backups") ?>: <?= $panel[$user]["U_BACKUPS"] ?> / <?= $panel[$user]["BACKUPS"] == "unlimited" ? "<span class=\"u-text-bold\">∞</span>" : $panel[$user]["BACKUPS"] ?>
									</li>
								</ul>
							</a>
						</li>
					<?php } ?>
				<?php } ?>

				<!-- Dominios (vista por dominio estilo Plesk) - Dashboard Manager, todos los usuarios -->
				<li class="main-menu-item">
					<a class="main-menu-item-link <?php if ($TAB == "DOMAIN") { echo "active"; } ?>" href="/list/domain/" title="<?= _("Domains") ?>">
						<p class="main-menu-item-label">Dominios<i class="fas fa-sitemap"></i></p>
					</a>
				</li>

				<!-- Security (Fail2ban) tab - Dashboard Manager, admin only -->
				<?php if (($_SESSION["userContext"] ?? "") === "admin") { ?>
					<li class="main-menu-item">
						<a class="main-menu-item-link <?php if ($TAB == "SECURITY") { echo "active"; } ?>" href="/list/security/" title="<?= _("Security") ?>: Fail2ban">
							<p class="main-menu-item-label"><?= _("Security") ?><i class="fas fa-shield-halved"></i></p>
						</a>
					</li>

					<!-- WordPress tab - Dashboard Manager, admin only -->
					<li class="main-menu-item">
						<a class="main-menu-item-link <?php if ($TAB == "WORDPRESS") { echo "active"; } ?>" href="/list/wp/" title="WordPress">
							<p class="main-menu-item-label">WordPress<i class="fab fa-wordpress"></i></p>
						</a>
					</li>
				<?php } ?>

			</ul>
		</div>
	</nav>

</header>

<main class="app-content">
<?php
// ---- Breadcrumb global (Dashboard Manager) ----
// Casa -> listado de dominios. Si venimos de un dominio (from= o domain=), enlaza
// al panel de ese dominio y, si se indica tab=, a su pestana. Se oculta en el tablero.
$__path = strtok($_SERVER["REQUEST_URI"] ?? "/", "?");
$__dom = "";
foreach (["from", "domain"] as $__k) {
	if (!empty($_GET[$__k]) && preg_match('/^[a-z0-9.-]{1,253}$/i', $_GET[$__k])) { $__dom = $_GET[$__k]; break; }
}
$__tabs = ["info" => "Panel de información", "hosting" => "Hosting y DNS", "mail" => "Correo", "files" => "Archivos", "db" => "Bases de datos", "wp" => "WordPress"];
$__tab = (!empty($_GET["tab"]) && isset($__tabs[$_GET["tab"]])) ? $_GET["tab"] : "";
$__pages = ["/edit/mail/" => "Editar correo", "/add/mail/" => "Nueva cuenta de correo", "/edit/db/" => "Editar base de datos", "/add/db/" => "Nueva base de datos", "/edit/web/" => "Editar dominio web", "/add/web/" => "Nuevo dominio", "/list/dns/" => "Zona DNS", "/edit/dns/" => "Editar DNS", "/add/dns/" => "Nuevo registro DNS", "/list/backup/" => "Copias de seguridad", "/list/cron/" => "Tareas programadas", "/list/stats/" => "Estadísticas", "/list/log/" => "Registros", "/list/security/" => "Seguridad", "/list/wp/" => "WordPress", "/list/user/" => "Usuarios", "/edit/user/" => "Editar usuario", "/add/user/" => "Nuevo usuario", "/list/web/" => "Dominios web", "/list/mail/" => "Correo", "/list/db/" => "Bases de datos"];
$__isDomainView = (strpos($__path, "/list/domain/") === 0);
$__isDashboard = (strpos($__path, "/list/dashboard/") === 0 || $__path === "/" || $__path === "");
$__a = 'style="color:#1A73B8;text-decoration:none"'; $__sep = '<span style="color:#B0BAC4">›</span>'; $__cur = 'style="color:#212529;font-weight:500"';
if (!$__isDashboard) {
	echo '<nav class="dv-crumbs global" style="display:flex;align-items:center;gap:.45rem;font-size:.85rem;color:#6B7A88;padding:.65rem 1.5rem 0;flex-wrap:wrap">';
	echo '<a href="/list/domain/" title="Dominios" ' . $__a . '><i class="fas fa-house"></i></a>';
	if ($__isDomainView) {
		if ($__dom === "") {
			echo $__sep . '<span ' . $__cur . '>Dominios</span>';
		} else {
			echo $__sep . '<a href="/list/domain/" ' . $__a . '>Dominios</a>' . $__sep . '<a href="/list/domain/?domain=' . urlencode($__dom) . '" ' . $__a . '>' . htmlspecialchars($__dom) . '</a>' . $__sep . '<span id="dv-crumb-tab" ' . $__cur . '>Panel de información</span>';
		}
	} else {
		echo $__sep . '<a href="/list/domain/" ' . $__a . '>Dominios</a>';
		if ($__dom !== "") {
			echo $__sep . '<a href="/list/domain/?domain=' . urlencode($__dom) . '" ' . $__a . '>' . htmlspecialchars($__dom) . '</a>';
			if ($__tab !== "") { echo $__sep . '<a href="/list/domain/?domain=' . urlencode($__dom) . '#' . $__tab . '" ' . $__a . '>' . $__tabs[$__tab] . '</a>'; }
		}
		$__label = "";
		foreach ($__pages as $__p => $__l) { if (strpos($__path, $__p) === 0) { $__label = $__l; break; } }
		if ($__label === "") { $__label = htmlspecialchars((string) ($TAB ?? "")); }
		if (!empty($_GET["account"]) && $__dom !== "") { $__label .= ": " . htmlspecialchars($_GET["account"] . "@" . $__dom); }
		elseif (!empty($_GET["database"])) { $__label .= ": " . htmlspecialchars($_GET["database"]); }
		echo $__sep . '<span ' . $__cur . '>' . $__label . '</span>';
	}
	echo '</nav>';
}
?>
<script>
// Boton "Atras" nativo de Hestia -> volver al DOMINIO/pestana de origen en vez de a
// la lista original (/list/dns/, /list/mail/...). El contexto viene en from=&tab=;
// como Hestia redirige tras guardar perdiendo esos parametros, se recuerda unos
// minutos en sessionStorage y se reutiliza en las paginas de dns/web/mail/db.
document.addEventListener("DOMContentLoaded", function () {
	var q = new URLSearchParams(location.search), p = location.pathname;
	if (/^\/list\/domain\//.test(p)) { return; }
	var re = /^[a-z0-9.-]{1,253}$/i;
	var dom = q.get("from") || q.get("domain") || "";
	if (!re.test(dom)) { dom = ""; }
	var tab = q.get("tab") || "";
	var tabs = { info: 1, hosting: 1, mail: 1, files: 1, db: 1, wp: 1 };
	function tabFromPath() {
		if (/\/(dns|web)\//.test(p)) { return "hosting"; }
		if (/\/mail\//.test(p)) { return "mail"; }
		if (/\/db\//.test(p)) { return "db"; }
		return "info";
	}
	if (!tabs[tab]) { tab = tabFromPath(); }
	try {
		if (dom) {
			sessionStorage.setItem("elurkDomCtx", JSON.stringify({ d: dom, t: tab, ts: Date.now() }));
		} else {
			var c = JSON.parse(sessionStorage.getItem("elurkDomCtx") || "null");
			if (c && re.test(c.d || "") && Date.now() - c.ts < 600000 && /^\/(list|edit|add)\/(dns|web|mail|db)\//.test(p)) {
				dom = c.d; tab = tabFromPath();
			}
		}
	} catch (e) {}
	if (!dom) { return; }
	var target = "/list/domain/?domain=" + encodeURIComponent(dom) + "#" + tab;
	document.querySelectorAll("a.button-back, a.js-button-back, .button-back").forEach(function (a) {
		if (a.tagName === "A") { a.setAttribute("href", target); }
		a.setAttribute("title", "Volver a " + dom);
		// Por si Hestia enganchase history.back() al boton: forzamos nuestro destino.
		a.addEventListener("click", function (e) { e.preventDefault(); e.stopImmediatePropagation(); location.href = target; }, true);
	});
});
</script>
