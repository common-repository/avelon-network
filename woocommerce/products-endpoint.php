<?php
if(get_option( 'avelon_account_id' )) {
    add_action( 'rest_api_init', function () {
        register_rest_route(  '/avelon', get_option( 'avelon_account_id' ).'/products/', array(
            'methods'             => 'GET',
            'callback'            => 'avelon_get_products_feed'
        ) );
    });

    function avelon_get_products_feed(WP_REST_Request $request) {

        $shopCurrency = get_option('woocommerce_currency');

        $args = array(
            'post_type'   => 'product',
            'numberposts' => -1,
            'fields'      => 'ids',
        );

        $product_ids = get_posts($args);

        $products = array();
        foreach ($product_ids as $product_id) {
            $product = wc_get_product($product_id);
            if ($product) {
                $productData = $product->get_data();
                $imageId = $productData['image_id'];
                $imageUrl = "";
                if($imageId) {
                    $imageUrl = wp_get_attachment_image_url( $imageId, 'full' );
                    $imageUrl = wp_unslash($imageUrl);
                }
                $productData['image_url'] = $imageUrl;
                $products[] = $productData;
            }
        }

        if (!empty($products)) {
            $productfeed = array(
                "currency" => $shopCurrency,
                "products" => stripslashes_deep($products),
            );
            $jsonEncoded = json_encode($productfeed, JSON_UNESCAPED_SLASHES);
            print_r($jsonEncoded);
        } else {
            $error_response = new WP_REST_Response(array('error' => 'No products found'), 404);
            return $error_response;
        }
    }
}