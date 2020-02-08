<?php

date_default_timezone_set('UTC');
set_time_limit(0);
error_reporting(E_ALL);
require_once('MysqliDb.php');
if($_SERVER['HTTP_HOST'] == "local.puneet.com"){
    define('DB_NAME', 'local_database');
    define('DB_USER', 'root');
    define('DB_PASSWORD', 'puneetyadav');
    define('DB_HOST', '127.0.0.1');
}

function p($obj, $exec=FALSE){

    echo "<pre>";
    print_r($obj);

    if(!$exec) die;
}

function dateDifference($date_1 , $date_2 , $differenceFormat = '%h' )
{
    $datetime1 = date_create($date_1);
    $datetime2 = date_create($date_2);

    $interval = date_diff($datetime1, $datetime2);

    return $interval->format($differenceFormat);

}

function slugify($text)
{
    $text = preg_replace('~[^\pL\d]+~u', '-', $text); // replace non letter or digits by -
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text); // transliterate
    $text = preg_replace('~[^-\w]+~', '', $text); // remove unwanted characters
    $text = trim($text, '-'); // trim
    $text = preg_replace('~-+~', '-', $text); // remove duplicate -
    $text = strtolower($text); // lowercase

    return $text;
}

function bfSlug($string) {
    $string = html_entity_decode($string);
    $arr = array("ˆ" => '-', "‡" => '-', "Š" => '-', "‰" => '-', "" => '-',
        "Ž" => '-', "‘" => '-', "" => '-', "“" => '-', "’" => '-',
        "•" => '-', "”" => '-', "˜" => '-', "—" => '-', "š" => '-',
        "™" => '-', "" => '-', "œ" => '-', "Ÿ" => '-', "ž" => '-',
        "–" => '-', "" => '-', "á" => '-', "/" => '-', "_" => '-',
        "," => '-', ":" => '-', ";" => '-');
    $string = strtr($string,  $arr);
    $string = strtolower(trim($string));
    $string = preg_replace('/[^a-z0-9\\-\\ ]/', '', $string);
    $string = preg_replace("/\s+/", "-", $string);
    $string = preg_replace("/-+/", "-", $string);

    if(substr($string, strlen($string) - 1, strlen($string)) === "-") {
        $string = substr($string, 0, strlen($string) - 1);
    }

    return $string;
}

function asyncAPIRequest($url, $payload, $type="POST", $contentType = 'application/x-www-form-urlencoded', $debug=false) {

    $cmd = "curl -X $type -H 'Content-Type: {$contentType}' -H 'Accept: {$contentType}'";
    $cmd.= " -d '" . $payload . "' " . "'" . $url . "'";

    if (!$debug) {
        $cmd .= " > /dev/null 2>&1 &";
    }

    exec($cmd, $output, $exit);
    return $exit == 0;
}

function thousandsCurrencyFormat($num)
{
    $num = (0 + str_replace(",", "", $num));
    $x = round($num);
    if ($x <= 999) {
        return $x;
    }
    $x_number_format = number_format($x);
    $x_array = explode(',', $x_number_format);
    $x_parts = array('K', 'M', 'B', 'T');
    $x_count_parts = count($x_array) - 1;
    $x_display = $x;
    $x_display = $x_array[0] . ((int)$x_array[1][0] !== 0 ? '.' . $x_array[1][0] : '');
    $x_display .= $x_parts[$x_count_parts - 1];
    return $x_display;
}

function getDomain( $website ){
    $domain = str_replace( 'https://', '',  $website);
    $domain = str_replace( 'http://', '',  $domain);
    $domain = str_replace( 'www.', '',  $domain);
    $domain = explode( '/', $domain);
    return trim($domain[0]);
}

function checkIsGeneric( $email, $genericEmails ){
    foreach ($genericEmails as $genericEmail) {
        $emailFirstPart = explode('@', $email);
        if (strpos($emailFirstPart[0], $genericEmail['email']) !== FALSE) {
            return true;
        }
    }
    return false;
}

function convertNumberToUsFormat($value) {
    if ($value > 1000000000000) return round($value/1000000000000).'T';
    elseif ($value > 1000000000) return round($value/1000000000).'B';
    elseif ($value > 1000000) return round($value/1000000).'M';
    elseif ($value > 1000) return round($value/1000).'K';
    return number_format($value);
}

$db = new MysqliDb(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
