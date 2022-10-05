<?php
/**
 * Genesass.
 *
 * These functions change the WooCommerce output.
 *
 * @package Genesass
 * @author  StudioPress
 * @license GPL-2.0-or-later
 * @link    https://github.com/gillespieza/genesass-and-genuflex/
 */



/**
 * Edit how the price is displayed re tax status.
 *
 * Displays price (margin scheme), or price (ex VAT) / price (incl VAT), depending
 * on the tax class assigned to the product.
 *
 * @return void
 */
function gearys_edit_price_display() {
	global $product;

	if ( $product ) {

		// print_r_hidden( '$product' );
		// print_r_hidden( $product );

		$product_type   = $product->get_type();
		$tax_status     = $product->get_tax_status();
		$tax_class      = $product->get_tax_class();
		$price          = $product->get_price();
		$product_id     = $product->get_id();
		$tax_class_text = '';

		$tax_rate = WC_Tax::get_rates( $tax_class );
		if ( ! empty( $tax_rate ) ) {
			$tax_rate = reset( $tax_rate );
			$tax_rate = $tax_rate['rate'];
		}

		if ( 'simple' === $product_type ) {

			// if it has a price.
			if ( $price ) {
				// print_r_pre( '$price' );
				// print_r_pre( $price );

				// print_r_pre( '$product_type' );
				// print_r_pre( $product_type );

				// print_r_pre( '$tax_status' );
				// print_r_pre( $tax_status );

				// print_r_pre( '$tax_class' );
				// print_r_pre( $tax_class );

				// print_r_pre( '$tax_rate' );
				// print_r_pre( $tax_rate );

				if ( 'taxable' === $tax_status ) {
					if ( 'margin-scheme' === $tax_class ) {
						$display_price = "
								<span class='price'>
									<span class='amount excl'>" . wc_price( $price ) . "
										<small class='woocommerce-price-suffix'>(No VAT*)</small>
									</span>
								</span>
								";
					} elseif ( 'zero-rate' === $tax_class ) {
						$display_price = "
								<span class='price'>
									<span class='amount excl'>" . wc_price( $price ) . "
										<small class='woocommerce-price-suffix'>(incl VAT, zero-rated)</small>
									</span>
								</span>
								";
					} elseif ( '' === $tax_class ) {
						if ( is_int( $tax_rate ) || is_float( $tax_rate ) ) {
							$tax_rate_percent = 1 + ( $tax_rate / 100 );
							// $price_ex_vat     = $price / $tax_rate_percent;
							$price_ex_vat   = $price;
							$price_incl_vat = $price * $tax_rate_percent;
							$display_price  = "
								<span class='price'>
									<span class='amount excl'>" . wc_price( $price_ex_vat ) . "
										<small class='woocommerce-price-suffix'>(excl VAT)</small>
									</span><br />
									<span class='amount incl'>" . wc_price( $price_incl_vat ) . "
										<small class='woocommerce-price-suffix'>(incl VAT)</small>
									</span>
								</span>
								";
						} // 23% tax
					} // tax type
				} else {
					// Not taxable.
						$display_price = "
								<span class='price'>
									<span class='amount'>" . wc_price( $price ) . "
										<small class='woocommerce-price-suffix'>(non-taxable)</small>
									</span>
								</span>
								";
				} // if taxable.
			} else {

				// if there is no price.
				$display_price = "
				<span class='price'>
					<span class='amount'>Call us for a quote</span>
				</span>
				";
			} // if price.}

			echo $display_price;
		} // if simple.
	} // if product.
}
add_filter( 'woocommerce_get_price_html', 'gearys_edit_price_display' );



/**
 * Replace Add To Cart button on archive/shop pages.
 *
 * @param string $button
 * @param object $product
 */
function gearys_replaced_add_to_cart_button( $button, $product ) {
	$button_text = __( 'Read More', 'woocommerce' );
	$button_link = $product->get_permalink();
	$button      = "<a class='button gb-button-size-small sidebar-display-none' href='{$button_link}'>{$button_text}</a>";

	return $button;
}
add_filter( 'woocommerce_loop_add_to_cart_link', 'gearys_replaced_add_to_cart_button', 10, 2 );
remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart' );
// // remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart');


// function gearys_remove_add_to_cart_buttons( $button ) {
// global $product;

// if( $product->is_type( 'external' ) ) {
// return '';
// }

// return $button;
// }


/**
 * @snippet       Change No. of Thumbnails per Row @ Product Gallery
 * @how-to        Get CustomizeWoo.com FREE
 * @author        Rodolfo Melogli
 * @testedwith    WooCommerce 5.0
 * @donate $9     https://businessbloomer.com/bloomer-armada/
 */
function bbloomer_5_columns_product_gallery( $wrapper_classes ) {
	$columns            = 3; // change this to 2, 3, 5, etc. Default is 4.
	$wrapper_classes[2] = 'woocommerce-product-gallery--columns-' . absint( $columns );
	return $wrapper_classes;
}
add_filter( 'woocommerce_single_product_image_gallery_classes', 'bbloomer_5_columns_product_gallery' );



/** Put a ribbon on auction products. */
function gearys_auction_ribbon() {
	global $product;
	$type = $product->get_type();

	if ( 'auction_simple' !== $type ) {
		return;
	}
	echo '<div class="ribbon ribbon-top-left ribbon-auction"><span>Auction</span></div>';
}
add_filter( 'woocommerce_before_shop_loop_item_title', 'gearys_auction_ribbon', 9 );


/** Put a ribbon on trade-only or sold products. */
function gearys_trade_ribbon() {
	global $product;
	$field_objs = get_field_objects();

	if ( 'Trade-Only' === $field_objs['new_or_used']['value'] ) {
		$output = '<div class="ribbon ribbon-top-left ribbon-trade"><span>Trade-Only</span></div>';
	}
	if ( 'sold' === $field_objs['new_or_used']['value'] ) {
		$output = '<div class="ribbon ribbon-top-left ribbon-sold"><span>Sold</span></div>';
	}
	echo $output;
}
add_filter( 'woocommerce_before_shop_loop_item_title', 'gearys_trade_ribbon', 9 );



/**
 * Automatically Delete Woocommerce Images After Deleting a Product.
 *
 * @param int $post_id The post ID
 */

function gearys_delete_product_images( $post_id ) {

	$product = wc_get_product( $post_id );

	if ( ! $product ) {
		return;
	}

	$featured_image_id  = $product->get_image_id();
	$image_galleries_id = $product->get_gallery_image_ids();

	if ( ! empty( $featured_image_id ) ) {
		wp_delete_post( $featured_image_id );
	}

	if ( ! empty( $image_galleries_id ) ) {
		foreach ( $image_galleries_id as $single_image_id ) {
			wp_delete_post( $single_image_id );
		}
	}
}
add_action( 'before_delete_post', 'gearys_delete_product_images', 10, 1 );


/**
 * Display products and categories / subcategories as two separate lists in product archive pages.
 *
 * @link    https://code.tutsplus.com/tutorials/woocommerce-display-product-categories-subcategories-and-products-in-two-separate-lists--cms-25479
 * @author  Rachel McCollin
 * @link    https://rachelmccollin.co.uk
 * @version 1.0
 *
 * @param array $args
 */
function tutsplus_product_subcategories( $args = array() ) {

	// identifies the current queried object and defines its id as $parentid.
	$parentid = get_queried_object_id();

	/*
	 * identify terms with the currently queried item as their parent.
	 * If this is the main shop page, it will return top-level categories;
	 * if this is a category archive, it will return subcategories.
	 * @link https://developer.wordpress.org/reference/functions/get_terms/
	 */
	$args  = array(
		'parent'     => $parentid,
		'hide_empty' => true,
		'exclude'    => 15, // exclude Uncategorised.
	);
	$terms = get_terms( 'product_cat', $args );

	if ( ! is_search() ) {if ( $terms ) {
		echo '<h2 class="woocommerce-loop-category__title">Categories</h2>';
		echo '<details open><summary></summary>';
		echo '<ul class="product-cats columns-4">';

		foreach ( $terms as $term ) {
			echo '<li class="product-category">';

			// output the thumbnail for that category.
			echo '<a href="' . esc_url( get_term_link( $term ) ) . '" class="woocommerce-LoopProduct-link ' . $term->slug . '">';
			woocommerce_subcategory_thumbnail( $term );
			echo '</a>';

			// output the title for the category.
			echo '<h2 class="woocommerce-loop-category__title">';
				echo '<a href="' . esc_url( get_term_link( $term ) ) . '" class="woocommerce-LoopProduct-link ' . $term->slug . '">';
					echo $term->name;
				echo '</a>';
			echo '</h2>';
			echo '</li>';
		}

		echo '</ul>';
		echo '</details>';
		echo '<hr class="is-style-wide" />';
		echo '<h2 class="woocommerce-loop-category__title">Products</h2>';
	}}
}
add_action( 'woocommerce_before_shop_loop', 'tutsplus_product_subcategories', 1 );


/** Remove additional notes field on checkout. */
add_filter( 'woocommerce_enable_order_notes_field', '__return_false' );





/**
 * Add custom fields to the custom weighting page on the admin side.
 *
 * @param  mixed $fields
 * @param  mixed $post_type
 * @return void
 */
function epio_blog_add_custom_field_ep_weighting( $fields, $post_type ) {
	if ( 'product' === $post_type ) {
		if ( empty( $fields['meta'] ) ) {
			$fields['meta'] = array(
				'label'    => 'Custom Fields',
				'children' => array(),
			);
		}

		// Change my_custom_field here to what you need.
		$key = 'meta.model.value';

		$fields['meta']['children'][ $key ] = array(
			'key'   => $key,
			'label' => __( 'Model', 'textdomain' ),
		);
	}

	return $fields;
}
// add_filter( 'ep_weighting_fields_for_post_type', 'epio_blog_add_custom_field_ep_weighting', 10, 2 );


function ep_custom_add_sku_field_weighting_prod_variations( $fields, $post_type ) {
	if ( 'product' === $post_type ) {
		if ( empty( $fields['meta'] ) ) {
			$fields['meta'] = array(
				'label'    => 'Custom Fields',
				'children' => array(),
			);
		}

		$keys = array(
			'meta.model.value'                           => 'Model',
			'meta.condition.value'                       => 'Condition',
			'meta.front_loader_has_frontloader.value'    => 'Has Frontloader',
			'meta.front_loader_front_loader.value'       => 'Frontloader Description',
			'meta.front_axle.value'                      => 'Front Axle',
			'meta.hydraulic_system_type.value'           => 'Hydraulic System Type',
			'meta.lighting_package.value'                => 'Lighting Package',
			'meta.additional_lights.value'               => 'Additional Lights',
			'meta.cab_suspension.value'                  => 'Cab suspension',
			'meta.software.value'                        => 'Software',
			'meta.air_conditioning.value'                => 'Airconditioning',
			'meta.extras_air_brakes.value'               => 'Air Brakes',
			'meta.extras_one_previous_owners.value'      => 'One previous owner',
			'meta.extras_other.value'                    => 'Other extras',
			'meta.transmission_type.value'               => 'Transmission',
			'meta.other_operator_station_features.value' => 'Other cab features',

		);

		foreach ( $keys as $key => $label ) {
			$fields['attributes']['children'][ $key ] = array(
				'key'   => $key,
				'label' => $label,
			);
		}
	}

	return $fields;
}
add_filter( 'ep_weighting_fields_for_post_type', 'ep_custom_add_sku_field_weighting_prod_variations', 10, 2 );


/**
 * Restrict search to current category only.
 *
 * Filter the search form to include a hidden field to restrict the search to that category only.
 *
 * @param  mixed $form
 * @return void
 */
function gearys_get_product_search_form( $form ) {
	if ( get_query_var( 'taxonomy' ) && get_query_var( 'taxonomy' ) === 'product_cat' ) {
		$cat_id  = get_queried_object_id();
		$pattern = '/<form[Ss]*?>/i';
		$add     = '${0}<input type="hidden" name="product_cat" id="product_cat" value="' . $cat_id . '" /><!-- GREP -->';
		$form    = preg_replace( $pattern, $add, $form );
	}
	return $form;
}
add_filter( 'get_product_search_form', 'gearys_get_product_search_form' );
