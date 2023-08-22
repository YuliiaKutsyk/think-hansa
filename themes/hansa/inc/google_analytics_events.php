<?php

// add_action('woocommerce_before_checkout_form', function(){
//     $script = "
//         <script>
//             window.dataLayer = window.dataLayer || [];
//             function gtag(){dataLayer.push(arguments);}
//             gtag('event', 'begin_checkout', {
//                 'currency': 'EUR',
//                 'value': " . WC()->cart->get_total('raw') . ",
//                 'items': [";
//         foreach (WC()->cart->get_cart() as $item) {
//             $script .= "{
//                             'item_id': '" . $item['data']->get_sku() . "',
//                             'item_name': '" . $item['data']->get_name() . "',
//                             'price': " . $item['data']->get_price() . "
//                         },";
//         }
//     $script .= "],
//             });
//         </script>
//     ";
//     echo $script;
// });

//add_action('woocommerce_new_order', function($order_id, $order){
//    $script = "
//        <script>
//            gtag('event', 'purchase', {
//                'currency': 'EUR',
//                'value': '" . WC()->cart->get_total() . "',
//                'items': [";
//    foreach ($order->get_items() as $item) {
//        $script .= "{
//                            'item_id': '" . $item['data']->get_sku() . "',
//                            'item_name': '" .  $item['data']->get_name() . "',
//                            'price': '<" . $item['data']->get_price() . "'
//                        }";
//    }
//    $script .= "],
//            });
//        </script>
//    ";
//    echo $script;
//});


//
//add_action('woocommerce_remove_cart_item', function($cart_id, $product_id){
//    $product = new WC_Product($product_id);
//    $script = "
//        <script>
//            gtag('event', 'remove_from_cart', {
//                'currency': 'EUR',
//                'value': '" . $product->get_price() . "',
//                'items': {
//                    'item_id': '" . $product->get_sku() . "',
//                    'item_name': '" . $product->get_name() . "',
//                    'price': '" . $product->get_price() . "'
//                },
//            });
//        </script>
//        ";
//    echo $script;
//}, 10, 2);
