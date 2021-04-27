<?php
	/**
		* Proper way to enqueue scripts and styles
	*/

	function theme_name_scripts() {
		wp_enqueue_style( 'style-name', get_stylesheet_uri() );
		wp_enqueue_script('jquery');
		wp_enqueue_script( 'modernizr', get_template_directory_uri() . '/js/modernizr.js', array(), '1.0.0', true );
		wp_enqueue_script( 'website main', get_template_directory_uri() . '/js/main.js', array(), '1.0.0', true );
	}
    // add the scripts
	add_action( 'wp_enqueue_scripts', 'theme_name_scripts' ); 
    //add custom theme support for thumbnails
	add_theme_support( 'post-thumbnails' ); 
	 // define the custom thumb size to refere on the blogs
	add_image_size( 'post-thumb-high', 700, 250, true ); 
    // add custom menu support
	add_theme_support( 'menus' ); 

function mytheme_add_woocommerce_support() {
	add_theme_support( 'woocommerce' );
}
add_action( 'after_setup_theme', 'mytheme_add_woocommerce_support' );

// Remove the sorting dropdown from Woocommerce
remove_action( 'woocommerce_before_shop_loop' , 'woocommerce_catalog_ordering', 30 );
// Remove the result count from WooCommerce
remove_action( 'woocommerce_before_shop_loop' , 'woocommerce_result_count', 20 );

//remove realted products
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );


//remove metas
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );


remove_theme_support( 'wc-product-gallery-zoom' );
remove_theme_support( 'wc-product-gallery-lightbox' );
remove_theme_support( 'wc-product-gallery-slider' );

remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );

//move product description higher
/**remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20 );

add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 6 );
add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 9 );**/

/**
 * Remove the breadcrumbs 
 */
add_action( 'init', 'woo_remove_wc_breadcrumbs' );
function woo_remove_wc_breadcrumbs() {
    remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0 );
}



//Page Nav
function pagination($pages = '', $range = 4)
{  
     $showitems = ($range * 2)+1;  
 
     global $paged;
     if(empty($paged)) $paged = 1;
 
     if($pages == '')
     {
         global $wp_query;
         $pages = $wp_query->max_num_pages;
         if(!$pages)
         {
             $pages = 1;
         }
     }   
 
     if(1 != $pages)
     {
        // echo "<div class=\"page-numbers\"><ul><li>Page ".$paged." of ".$pages."</li>";
echo "<div class=\"page-numbers\"><ul>";
         if($paged > 2 && $paged > $range+1 && $showitems < $pages) echo "<a href='".get_pagenum_link(1)."'>&laquo; First</a>";
         if($paged > 1 && $showitems < $pages) echo "<a href='".get_pagenum_link($paged - 1)."'>&lsaquo; Previous</a>";
 
         for ($i=1; $i <= $pages; $i++)
         {
             if (1 != $pages &&( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems ))
             {
                 echo ($paged == $i)? "<li class=\"current\">".$i."</li>":"<a href='".get_pagenum_link($i)."' class=\"inactive\">".$i."</a>";
             }
         }
 
         if ($paged < $pages && $showitems < $pages) echo "<a href=\"".get_pagenum_link($paged + 1)."\">Next &rsaquo;</a>";  
         if ($paged < $pages-1 &&  $paged+$range-1 < $pages && $showitems < $pages) echo "<a href='".get_pagenum_link($pages)."'>Last &raquo;</a>";
         echo "</ul></div>\n";
     }
}


/**
 * Show cart contents / total Ajax
 */
add_filter( 'woocommerce_add_to_cart_fragments', 'woocommerce_header_add_to_cart_fragment' );

function woocommerce_header_add_to_cart_fragment( $fragments ) {
    global $woocommerce;

    ob_start();

    ?>
    <a class="cart-customlocation" href="<?php echo esc_url(wc_get_cart_url()); ?>" title="<?php _e('View your shopping cart', 'woothemes'); ?>"><?php echo sprintf(_n('%d item', '%d items', $woocommerce->cart->cart_contents_count, 'woothemes'), $woocommerce->cart->cart_contents_count);?> - <?php echo $woocommerce->cart->get_cart_total(); ?></a>
    <?php
    $fragments['a.cart-customlocation'] = ob_get_clean();
    return $fragments;
}
