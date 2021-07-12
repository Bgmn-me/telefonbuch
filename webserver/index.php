<?php
include_once "/config.php";
include_once "/header.php";
$conn = new mysqli(DB_HOSTNAME, DB_USERNAME, DB_PASSWD, DB_DATABASE);
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = <<<SQL
    SELECT nachname, vorname FROM personen WHERE id = $id;
    SQL;
    $result = $conn->query($sql);
    if (mysqli_num_rows($result) == 1) {
        $names = $result->fetch_array();
        $nachname = $names[0];
        $vorname = $names[1];
        echo <<<HTML
        <h1>$nachname, $vorname</h1>
        HTML;
    } else {
        die("Benutzer existiert nicht!");
    }
    $sql = <<<SQL
    SELECT type, intl_vorwahl, vorwahl, nummer, zweck FROM nummern WHERE person_id = $id;
    SQL;
    $result = $conn->query($sql);
    if (mysqli_num_rows($result) > 0) {
        if (mysqli_num_rows($result) > 1) {
            echo "<h3>Nummern</h3>";
        } else {
            echo "<h3>Nummer</h3>";
        }
        echo <<<HTML
        <table>
        <thead>
            <tr>
                <th>Zweck</th>
                <th>Typ</th>
                <th>Int. Vorwahl</th>
                <th>Vorwahl/Telefon-Nr.</th>
            </tr>
        </thead>
        <tbody>
        HTML;
        $nummern = $result->fetch_all();
        foreach ($nummern as $nummer) {
            $typ = $nummer[0];
            $int_vorwahl = $nummer[1];
            $vorwahl = $nummer[2];
            $tel_nummer = $nummer[3];
            $zweck = $nummer[4];
            echo <<<HTML
            <tr><td>$zweck</td><td>$typ</td><td>$int_vorwahl</td><td>$vorwahl/$tel_nummer</td></tr>
            HTML;
        }
        echo <<<HTML
        </tbody>
        </table>
        HTML;
    }
    $sql = <<<SQL
    SELECT email, zweck FROM email_adressen WHERE person_id = $id;
    SQL;
    $result = $conn->query($sql);
    if (mysqli_num_rows($result) > 0) {
        if (mysqli_num_rows($result) > 1) {
            echo "<h3>Email-Adressen</h3>";
        } else {
            echo "<h3>Email-Adresse</h3>";
        }
        echo <<<HTML
        <table>
        <thead>
            <th>Zweck</th>
            <th>Email Adresse</th>
        </thead>
        <tbody>
        HTML;
        $emails = $result->fetch_all();
        foreach ($emails as $email) {
            $email_adresse = $email[0];
            $zweck = $email[1];
            echo <<<HTML
            <tr><td>$zweck</td><td>$email_adresse</td></tr>
            HTML;
        }
        echo <<<HTML
        </tbody>
        </table>
        HTML;
    }
    $sql = <<<SQL
    SELECT straße, hausnr, plz, stadt, land, zweck FROM adressen WHERE person_id = $id;
    SQL;
    $result = $conn->query($sql);
    if (mysqli_num_rows($result) > 0) {
        if (mysqli_num_rows($result) > 1) {
            echo "<h3>Adressen</h3>";
        } else {
            echo "<h3>Adresse</h3>";
        }
        echo <<<HTML
        <table>
        <thead>
            <tr>
                <th>Zweck</th>
                <th>Adresse</th>
            </tr>
        </thead>
        <tbody>
        HTML;
        $adressen = $result->fetch_all();
        foreach ($adressen as $adresse) {
            $straße = $adresse[0];
            $hausnr = $adresse[1];
            $plz = $adresse[2];
            $stadt = $adresse[3];
            $land = $adresse[4];
            $zweck = $adresse[5];
            echo <<<HTML
            <tr>
                <td rowspan="4">$zweck</td>
            </tr>
            <tr>
                <td>$straße $hausnr</td>
            </tr>
            <tr>
                <td>$plz $stadt</td>
            </tr>
            <tr>
                <td>$land</td>
            </tr>
            HTML;
        }
        echo <<<HTML
        </tbody>
        </table>
        HTML;
    }
} else {
    echo <<<HTML
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th class="content">Nachname, Vorname</th>
                <!--<th colspan="2"></th>-->
            </tr>
        </thead>
        <tbody>
    HTML;
    ##$conn->connect(DB_HOSTNAME, DB_USERNAME, DB_PASSWD, DB_DATABASE);
    $sql = <<<SQL
        SELECT * FROM personen;
        SQL;
    $result = $conn->query($sql);
    if (mysqli_num_rows($result) > 0) {
        $personen = $result->fetch_all();
        $counter = 1;
        foreach ($personen as $person) {
            $id = $person[0];
            $nachname = $person[1];
            $vorname = $person[2];
            echo <<<HTML
                <tr>
                    <th>$counter</th>
                    <td class="content"><a href="http://localhost/index.php?id=$id">$nachname, $vorname</a></td>
                </tr>
                HTML;
            $counter++;
        }
    } else {
        echo <<<HTML
            <tr><td colspan="6" id="empty"><p>Empty! -> <a href="/create.php">Eintrag Erstellen!</a></p></td></tr>
            HTML;
    }
}
?>
</tbody>
</table>
<?php
include_once "/footer.php";
?>