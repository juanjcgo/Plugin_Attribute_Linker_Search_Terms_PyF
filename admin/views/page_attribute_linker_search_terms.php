<?php
wp_enqueue_style("alst_admin_bootstrap_css");
wp_enqueue_style("alst_admin_bootstrap_icon_css");
wp_enqueue_style("alst_page_attribute_linker_css");
wp_enqueue_style("alst_preloader_css");

wp_enqueue_script("alst_admin_jquey_js");
wp_enqueue_script("alst_xlsx_js");
wp_enqueue_script("alst_attribute_linker_js");
wp_enqueue_script("alst_service_attribute_linker_js");
?>

<div class="alst_container">
    <h5 class="mt-5">Relacionar términos de busqueda con productos</h5>

    <div class="card mb-3 alst_card">
        <div class="alst_alert" role="alert"></div><br>
        <div class="progress" id="alstProgressBar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="">
            <div class="progress-bar progress-bar-striped progress-bar-animated" id="alstProgressBarChild"></div>
        </div>

        <div class="row g-0">
            <div class="col-md-4 alst_box_img">
                <img src="<?php echo esc_url(plugins_url() . '/attribute-linker-search-terms/admin/views/assets/img/linker.png'); ?>" class="card-img-top" alt="Relacionar">
                <div id="alst_preloader"></div>
            </div>
            <div class="col-md-8">
                <div class="card-body">
                    <h6 class="card-title">Recomendación</h6>
                    <p class="card-text">
                        Para importar los términos de busqueda con los productos, debes seleccionar el archivo Excel XLSX que contiene los datos.
                        <a href="<?php echo esc_url(plugins_url() . '/attribute-linker-search-terms/admin/views/assets/files/Importar_terminos_de_busqueda.xlsx'); ?>">
                            Descarga el formato aquí
                        </a>
                    </p>
                    <input type="file" id="alst_select_xlsx" class="form-control" placeholder="Seleccionar Excel">
                    <br>
                    <button type="button" class="btn btn-outline-success" id="alst_execute" disabled>Importar y relacionar</button>
                </div>
            </div>
        </div>
    </div>
</div>