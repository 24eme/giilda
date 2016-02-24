<?php

$file = $argv[1];

$json = json_decode(file_get_contents($file));
foreach($json as $editions) {
    if($editions->op != "core/mass-edit") {

        continue;
    }

    foreach($editions->edits as $item) {
        foreach($item->from as $from) {
            echo $from.";".$item->to."\n";
        }
    }
}