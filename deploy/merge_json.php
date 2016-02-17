<?php
/**
 * Get one JSON file (arg #1), unset keys read from text file (arg. #2) and merge with other JSON file (arg. #3).
 * Backup first JSON and save result with the same name (as first JSON).
 */
/* include autoload to use composer's modules in this utility */
require_once(__DIR__ . '/../work/vendor/autoload.php');

use Flancer32\Lib\DataObject;

if($argc >= 3) {
    /* parse arguments: $src $additions */
    $fileComposer = $argv[1];
    $fileUnset = $argv[2];
    $fileOpts = $argv[3];
    /* load original 'composer.json' */
    $main = load_json($fileComposer);
    /* Load list to filter extra data and unset it */
    $unset = load_json($fileUnset);
    foreach($unset->getData() as $item) {
        $main->unsetData($item);
    }
    /* load additional options */
    $opts = load_json($fileOpts);
    /* merge both JSONs and save as source with suffix '.merged' */
    $arrMerged = array_merge_recursive($main->getData(), $opts->getData());
    $jsonMerged = json_encode($arrMerged, JSON_UNESCAPED_SLASHES);
    file_put_contents($fileComposer . '.merged.json', $jsonMerged);
    /* backup original source file and replace it by merged */
    $tstamp = date('.YmdHis');
    rename($fileComposer, $fileComposer . $tstamp);
    rename($fileComposer . '.merged.json', $fileComposer);
} else {
    $iAm = __FILE__;
    echo "\nUsage: $iAm 'source.json' 'unset.json' 'opts_to_add.json'";
}
return;


function load_json($file) {
    $jsonFile = file_get_contents($file);
    $arr = json_decode($jsonFile, true);
    $result = new DataObject($arr);
    return $result;
}