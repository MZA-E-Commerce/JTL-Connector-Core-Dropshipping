<?php
/**
 * Racing Planet Dealer Scraper
 *
 * Dieses Script ruft die Händlerseite von Racing Planet auf,
 * sendet verschiedene Postleitzahlen und extrahiert die Händlerinformationen.
 */

function readPlzFromFile($filename) {
    $plzList = [];

    if (($handle = fopen($filename, 'r')) !== FALSE) {
        while (($data = fgetcsv($handle)) !== FALSE) {
            // Sicherstellen, dass jede Zeile eine gültige PLZ enthält
            $plzList[] = str_pad($data[0], 5, '0', STR_PAD_LEFT); // Führende Nullen sicherstellen
        }
        fclose($handle);
    } else {
        die("Fehler beim Öffnen der Datei: $filename");
    }

    return $plzList;
}

// Fehleranzeige aktivieren
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Unterdrücken von libxml-Fehlern global
libxml_use_internal_errors(true);

// Speicherlimit erhöhen
ini_set('memory_limit', '256M');
// Ausführungszeit erhöhen (in Sekunden)
set_time_limit(300);

// Fehlerprotokollierung in eine Datei
$logFile = 'scraper_error.log';
ini_set('error_log', $logFile);
error_log("Script gestartet um " . date('Y-m-d H:i:s'));

// Konfiguration
$url = "https://www.racing-planet.de/dealer_locator.php";
$csvFile = "haendler_daten.csv";
$postParameter = "filter_ort";
$maxPlzCount = 10; // Maximale Anzahl an zu testenden PLZ

// Überprüfen, ob die erforderlichen Erweiterungen vorhanden sind
if (!function_exists('curl_init')) {
    die("FEHLER: Die cURL-Erweiterung ist nicht verfügbar. Bitte installieren oder aktivieren Sie die cURL-Erweiterung für PHP.");
}

if (!class_exists('DOMDocument')) {
    die("FEHLER: Die DOM-Erweiterung ist nicht verfügbar. Bitte installieren oder aktivieren Sie die DOM-Erweiterung für PHP.");
}

// Schalter für den Modus: true = Zufallsmodus, false = alle PLZ-Präfixe durchlaufen
$randomMode = false;

// Anzahl der Stichproben pro Präfix im vollständigen Modus
$samplesPerPrefix = 10;

if ($randomMode) {
    // Zufallsmodus: Generiere zufällige PLZ
    for ($i = 0; $i < $maxPlzCount; $i++) {
        // Deutsche PLZ gehen von 01000 bis 99999
        $plz = str_pad(rand(1000, 99999), 5, '0', STR_PAD_LEFT);
        $plzList[] = $plz;
    }
    echo "Zufallsmodus aktiviert - Es werden $maxPlzCount zufaellige PLZ geprueft.\n";
    error_log("Zufallsmodus aktiviert - Es werden $maxPlzCount zufaellige PLZ geprueft.");
} else {

    $plzList = readPlzFromFile('plz.csv');

        /*
    // Vollständiger Modus: Präfixe durchlaufen
    $plzPrefixes = range(0, 9); // Die ersten Ziffern der deutschen PLZ: 0-9

    // Für jedes Präfix einige Stichproben generieren
    foreach ($plzPrefixes as $prefix) {
        for ($i = 0; $i < $samplesPerPrefix; $i++) {
            $plz = $prefix . str_pad(rand(1000, 9999), 4, '0', STR_PAD_LEFT);
            $plzList[] = $plz;
        }
    }
    echo "Vollständiger Modus aktiviert - Es werden " . count($plzList) . " PLZ geprüft ($samplesPerPrefix Stichproben pro Präfix).\n";
    error_log("Vollständiger Modus aktiviert - Es werden " . count($plzList) . " PLZ geprüft.");
        */
}

// CSV-Header erstellen
$csvHeader = ["Name", "Adresse", "Ort", "Telefon", "PLZ"];
$csvData = [];

// CSV-Datei öffnen oder erstellen
$file = fopen($csvFile, 'w');
fputcsv($file, $csvHeader);

// cURL-Sitzung initialisieren
$ch = curl_init();

// Benutzerdefinierte Optionen für cURL setzen
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)');
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);

// Schleife durch die zufälligen PLZ
foreach ($plzList as $plz) {
    echo "Verarbeite PLZ: $plz<br>\n";

    // POST-Parameter setzen
    $postData = [$postParameter => $plz,
        'filter_country' => 'de'];
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);

    // Request ausführen
    $response = curl_exec($ch);

    if ($response === false) {
        echo "cURL-Fehler: " . curl_error($ch) . "\n";
        continue;
    }

    // HTML parsen
    $dom = new DOMDocument();
    @$dom->loadHTML($response);
    $xpath = new DOMXPath($dom);

    // Alle div-Container mit class="box_spalten_33" finden
    $dealerDivs = $xpath->query('//div[contains(@class, "box_spalten_33")]');

    if ($dealerDivs->length == 0) {
        echo "Keine Haendler für PLZ $plz gefunden.<br>\n";
        continue;
    }

    echo "Gefundene Haendler: " . $dealerDivs->length . "<br>\n";

    // Durch alle gefundenen Händler-Divs iterieren
    foreach ($dealerDivs as $dealerDiv) {
        $dealerId = $dealerDiv->getAttribute('id');

        // Name extrahieren (erstes div mit class="Text13Fett")
        $nameDiv = $xpath->query('.//div[contains(@class, "Text13Fett")]', $dealerDiv)->item(0);
        $name = $nameDiv ? trim($nameDiv->textContent) : "";

        // Adressinformationen (zweites div ohne Klasse)
        $addressDiv = $xpath->query('.//div[not(@class)][1]', $dealerDiv)->item(0);

        // Inhalt als HTML holen, um das <br> korrekt zu berücksichtigen
        if ($addressDiv) {
            // Adresse und Ort separat extrahieren
            $addressHtml = $dom->saveHTML($addressDiv);
            // Überprüfen, ob der HTML-Code ein <br>-Tag enthält
            if (strpos($addressHtml, '<br>') !== false || strpos($addressHtml, '<br/>') !== false || strpos($addressHtml, '<br />') !== false) {
                // Wir können den innerHTML nicht direkt bekommen, also kopieren wir den Inhalt in ein neues temporäres Dokument
                $tempDom = new DOMDocument();
                $tempNode = $tempDom->importNode($addressDiv, true);
                $tempDom->appendChild($tempNode);
                $innerHTML = $tempDom->saveHTML();

                // Regulärer Ausdruck zum Extrahieren der Inhalte vor und nach dem <br>-Tag
                if (preg_match('/<div[^>]*>(.*?)<br\s*\/?>(.*?)<\/div>/is', $innerHTML, $matches)) {
                    $address = trim($matches[1]);
                    $location = trim($matches[2]);
                } else {
                    // Fallback: Wenn kein Match, versuchen wir einen anderen Ansatz
                    $textContent = trim($addressDiv->textContent);
                    // Versuchen zu erkennen, ob ein Muster wie "Straße 123 12345 Stadt" vorhanden ist
                    if (preg_match('/^(.*?)(\d{5}\s+.*)$/s', $textContent, $matches)) {
                        $address = trim($matches[1]);
                        $location = trim($matches[2]);
                    } else {
                        $address = $textContent;
                        $location = "";
                    }
                }
            } else {
                // Wenn kein <br>-Tag gefunden wurde, dann haben wir möglicherweise nur die Adresse
                $address = trim($addressDiv->textContent);
                $location = "";
            }
        } else {
            $address = "";
            $location = "";
        }

        // PLZ aus dem Ort extrahieren (deutsches Format: PLZ Stadt)
        $locationPlz = "";
        if (preg_match('/(\d{5})\s+(.+)/', $location, $matches)) {
            $locationPlz = $matches[1];
            $location = $matches[2];
        }

        // Telefon extrahieren (div mit "Telefon:" Text)
        $phoneDiv = $xpath->query('.//div[contains(text(), "Telefon:")]', $dealerDiv)->item(0);
        $phone = $phoneDiv ? trim(str_replace("Telefon:", "", $phoneDiv->textContent)) : "";

        // Prüfen ob wir diesen Händler bereits haben (Deduplizierung anhand des Namen und der Adresse)
        $dealerKey = md5($name . '_' . $address);

        if (!isset($csvData[$dealerKey])) {
            $csvData[$dealerKey] = [
                'Name' => $name,
                'Adresse' => $address,
                'Ort' => $location,
                'Telefon' => $phone,
                'PLZ' => $locationPlz
            ];

            // In CSV schreiben
            fputcsv($file, [
                $name,
                html_entity_decode($address),
                html_entity_decode($location),
                $phone,
                $locationPlz
            ]);
            echo "Haendler gespeichert: $name<br>\n";
        }
    }

    // Kurze Pause, um den Server nicht zu überlasten
    usleep(500000); // 500ms Pause
}

// Ressourcen freigeben
curl_close($ch);
fclose($file);

echo "\n<br>Fertig! Daten wurden in $csvFile gespeichert.<br>\n";
echo "Insgesamt " . count($csvData) . " eindeutige Haendler gefunden.<br>\n";
?>