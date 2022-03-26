<?php
//update parent stock when when you buy a variation

function woocommerce_variation_stock_by_parent($order_id, $old_status, $new_status)
{
    $order = new WC_Order($order_id);
    if (($old_status != 'on-hold' && $old_status != 'completed' && $old_status != 'processing') && ($new_status == 'processing' || $new_status == 'completed' || $new_status == 'on-hold')) {
        foreach ($order->get_items() as $item_id => $item_data) {
            $product = $item_data->get_product();
            $product_quantity  = $item_data->get_quantity();
            if ($product->is_type('variation')) {
                $parent_id = $product->get_parent_id();
                $parent_product = wc_get_product($parent_id);
                $product_id = $product->get_id();
                $product_name = $product->get_name();
                $product_categorys = get_the_terms($parent_id, 'product_cat');
                foreach ($product_categorys  as $product_category) {
                    $product_category_name = $product_category->name;
                    if ($product_category_name == 'variationStockByParent') {
                        $value1 = '20';
                        $value2 = '50';
                        $value3 = '100';
                        $stock_value = 0;
                        if (strpos($product_name, $value1) !== false) {
                            $stock_value = 20;
                        }
                        if (strpos($product_name, $value2) !== false) {
                            $stock_value = 50;
                        }
                        if (strpos($product_name, $value3) !== false) {
                            $stock_value = 100;
                        }
                        $parent_stock = $parent_product->get_stock_quantity();
                        $new_stock = $parent_stock - ($stock_value * $product_quantity);
                        wc_update_product_stock($parent_id, $new_stock);
                    }
                }
            }
        }
    } else if (($old_status == 'on-hold' || $old_status == 'completed' || $old_status == 'processing') && ($new_status == 'cancelled')) {
        foreach ($order->get_items() as $item_id => $item_data) {
            $product = $item_data->get_product();
            $product_quantity  = $item_data->get_quantity();
            if ($product->is_type('variation')) {
                $parent_id = $product->get_parent_id();
                $parent_product = wc_get_product($parent_id);
                $product_id = $product->get_id();
                $product_name = $product->get_name();
                $product_categorys = get_the_terms($parent_id, 'product_cat');
                foreach ($product_categorys  as $product_category) {
                    $product_category_name = $product_category->name;
                    if ($product_category_name == 'variationStockByParent') {
                        $value1 = '20';
                        $value2 = '50';
                        $value3 = '100';
                        $stock_value = 0;
                        if (strpos($product_name, $value1) !== false) {
                            $stock_value = 20;
                        }
                        if (strpos($product_name, $value2) !== false) {
                            $stock_value = 50;
                        }
                        if (strpos($product_name, $value3) !== false) {
                            $stock_value = 100;
                        }
                        $parent_stock = $parent_product->get_stock_quantity();
                        $new_stock = $parent_stock + ($stock_value * $product_quantity);
                        wc_update_product_stock($parent_id, $new_stock);
                    }
                }
            }
        }
    }
}
add_action('woocommerce_order_status_changed', 'woocommerce_variation_stock_by_parent', 10, 3);



//update variation stock when parent stock is updated

function add_variation_auto_stock($product)
{
    $product_id = $product->get_id();
    $current_products = $product->get_children();
    $product_categorys = get_the_terms($product_id, 'product_cat');
    foreach ($product_categorys  as $product_category) {
        $product_category_name = $product_category->name;
        if ($product_category_name == 'variationStockByParent') {
            foreach ($current_products as $child_id) {
                $child = wc_get_product($child_id);
                $child_name = $child->get_name();
                $value1 = '20';
                $value2 = '50';
                $value3 = '100';
                $stock_value = 0;
                if (strpos($child_name, $value1) !== false) {
                    $stock_value = 20;
                }
                if (strpos($child_name, $value2) !== false) {
                    $stock_value = 50;
                }
                if (strpos($child_name, $value3) !== false) {
                    $stock_value = 100;
                }

                $parent_stock = $product->get_stock_quantity();
                $new_stock_raw = $parent_stock / $stock_value;
                $new_stock = intval($new_stock_raw);
                wc_update_product_stock($child_id, $new_stock);
            }
        }
    }
}
add_action('woocommerce_product_set_stock', 'add_variation_auto_stock');

//allows only one product variation to be added to the cart

function filter_add_to_cart_validation($passed, $product_id, $quantity)
{
    foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
        $product = $cart_item['data'];
        $product_cart_id = $cart_item['product_id'];
        $product_in_cart_id = $product->get_ID();
        $product_in_cart = wc_get_product($product_in_cart_id);
        if ($product_in_cart->is_type('variation')) {
            $parent_id = $product_in_cart->get_parent_id();
            $parent = wc_get_product($parent_id);
            $parent_childs = $parent->get_children();
            $parent_categorys = get_the_terms($parent_id, 'product_cat');
            foreach ($parent_categorys  as $parent_category) {
                $parent_category_name = $parent_category->name;
                if ($parent_category_name == 'variationStockByParent') {
                    if ($parent_id == $product_id) {
                        $passed = false;
                        $message = __( "only one variation can be in the cart", "woocommerce" );
                        wc_add_notice( $message, 'error' );
                        return $passed;
                        break;
                    } else {
                        $passed = true;
                    }
                }
            }
        }
    }
    return $passed;
}
add_filter('woocommerce_add_to_cart_validation', 'filter_add_to_cart_validation', 10, 3);
?>