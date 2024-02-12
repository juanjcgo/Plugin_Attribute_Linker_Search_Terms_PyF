<?php
add_action("wp_ajax_alst_attribute_linker_search_terms_ajax", "alst_attribute_linker_search_terms_ajax");
function alst_attribute_linker_search_terms_ajax()
{
    try {
        require_once ALST_PATH . "models/model_linker_search_terms.php";
        if (!class_exists('ALST_LINKER_SEARCH_TERMS')) {
            wp_send_json([
                'res' => false,
                'msg' => 'Hubo un error interno, por favor notificar al administrador',
            ]);
        }
        $class_linker = new ALST_LINKER_SEARCH_TERMS();
        $sku = $_POST['sku'];
        $terms = $_POST['terms'];
        $taxonomy = 'product_filter_data';
        $product_id = '';

        if ($terms == null || empty($terms)) {
            wp_send_json([
                'res' => false,
                'msg' => 'No se recibieron los datos necesarios para realizar la importación',
            ]);
        }

        $exists_product_id = wc_get_product_id_by_sku($sku);
        if ($exists_product_id) {
            $product_id = $exists_product_id;
            // Eliminamos los términos de busqueda que tenga el producto
            wp_set_object_terms($product_id, array(), $taxonomy);
        } else {
            wp_send_json([
                'res' => false,
                'msg' => 'No se encontró ningún producto con ese SKU: ' . $sku,
            ]);
        }

        foreach ($terms as $index => $term_path) {
            $res = $class_linker->find_hierarchical_term($term_path, $taxonomy, $product_id);
            if ($res['res'] === 'error' || $res['res'] === false) {
                wp_send_json($res);
            }
        }

        wp_send_json([
            'res' => true,
            'msg' => 'Se importaron los términos de busqueda con los productos correctamente',
        ]);
    } catch (Exception $e) {
        wp_send_json([
            'res' => 'error',
            'msg' => 'Excepción capturada: ',  $e->getMessage()
        ]);
    }
}
