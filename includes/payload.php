<?php


function avelon_payload_order_processed( $orderId ) {

    // Getting an instance of the order object
    

    if ( get_option( 'avelon_account_id' ) && get_option( 'avelon_api_token' ) ){

        $order = wc_get_order( $orderId );

        $avlnCid = get_post_meta( $orderId, '_avln_cid', true );
        $coupons = $order->get_coupon_codes();

        if( $avlnCid || count($coupons) > 0 ) {

            $currency = $order->get_currency();
        
            $endpoint = esc_url('https://'.get_option( 'avelon_account_id' ).'.avln.me/purchase');

            $products = array();
        
            foreach ( $order->get_items() as $item_id => $item ) {
        
                if( $item['variation_id'] > 0 ){
                    $productId = $item['variation_id']; // variable product
                } else {
                    $productId = $item['product_id']; // simple product
                }
        
                $product = wc_get_product($productId);
        
                $categories = array();
                $terms = get_the_terms( $productId, 'product_cat' );
        
                foreach($terms as $term) {
                    $categories[] = $term->name;
                }

                $product_price = $item->get_total();
                $product_quantity = $item->get_quantity();
                $unit_price = $product_price / $product_quantity;

                $productObj = array(
                    'item_price' => round($unit_price, 2),
                    'item_currency' => esc_attr($currency),
                    'item_id' => esc_attr($productId),
                    'item_name' => esc_attr($item->get_name()),
                    'item_category' => esc_attr(implode(',', $categories)),
                    'item_quantity' => intval($product_quantity)
                );
        
                $products[] = $productObj;
        
            }
        
            $body = [
                'transaction_id' =>  esc_attr($orderId),
                'currency' => esc_attr($currency),
                'items' => $products,
            ];

            if($avlnCid) {
                $body["avln_cid"] = esc_attr($avlnCid);
            }
            
            if(count($coupons) > 0){
                $coupons = array_map( 'esc_attr', $coupons );
                $body["promo_codes"] = $coupons;
            }
            
            $options = [
                'body'        => wp_json_encode($body),
                'headers'     => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . esc_attr(get_option( 'avelon_api_token' ))
                ],
                'timeout'     => 60,
                'sslverify'   => false
            ];
            
            $response = wp_remote_post( $endpoint, $options );

        }

    }

}

add_action('woocommerce_checkout_order_processed', 'avelon_payload_order_processed', 10, 1);