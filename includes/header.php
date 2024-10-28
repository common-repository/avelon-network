<?php

add_action( 'wp_enqueue_scripts', 'avelon_enqueue_header_script' );

function avelon_enqueue_header_script(){

    $accountName = get_option( 'avelon_account_id' );

    if($accountName) {

        wp_enqueue_script( 'avelon-header-script', 'https://'.$accountName.'.avln.me/t.js', array(), '1.0' );
        
    }

};