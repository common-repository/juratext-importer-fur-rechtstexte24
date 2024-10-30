<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://sirconic-group.de
 * @since      1.0.0
 *
 * @package    Juracmsplugin
 * @subpackage Juracmsplugin/admin/partials
 */




$antwort = "";

if (isset($_POST["sent"])) {
    if (!class_exists("jura_tifr_Request")) require_once plugin_dir_path(dirname(__FILE__)) . "class-juracmsplugin-request.php";
    $antwort = jura_tifr_Request::jura_tifr_getDataFromBackend();
}



if (isset($_POST["editKunde"])) {
    if (!class_exists("jura_tifr_Request")) require_once plugin_dir_path(dirname(__FILE__)) . "class-juracmsplugin-request.php";
    jura_tifr_Request::jura_tifr_editKunde();
}




global $wpdb;

$sql = "SELECT * FROM ".$wpdb->prefix."rechtstext;";

$result = $wpdb->get_results($sql);

$sql = "SELECT * FROM ".$wpdb->prefix."juracms_kunde;";

$kunde = $wpdb->get_results($sql);


$typen = array();
$versionen = array();
$kundendaten = array();
foreach ($result as $texte) {
    $typen[] = $texte->typ;
    $versionen[] = $texte->version;
}
if (!empty($kunde)) {
    foreach ($kunde as $k) {
        $kundendaten["kndnr"] = $k->kundennummer;
        $kundendaten["pubkey"] = $k->pubkey;
    }
}

?>
<script>
    var typen = <?php echo json_encode($typen); echo ", versionen = ".json_encode($versionen); echo (!empty($kundendaten)) ? ", kunde = ".json_encode($kundendaten).";" : ";"?>
</script>

<div class="wrap">
    <div class="logo"><i class="fa fa-university fa-4x" aria-hidden="true"></i></div>
    <div class="header">
        <h1>JuraText-Importer für rechtstexte24</h1>
        <p class="subtitle"><!--Startseite--></p>
    </div>

    <div class="nav-container">
        <ul>
            <li class="navigator start highlighted"><i class="fa fa-play" aria-hidden="true"></i> Start</li>
            <li class="navigator leistungen"><i class="fa fa-money" aria-hidden="true"></i> Leistungen</li>
            <li class="navigator einstellungen"><i class="fa fa-cogs" aria-hidden="true"></i> Einstellungen</li>
        </ul>
    </div>

    <div class="settings">
        <i class="fa fa-book fa-4x" style="color:rgba(88, 31, 2, 0.95)" aria-hidden="true"></i>
    </div>

    <div class="convertible">
        <section id="content">

        </section>
        <aside id="credentials">
            <div class="sub-header">
                <div>
                    <h3>JuraCMS</h3>
                </div>
                <div>
                    <h3>Version: 1.0</h3>
                </div>
            </div>
            <div class="aside-logo"></div>
            <div class="description">
                <p>Warum sirconic?</p>
                <ul>
                    <li>Individuelle Software</li>
                    <li>Ausgezeichneter Service</li>
                </ul>
            </div>
            <p class="outputmessage <?php echo $antwort == "Texte erfolgreich manuell angefordert!" ? "success" : "error"; ?>"><?=$antwort?></p>
        </aside>
    </div>
</div>