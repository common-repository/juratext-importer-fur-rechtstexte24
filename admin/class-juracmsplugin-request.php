<?php

class jura_tifr_Request {

    public static function jura_tifr_getDataFromBackend () {
        global $wpdb;

        $sql = "SELECT * FROM ".$wpdb->prefix."rechtstext;";

        $result = $wpdb->get_results($sql);

        if (!class_exists("RSA")) require_once plugin_dir_path(dirname(__FILE__))."extClasses/Crypt/RSA.php";
        $rsa = new Crypt_RSA();

        $sql = "SELECT * FROM ".$wpdb->prefix."juracms_kunde LIMIT 1";

        $kdaten = $wpdb->get_results($sql,ARRAY_A);


        if (!empty($kdaten)) {
            $answer = json_decode(file_get_contents("http://api.rechtstexte24.de/v1/index.php?kndnr=".$kdaten[0]["kundennummer"]."&apikey=".urlencode($kdaten[0]["pubkey"])));

            if ($answer != false) {
                $queries = array();
                $receivedtypes = array();
                $signature = base64_decode(array_pop($answer));

                $rsa->loadKey($kdaten[0]["pubkey"]);
                if ($rsa->verify($kdaten[0]["kundennummer"], $signature) == false) return "Daten kommen von nicht verifiziertem Server!";

                /* Check wether type is registered or not and then update or insert */
                for ($i = 0; $i < count($answer); $i++) {
                    $exists = false;
                    foreach ($result as $texte) {
                        if ($texte->typ == $answer[$i]->typ) {
                            $queries[] = "UPDATE " . $wpdb->prefix . "rechtstext SET content='" . $answer[$i]->content . "', version='" . $answer[$i]->version . "' WHERE id=".$texte->id;
                            $exists = true;
                            break;
                        }
                    }
                    if (!$exists) $queries[] = "INSERT INTO " . $wpdb->prefix . "rechtstext (typ,content,version) VALUES ('" . $answer[$i]->typ . "', '" . $answer[$i]->content . "', '" . $answer[$i]->version . "')";
                    $receivedtypes[] = $answer[$i]->typ;
                }

                /* DELETE Queries */
                if (count($answer) < count($result)) {
                    foreach ($result as $texte) {
                        if (!in_array($texte->typ, $receivedtypes)) $queries[] = "DELETE FROM ".$wpdb->prefix."rechtstext WHERE id=".$texte->id;
                    }
                }

                /* Execute Queries */
                foreach ($queries as $query) {
                    $wpdb->query($query);
                }
                return "Texte erfolgreich manuell angefordert!";
            }
            else return "Falsche Kundendaten!";
        }
        else return "Keine Kundendaten!";
    }

    public static function jura_tifr_editKunde () {
        global $wpdb;

        $sql = "SELECT * FROM ".$wpdb->prefix."juracms_kunde";

        $result = $wpdb->get_results($sql);

        extract($_POST);

        if (empty($result))
            $sql = "INSERT INTO ".$wpdb->prefix."juracms_kunde (kundennummer,pubkey) VALUES ('$kundennummer', '$pkey')";
        else
            $sql = "UPDATE ".$wpdb->prefix."juracms_kunde SET kundennummer='$kundennummer', pubkey='$pkey' WHERE ".$result[0]->id;

        $wpdb->query($sql);

        if (wp_next_scheduled ( 'jura_tifr_cron_event' )) {
            wp_clear_scheduled_hook('jura_tifr_cron_event');
        }

        if ($cronjobber == "stuendlich") {
            wp_schedule_event(time()+3, 'hourly', 'jura_tifr_cron_event');
        } else {
            wp_schedule_event(time()+3, 'daily', 'jura_tifr_cron_event');
        }

    }

}