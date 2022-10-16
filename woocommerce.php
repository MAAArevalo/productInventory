<?php
require __DIR__ . '/vendor/autoload.php';

use Automattic\WooCommerce\Client;

$woocommerce = new Client(

    'DOMAIN_NAME',

    'WC_KEY',

    'WC_SECRET',

    [

        'wp_api' => true,

        'version' => 'wc/v3',

        'query_string_auth' => true

    ]

);

?>