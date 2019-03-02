<?php
    include "UUID.php";
    $uuid = new UUID();
    for ($i=0; $i < 1; $i++) { 
        echo $uuid->generate(), PHP_EOL;
    }
    