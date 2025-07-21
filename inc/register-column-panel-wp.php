<?php 
// ============================
// Exportar productos WooCommerce
// ============================
function export_woocommerce_products_full() {
    if (!current_user_can('manage_woocommerce')) {
        wp_die('No tenés permisos para acceder.');
    }

    while (ob_get_level()) {
        ob_end_clean();
    }

    header('Content-Type: text/csv; charset=UTF-8');
    header('Content-Disposition: attachment; filename="productos_woocommerce.csv"');
    header('Pragma: no-cache');
    header('Expires: 0');

    $output = fopen('php://output', 'w');

    $headers = [
        'ID', 'Nombre', 'Content', 'Excerpt', 'Permalink', 'Post Type', 
        'Regular Price', 'Sale Price', 'Stock Status', 'Stock', 'Featured',
        'Weight', 'Length', 'Width', 'Height',
        'Image URL', 'Image Title', 'Image Description', 'Image Featured', 'Attachment URL',
        'Up-Sells', 'Cross-Sells'
    ];

    $custom_fields = [
        'dimensiones_ancho', 'dimensiones_alto', 'dimensiones_profundidad', 'dimensiones_peso',
        'dimensiones_diametro_total', 'dimensiones_diametro_coccion', 'potencia', 'caracteristicas',
        '3_cuotas', '6_cuotas', '12_cuotas',
        'descargas_manual', 'descargas_ficha_tecnica', 'descargas_cad_del_producto',
        'video', 'video_2', 'youtube', 'youtube_2'
    ];

    $attribute_taxonomies = wc_get_attribute_taxonomies();
    $attributes = [];
    foreach ($attribute_taxonomies as $taxonomy) {
        $attributes[] = 'pa_' . $taxonomy->attribute_name;
    }

    $headers = array_merge($headers, $attributes, $custom_fields);
    fputcsv($output, $headers);

    $products = wc_get_products(['limit' => -1]);

    foreach ($products as $product) {
        $product_id = $product->get_id();
        $post = get_post($product_id);

        $row = [
            $product_id,
            $product->get_name(),
            $post->post_content,
            $post->post_excerpt,
            get_permalink($product_id),
            get_post_type($product_id),
            $product->get_regular_price(),
            $product->get_sale_price(),
            $product->get_stock_status(),
            $product->get_stock_quantity(),
            $product->is_featured() ? 'Yes' : 'No',
            $product->get_weight(),
            $product->get_length(),
            $product->get_width(),
            $product->get_height()
        ];

        $image_id = $product->get_image_id();
        $image_url = wp_get_attachment_url($image_id);
        $image_title = get_the_title($image_id);
        $image_description = get_post_meta($image_id, '_wp_attachment_image_alt', true);
        $attachment_url = $image_id ? wp_get_attachment_url($image_id) : '';

        $row = array_merge($row, [
            $image_url,
            $image_title,
            $image_description,
            $image_url,
            $attachment_url
        ]);

        $up_sells = array_map(function ($id) { return get_the_title($id); }, $product->get_upsell_ids());
        $cross_sells = array_map(function ($id) { return get_the_title($id); }, $product->get_cross_sell_ids());

        $row[] = !empty($up_sells) ? implode(', ', $up_sells) : '';
        $row[] = !empty($cross_sells) ? implode(', ', $cross_sells) : '';

        foreach ($attributes as $attribute) {
            $attribute_value = wc_get_product_terms($product_id, $attribute, ['fields' => 'names']);
            $row[] = !empty($attribute_value) ? implode(', ', $attribute_value) : '';
        }

        foreach ($custom_fields as $field) {
            $field_value = get_post_meta($product_id, $field, true);
            if (in_array($field, ['descargas_manual', 'descargas_ficha_tecnica', 'descargas_cad_del_producto'])) {
                $field_value = wp_get_attachment_url($field_value) ?: $field_value;
            }
            $row[] = $field_value;
        }

        fputcsv($output, $row);
    }

    fclose($output);
    exit;
}

// ============================
// Exportar puntos de venta (PDV)
// ============================
function export_pdv_custom_post_csv() {
    if (!current_user_can('manage_options')) {
        wp_die('No tenés permisos para acceder.');
    }

    while (ob_get_level()) {
        ob_end_clean();
    }

    header('Content-Type: text/csv; charset=UTF-8');
    header('Content-Disposition: attachment; filename="puntos_de_venta.csv"');
    header('Pragma: no-cache');
    header('Expires: 0');

    $output = fopen('php://output', 'w');
    $headers = [
        'ID', 'Título','Adherido a la oferta', 'Nombre', 'Dirección', 'Ciudad',
        'Provincia', 'Latitud', 'Longitud', 'Web', 'Teléfono', 'Email'
    ];
    fputcsv($output, $headers);

    $pdvs = get_posts([
        'post_type' => 'pdv',
        'posts_per_page' => -1,
        'post_status' => 'publish'
    ]);

    foreach ($pdvs as $post) {
        $post_id = $post->ID;
        $row = [
            $post_id,
            get_the_title($post_id),
            get_field('adherido_a_la_oferta', $post_id),
            get_field('nombre', $post_id),
            get_field('direccion', $post_id),
            get_field('ciudad', $post_id),
            get_field('provincia', $post_id),
            get_field('lat', $post_id),
            get_field('long', $post_id),
            get_field('web', $post_id),
            get_field('telefono', $post_id),
            get_field('mail', $post_id)
        ];
        fputcsv($output, $row);
    }

    fclose($output);
    exit;
}

// ============================
// Exportar ventas de WooCommerce
// ============================
function exportar_ventas_woocommerce_csv() {
    if (!current_user_can('manage_woocommerce')) {
        wp_die('No tenés permisos para acceder.');
    }

    ignore_user_abort(true);
    nocache_headers();

    while (ob_get_level()) {
        ob_end_clean();
    }

    header('Content-Type: text/csv; charset=UTF-8');
    header('Content-Disposition: attachment; filename="ventas_woocommerce.csv"');
    header('Pragma: no-cache');
    header('Expires: 0');

    $output = fopen('php://output', 'w');

    $headers = [
        'Nº Pedido', 'Fecha', 'Cliente', 'Email',
        'Productos', 'Cantidad', 'Categorías', 'Total', 'Envío',
        'Estado', 'Reembolsado'
    ];
    fputcsv($output, $headers);

    $orders = wc_get_orders([
        'limit' => -1,
        'status' => ['wc-completed', 'wc-processing', 'wc-refunded'],
        'return' => 'objects'
    ]);

    foreach ($orders as $order) {
        if (!is_a($order, 'WC_Order')) continue;

        // Agrupar productos por nombre
        $items_grouped = [];

        foreach ($order->get_items() as $item) {
            $product = $item->get_product();
            if (!$product) continue;

            $name = $product->get_name();
            if (!isset($items_grouped[$name])) {
                $items_grouped[$name] = [
                    'qty' => 0,
                    'cats' => [],
                    'product' => $product,
                ];
            }
            $items_grouped[$name]['qty'] += $item->get_quantity();

            $terms = get_the_terms($product->get_id(), 'product_cat');
            if ($terms && !is_wp_error($terms)) {
                foreach ($terms as $term) {
                    $items_grouped[$name]['cats'][] = $term->name;
                }
            }
        }

        // Si solo hay un producto (o varios iguales), generar una sola fila
        if (count($items_grouped) === 1) {
            foreach ($items_grouped as $name => $data) {
                $row = [
                    $order->get_id(),
                    $order->get_date_created() ? $order->get_date_created()->format('Y-m-d') : '',
                    $order->get_billing_first_name() . ' ' . $order->get_billing_last_name(),
                    $order->get_billing_email(),
                    $name,
                    $data['qty'],
                    implode(', ', array_unique($data['cats'])),
                    $order->get_total(),
                    $order->get_shipping_total(),
                    wc_get_order_status_name($order->get_status()),
                    $order->get_total_refunded() > 0 ? $order->get_total_refunded() : ''
                ];
                fputcsv($output, $row);
            }
        } else {
            // Múltiples productos diferentes: una fila por producto
            foreach ($items_grouped as $name => $data) {
                $row = [
                    $order->get_id(),
                    $order->get_date_created() ? $order->get_date_created()->format('Y-m-d') : '',
                    $order->get_billing_first_name() . ' ' . $order->get_billing_last_name(),
                    $order->get_billing_email(),
                    $name,
                    $data['qty'],
                    implode(', ', array_unique($data['cats'])),
                    $order->get_total(),
                    $order->get_shipping_total(),
                    wc_get_order_status_name($order->get_status()),
                    $order->get_total_refunded() > 0 ? $order->get_total_refunded() : ''
                ];
                fputcsv($output, $row);
            }
        }
    }

    fclose($output);
    exit;
}

// ============================
// Páginas en el menú de admin con botones
// ============================
add_action('admin_menu', function () {
    add_menu_page('Exportar Productos', 'Export Productos', 'manage_options', 'export-woo-csv', function () {
        echo '<div class="wrap"><h1>Exportar Productos</h1><a href="' . admin_url('admin-post.php?action=exportar_productos_woocommerce') . '" class="button button-primary">Descargar CSV</a></div>';
    });

    add_menu_page('Exportar PDVs', 'Exportar PDVs', 'manage_options', 'exportar-pdv', function () {
        echo '<div class="wrap"><h1>Exportar Puntos de Venta</h1><a href="' . admin_url('admin-post.php?action=exportar_pdv_custom_post') . '" class="button button-primary">Descargar CSV</a></div>';
    });

    add_menu_page('Exportar Ventas', 'Exportar Ventas', 'manage_woocommerce', 'exportar-ventas', function () {
        echo '<div class="wrap"><h1>Exportar Ventas</h1><a href="' . admin_url('admin-post.php?action=exportar_ventas_woocommerce_csv') . '" class="button button-primary">Descargar CSV</a></div>';
    });
});

// ============================
// Acciones admin-post
// ============================
add_action('admin_post_exportar_productos_woocommerce', 'export_woocommerce_products_full');
add_action('admin_post_exportar_pdv_custom_post', 'export_pdv_custom_post_csv');
add_action('admin_post_exportar_ventas_woocommerce_csv', 'exportar_ventas_woocommerce_csv');