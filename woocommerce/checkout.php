<?php

add_action( 'woocommerce_before_order_notes', 'avln_cid_checkout_field' );
  
function avln_cid_checkout_field( $checkout ) { 

   woocommerce_form_field( 'avln_cid', array(        
      'type' => 'text',        
      'class' => array( 'form-row-wide' ),        
      'label' => 'Avln Cid',        
      'placeholder' => '',        
      'required' => false,        
      'default' => sanitize_text_field($_COOKIE['avln_cid']),        
   ), $checkout->get_value( 'avln_cid' ) ); 
}

add_action( 'woocommerce_checkout_update_order_meta', 'avln_cid_save_checkout_field' );
  
function avln_cid_save_checkout_field( $order_id ) { 
    if ( $_POST['avln_cid'] ) update_post_meta( $order_id, '_avln_cid', sanitize_text_field( $_POST['avln_cid'] ) );
}
  
add_action( 'woocommerce_admin_order_data_after_billing_address', 'avln_cid_show_new_checkout_field_order', 10, 1 );
   
function avln_cid_show_new_checkout_field_order( $order ) {    
   $order_id = $order->get_id();
   $avln_cid = get_post_meta( $order_id, '_avln_cid', true );
   if ( $avln_cid ) {
?>
      <p><strong>Avln Cid: </strong>
      <?php esc_html_e($avln_cid); ?>
      </p>
<?php
   }
}