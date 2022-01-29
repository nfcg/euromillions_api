<?php

$database = "./em.sqlite"; // database location
////////////////////////////////////////////////////////////

function sqlite($method, $cmd)
{
    global $database, $values;
    try {
        if (!file_exists($database)) {
            throw new PDOException("database not found");
        }
        $db = new PDO("sqlite:$database");
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $db->exec("PRAGMA journal_mode = wal;");

        switch ($method) {
            case "query":
                try {
                    $result = $db->query($cmd);
                    return $result->fetchALL(PDO::FETCH_ASSOC);
                    $db = null;
                    unset($db);
                } catch (PDOException $e) {
                    echo "Error: " . $e->getMessage() . "<BR />Error Code: " . $e->getCode();
                    $db = null;
                    unset($db);
                    die();
                }
                break;
            case "update":
                try {
                    $update = $db->prepare($cmd);
                    $update->execute();
                    return $update->rowCount();
                    $db = null;
                    unset($db);
                } catch (PDOException $e) {
                    echo "Error: " . $e->getMessage() . "<BR />Error Code: " . $e->getCode();
                    $db = null;
                    unset($db);
                    die();
                }
                break;
            case "insert":
                try {
                    $qry = $db->prepare($cmd);
                    $qry->execute($values);
                    return $db->lastInsertId();
                    $db = null;
                    unset($db);
                } catch (PDOException $e) {
                    echo "Error: " . $e->getMessage() . "<BR />Error Code: " . $e->getCode();
                    $db = null;
                    unset($db);
                    die();
                }
                break;
            default:
                echo "Not Allowed";
                exit();
        }
    } catch (PDOException $e) {
        echo "Error: " .
            $e->getMessage() .
            "<BR />Error Code: " .
            $e->getCode();
        $db = null;
        unset($db);
        die();
    }
}

function arrayToXML($array, SimpleXMLElement $xml, $child_name)
{
    foreach ($array as $k => $v) {
        if (is_array($v)) {
            is_int($k)
                ? arrayToXML($v, $xml->addChild($child_name), $v)
                : $this->arrayToXML(
                    $v,
                    $xml->addChild(strtolower($k)),
                    $child_name
                );
        } else {
            is_int($k)
                ? $xml->addChild($child_name, $v)
                : $xml->addChild(strtolower($k), $v);
        }
    }

    return $xml->asXML();
}

$available_formats = ["txt", "xml", "json"];
$available_formats = array_fill_keys($available_formats, 1);

$f = $_REQUEST["format"];

if (!$available_formats[$f]) {
    $f = "txt";
}

$q = trim($_REQUEST["result"]);
if (!preg_match('/^[a-z0-9 .\-]+$/i', $q)) {
    $q = "";
}

if ($q == "") {
    if ($_REQUEST["v"] == "0") {
        $result = sqlite("query", "SELECT date, ball_1 || ' ' || ball_2 || ' ' || ball_3 || ' ' || ball_4 || ' ' || ball_5 AS balls, star_1 || ' ' || star_2 AS stars FROM euro_millions ORDER BY date DESC limit 1");
    } else {
        $result = sqlite("query", "SELECT date,ball_1,ball_2,ball_3,ball_4,ball_5,star_1,star_2 FROM euro_millions ORDER BY date DESC limit 1");
    }
} elseif ($q == "all") {
    if ($_REQUEST["v"] == "0") {
        $result = sqlite("query", "SELECT date, ball_1 || ' ' || ball_2 || ' ' || ball_3 || ' ' || ball_4 || ' ' || ball_5 AS balls, star_1 || ' ' || star_2 AS stars FROM euro_millions ORDER BY date DESC");
    } else {
        $result = sqlite("query", "SELECT date,ball_1,ball_2,ball_3,ball_4,ball_5,star_1,star_2 FROM euro_millions ORDER BY date DESC");
    }
} else {
    if ($_REQUEST["v"] == "0") {
        $result = sqlite("query", "SELECT date, ball_1 || ' ' || ball_2 || ' ' || ball_3 || ' ' || ball_4 || ' ' || ball_5 AS balls, star_1 || ' ' || star_2 AS stars FROM euro_millions WHERE date LIKE '%$q%' ORDER BY date DESC");
    } else {
        $result = sqlite("query", "SELECT date,ball_1,ball_2,ball_3,ball_4,ball_5,star_1,star_2 FROM euro_millions WHERE date LIKE '%$q%' ORDER BY date DESC");
    }
}

if ($f == "xml") {
    header("Content-Type: application/xml; charset=utf-8");
    echo arrayToXML($result, new SimpleXMLElement("<euromillions/>"), "drawn");
} elseif ($f == "json") {
    header("Content-Type: application/json; charset=utf-8");
    echo json_encode(["drawns" => $result], JSON_PRETTY_PRINT, JSON_UNESCAPED_UNICODE);
} else {
    header("Content-type: text/plain");
    foreach ($result as $row) {
        if ($_REQUEST["v"] == "0") {
            echo $row["date"] . " = " . $row["balls"] . " + " . $row["stars"] . "\n";
        } else {
            echo $row["date"] . " = " . $row["ball_1"] . " - " . $row["ball_2"] . " - " . $row["ball_3"] . " - " . $row["ball_4"] . " - " . $row["ball_5"] . " + " . $row["star_1"] . " - " . $row["star_2"] . "\n";
        }
    }
}

?>
