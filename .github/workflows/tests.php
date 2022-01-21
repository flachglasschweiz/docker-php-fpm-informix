<?php

try {
    $dbh = new PDO('informix:host=ifx-test;service=9088;database=test;server=informix;protocol=olsoctcp;CLIENT_LOCALE=en_us.utf8;DB_LOCALE=en_US.1252;EnableScrollableCursors=1');
} catch (PDOException $exception) {
    echo 'No connection possible: ' . $exception;
}
