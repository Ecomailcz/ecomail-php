<?php

require_once __DIR__ . '/../src/Ecomail.php';

$ecomail = new Ecomail('API_KEY');

echo '<pre>';
print_r( $ecomail->getListsCollection() );