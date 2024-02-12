<?php
class ALST_LINKER_SEARCH_TERMS
{

    public function get_or_create_term_id_by_name($term_name, $taxonomy, $parent_term_id)
    {
        try {
            $terms = get_terms(array(
                'taxonomy' => $taxonomy,
                'hide_empty' => false,
                'name' => $term_name,
                'parent' => $parent_term_id
            ));

            if (!empty($terms) && !is_wp_error($terms)) {
                foreach ($terms as $term) {
                    if ($term->parent == $parent_term_id) {
                        return [
                            'res' => true,
                            'data' => $term->term_id
                        ];
                    }
                }
            } else {
                // El término no existe, así que lo creamos.
                $args = array(
                    'name' => $term_name,
                    'taxonomy' => $taxonomy,
                    'parent' => $parent_term_id,
                );
                $new_term = wp_insert_term($term_name, $taxonomy, $args);

                if (!is_wp_error($new_term) && isset($new_term['term_id'])) {
                    return [
                        'res' => true,
                        'data' => $new_term['term_id']
                    ];
                }
            }
            return [
                'res' => false,
                'msg' => 'No se pudo crear el término: ' . $term_name
            ];
        } catch (Exception $e) {
            return [
                'res' => 'error',
                'msg' => 'Excepción capturada: ',  $e->getMessage()
            ];
        }
    }

    public function find_hierarchical_term($term_path, $taxonomy, $product_id)
    {
        try {
            $terms_to_assign = array_map('trim', explode(' > ', $term_path));
            $parent_id = 0;

            foreach ($terms_to_assign as $term_name) {
                $term_id = $this->get_or_create_term_id_by_name($term_name, $taxonomy, $parent_id);

                if ($term_id['res']) {
                    if ($parent_id === 0 || term_is_ancestor_of($parent_id, $term_id['data'], $taxonomy)) {
                        wp_set_post_terms($product_id, array($term_id['data']), $taxonomy, true);
                        $parent_id = $term_id['data'];
                    }
                } else {
                    return [
                        'res' => $term_id['res'],
                        'msg' => $term_id['msg']
                    ];
                }
            }
            return [
                'res' => true,
                'msg' => 'Términos de búsqueda relacionados exitosamente!'
            ];
        } catch (Exception $e) {
            return [
                'res' => 'error',
                'msg' => 'Excepción capturada: ',  $e->getMessage()
            ];
        }
    }


    /* public function attribute_linker_search_terms()
    {
        try {
            require_once('wp-load.php');
            $generation = '';

            $args = array(
                'post_type' => 'product',
                'post_status' => 'publish',
                'posts_per_page' => -1
            );

            $products = new WP_Query($args);

            if ($products->have_posts()) {
                while ($products->have_posts()) {
                    $products->the_post();

                    $product = wc_get_product(get_the_ID());

                    if ($product->is_type('variable')) {
                        $variations = $product->get_available_variations();

                        if (!empty($variations)) {
                            foreach ($variations as $variation) {
                                $attributes = $variation['attributes'];

                                foreach ($attributes as $attribute_name => $attribute_value) {
                                    if (!empty($attribute_name)) {
                                        $generation .= ucwords($attribute_value) . ' > ';
                                    }
                                }
                                $generation = substr($generation, 0, -3);
                                $generation .= ', ';
                            }
                        }
                    } elseif ($product->is_type('simple')) {
                        $attributes = $product->get_attributes();
                        if (!empty($attributes)) {
                            foreach ($attributes as $attribute_name => $attribute) {
                                $value = $product->get_attribute($attribute_name);

                                if (!empty($value)) {
                                    $generation .= ucwords($value) . ' > ';
                                }
                            }
                            $generation = substr($generation, 0, -3);
                            $generation .= ', ';
                        }
                    }

                    if (!empty($generation)) {
                        $generations = explode(',', $generation);
                        $terms = array_map('trim', $generations);
                        $taxonomy = 'product_filter_data';
                        $product_id = get_the_ID();

                        foreach ($terms as $term_path) {
                            if (!empty($term_path)) {
                                $this->find_hierarchical_term($term_path, $taxonomy, $product_id);
                            }
                        }
                        $generation = '';
                    }
                }
            }
            wp_reset_postdata();

            return [
                'res' => true,
                'msg' => 'Términos de búsqueda relacionados exitosamente!  '
            ];
        } catch (Exception $e) {
            return [
                'res' => 'error',
                'msg' => 'Excepción capturada: ',  $e->getMessage()
            ];
        }
    } */
}
