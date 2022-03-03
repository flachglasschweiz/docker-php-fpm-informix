<?php
try {
    $dbh = new PDO(
        'informix:host=ifx-test;service=9088;database=test;server=informix;protocol=olsoctcp;CLIENT_LOCALE=en_us.utf8;DB_LOCALE=en_US.819;EnableScrollableCursors=1',
        'informix',
        'in4mix'
    );
    echo 'database connection successful';
} catch (PDOException $exception) {
    throw new PDOException($exception->getMessage());
}
