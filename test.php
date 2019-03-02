<?php
    include "uuid.php";
    $uuid = new uuid();
    for ($i=0; $i < 1; $i++) { 
        echo $uuid->generate(), PHP_EOL;
    }
    