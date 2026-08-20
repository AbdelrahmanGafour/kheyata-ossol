<?php
/**
 * تجاوز حلقة عرض المنتج الافتراضية في WooCommerce بنفس بطاقة المنتج المستخدمة
 * في الصفحة الرئيسية (ko_product_card_html) حتى يتطابق تصميم صفحة المتجر تمامًا.
 */
if ( ! defined( 'ABSPATH' ) ) exit;

global $product;
if ( ! $product || ! $product->is_visible() ) return;

echo ko_product_card_html( $product );
