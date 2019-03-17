<?php

//$filename = "/home/ansaoo/Images/2013/2013-06/avril 2018 (41).JPG";
$filename = "/home/store/Images/2013/2013-06/Maternite_20190102_130050 (copie 2).jpg";
//$exif = exif_read_data($filename, "FILE,COMPUTED,IFD0,EXIF", true);
//echo "$filename<br />\n";
//foreach ($exif as $key => $section) {
//    foreach ($section as $name => $val) {
//        echo "$key.$name: $val<br />\n";
//    }
//}
//echo '<br>';
//
//echo json_encode(array_replace($exif["COMPUTED"] ?? array(),
//    $exif["IFD0"] ?? array(),
//    $exif["EXIF"] ?? array()),JSON_PARTIAL_OUTPUT_ON_ERROR);
//
//echo (new DateTime("2018:02:16 18:29:55"))->format(DATE_ATOM);
//echo dirname(dirname($filename));
//echo '<br>';
//echo basename(dirname($filename));
//echo '<br>';
//echo basename($filename);
//echo phpinfo();

echo '<br>';
preg_match("/(?P<year>\\d{4})-(?P<month>\\d{2})-(?P<day>\\d{2})_(?P<hour>\\d{2})h(?P<min>\\d{2})m(?P<sec>\\d{2})/", basename($filename), $matches);
preg_match("/(?P<year>\\d{4})(?P<month>\\d{2})(?P<day>\\d{2})_(?P<hour>\\d{2})(?P<min>\\d{2})(?P<sec>\\d{2})/", basename($filename), $matches2);
$res= $matches ?? $matches2;
if ($res) {
    $date = date_create(
        sprintf(
            "%s-%s-%s %s:%s:%s",
            $matches["year"],
            $matches["month"],
            $matches["day"],
            $matches["hour"],
            $matches["min"],
            $matches["sec"]
        ));
    echo $date->format(DATE_ISO8601);
}
if ($res) {
    $date = date_create(
        sprintf(
            "%s-%s-%s %s:%s:%s",
            $matches2["year"],
            $matches2["month"],
            $matches2["day"],
            $matches2["hour"],
            $matches2["min"],
            $matches2["sec"]
        ));
    echo $date->format(DATE_ISO8601);
}