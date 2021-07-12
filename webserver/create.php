<?php
if ($_SERVER['REQUEST_METHOD'] == "GET") {
    include_once "/header.php";
    $json = file_get_contents("/json/mobil_vorwahlen_de.json", 1);
    $json_obj = json_decode($json);
    sort($json_obj);
    $PhonePrefixes = <<<HTML
        <option value="-1">Auswahl</option>\n
        <option value="-2" disabled>Mobilfunk</option>\n
    HTML;
    foreach ($json_obj as $prefix) {
        $PhonePrefixes_temp = <<<HTML
            <option value="$prefix">$prefix</option>\n
        HTML;
        $PhonePrefixes .= $PhonePrefixes_temp;
    }
    echo <<<HTML
    <table id="createForm">
        <tr id="row-1"><td colspan="3"></td></tr>
        <tr id="row-2">
            <td id="col-1"></td>
            <td id="col-2">
            <div id="create_form">
                <form action="/create.php" method="POST">
                        <label for="firstname">Vorname</label>
                        <div id="firstname">
                            <input type="text" name="FirstName" placeholder="Max" required>
                        </div>
                        <label for="lastname">Nachname</label>
                        <div id="lastname">
                            <input type="text" name="LastName" placeholder="Mustermann">
                        </div>
                    <label for="TelNumber">Telefonnummer</label>
                    <div id="TelNumber">
                        <select name="ZweckTelNr">
                            <option value="-1">Auswahl</option>
                            <option value="Privat">Privat</option>
                            <option value="Geschäftlich">Geschäftlich</option>
                        </select>
                        <select name="Type">
                            <option value="-1">Auswahl</option>
                            <option value="Festnetz">Festnetz</option>
                            <option value="Mobilfunk">Mobilfunk</option>
                            <option value="Fax">Fax</option>
                        </select>
                        <select name="IntPrefix">
                            <option value="-1">Auswahl</option>
                            <option value="+49">DE +49</option>
                        </select>
                        <select name="PhonePrefix">
                            $PhonePrefixes
                        </select>
                        <input type="number" name="PhoneNumber" placeholder="4567890">
                    </div>
                    <label for="email">Email-Adresse (Optional)</label>
                    <div id="email">
                        <select name="ZweckEmail">
                            <option value="-1">Auswahl</option>
                            <option value="Privat">Privat</option>
                            <option value="Geschäftlich">Geschäftlich</option>
                        </select>
                        <input type="email" name="email" placeholder="max.mustermann@example.com">
                    </div>
                    <label for="zweck_adresse">Zweck</label>
                    <div id="zweck_adresse">
                        <select name="ZweckAdresse">
                            <option value="-1">Auswahl</option>
                            <option value="Privat">Privat</option>
                            <option value="Geschäftlich">Geschäftlich</option>
                        </select>
                    </div>
                        <label for="street">Straße und Hausnummer (Optional)</label>
                        <div id="street">
                            <input type="text" name="Street" placeholder="Musterstraße">
                            <input type="text" name="HouseNumber" placeholder="12 oder 12a">
                        </div>
                    <label for="zipcode">PLZ und Stadt (Optional)</label>
                    <div class="from_inline">
                        <input type="number" name="ZipCode" placeholder="12345" min="10000" max="99999"> 
                        <input type="text" name="City" placeholder="Musterstadt">
                    </div>
                    <label for="country">Land (Optional)</label>
                    <div id="country">
                        <select name="Country">
                            <option value="-1">Auswahl</option>
                            <option value="Germany">Deutschland</option>
                            <option value="Austria">Österreich</option>
                            <option value="Swiss">Schweiz</option>
                        </select>
                    </div>
                    <button type="submit">Erstellen</button>
                </form>
            </div>
        </td>
        <td id="col-3"></td>
        </tr>
        <tr id="row-3"><td colspan="3"></td></tr>
    </table>
    HTML;
    include_once "/footer.php";
} else if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if (isset($_POST)) {
        include_once "/config.php";
        $conn = new mysqli(DB_HOSTNAME, DB_USERNAME, DB_PASSWD, DB_DATABASE);
        $vorname = $_POST['FirstName'];
        $nachname = $_POST['LastName'];
        $zweckTelNr = $_POST['ZweckTelNr'];
        $typ = $_POST['Type'];
        $intvorwahl = $_POST['IntPrefix'];
        $vorwahl = $_POST['PhonePrefix'];
        $telnummer = $_POST['PhoneNumber'];
        $zweckEmail = $_POST['ZweckEmail'];
        $email = $_POST['email'];
        $zweckAdresse = $_POST['ZweckAdresse'];
        $straße = $_POST['Street'];
        $hausnr = $_POST['HouseNumber'];
        $plz = $_POST['ZipCode'];
        $stadt = $_POST['City'];
        $land = $_POST['Country'];
        $sql = <<<SQL
        INSERT INTO personen (nachname, vorname) VALUES ('$nachname', '$vorname');
        SQL;
        if (!$conn->query($sql)) {
            die("Error! Benutzer konnte nicht in die Datenbank eintragen werden.");
        }
        echo $insert_id = $conn->insert_id;
        $sql = <<<SQL
        INSERT INTO nummern (zweck, type, intl_vorwahl, vorwahl, nummer, person_id) VALUES ('$zweckTelNr', '$typ', '$intvorwahl', '$vorwahl', '$telnummer', $insert_id);
        SQL;
        if (!$conn->query($sql)) {
            die("Error! Telefonnummer konnte nicht in die Datenbank eintragen werden.");
        }
        $sql = <<<SQL
        INSERT INTO email_adressen (zweck, email, person_id) VALUES ('$zweckEmail', '$email', $insert_id);
        SQL;
        if (!$conn->query($sql)) {
            die("Error! Email konnte nicht in die Datenbank eintragen werden.");
        }
        $sql = <<<SQL
        INSERT INTO adressen (zweck, straße, hausnr, plz, stadt, land, person_id) VALUES ('$zweckAdresse', '$straße', '$hausnr', '$plz', '$stadt', '$land', $insert_id);
        SQL;
        if (!$conn->query($sql)) {
            die("Error! Adresse konnte nicht in die Datenbank eintragen werden.");
        }
    }
    header("Location: https://localhost/");
} else {
    die("Request method \"" . $_SERVER['REQUEST_METHOD'] . "\" is not allowed");
}
