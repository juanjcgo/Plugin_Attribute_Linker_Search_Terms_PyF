(function ($) {

    // Espera a que el documento esté listo
    $(document).ready(function () {

        /****************************** Preloader function *****************************/
        function preloaderActions(origenPreload, origenOpacity, status) {
            if (status) {
                $(origenPreload).append(`<div class="custom-loader"></div>`);
                $(origenOpacity).css('opacity', '0.9');
            } else {
                $(".custom-loader").remove();
                $(origenOpacity).css("opacity", "initial");
            }
        }

        var termsProducts = {};
        var totalProducts = 0;

        // Escucha cambios en el input de archivo
        $('#alst_select_xlsx').on('change', function (e) {

            // Contador de productos
            totalProducts = 0;
            termsProducts = {};

            // Obtiene el archivo seleccionado
            var file = e.target.files[0];

            // Obtiene el nombre del archivo
            var fileName = e.target.files[0].name;

            // Obtiene la extensión del archivo
            var fileExtension = fileName.split('.').pop().toLowerCase();

            if (fileExtension != 'xlsx') {
                $('#alst_execute').attr('disabled', true);
                alert("El archivo seleccionado no es un archivo Excel XLSX.");
            } else {
                // Crea un lector de archivos
                var reader = new FileReader();

                // Cuando la lectura del archivo esté completa
                reader.onload = function (e) {
                    // Obtiene el contenido del archivo
                    var data = e.target.result;

                    // Convierte el contenido a un objeto JSON utilizando xlsx
                    var workbook = XLSX.read(data, { type: 'binary' });

                    // Obtiene la primera hoja del libro
                    var sheet = workbook.Sheets[workbook.SheetNames[0]];

                    // Convierte los datos a formato JSON
                    var jsonData = XLSX.utils.sheet_to_json(sheet);

                    // SKU producto principal 
                    var skuSuperior = '';

                    // Recorre cada row en jsonData
                    jsonData.forEach(function (row) {
                        if (row.SKU) {
                            skuSuperior = row.SKU.toString();
                            termsProducts[skuSuperior] = [];
                            totalProducts++;
                        }

                        if (skuSuperior && skuSuperior.length > 0) {
                            termsProducts[skuSuperior].push(row.MARCA + ' > ' + row.MODELO + ' > ' + row.CILINDRAJE + ' > ' + row.FABRICACION);
                        } else {
                            alert('El formato del archivo no es correcto');
                        }
                    });

                    $('#alstProgressBarChild').removeClass('bg-success');
                    $('#alstProgressBarChild').addClass('progress-bar-striped');
                    $('#alstProgressBarChild').attr('style', 'width: 0%');
                    $('.alst_alert').text(totalProducts + ' productos encontrados');
                };

                // Lee el contenido del archivo como binario
                reader.readAsBinaryString(file);
                $('#alst_execute').attr('disabled', false);
            }

        });

        /********************** Linker atributes with terms search *********************/
        $('#alst_execute').click(function () {
            preloaderActions('#alst_preloader', '.alst_card', true);

            var linker = new ServicesLinker(alst.ajax_url);

            // Convertir el objeto termsProducts a un array para usar reduce
            var termsArray = Object.keys(termsProducts).map(function (key) {
                return { sku: key, terms: termsProducts[key] };
            });

            // Obtener el elemento de la barra de progreso
            var progressBar = $('#alstProgressBar');
            var progressBarChild = $('#alstProgressBarChild');

            // Establecer el valor máximo de la barra de progreso al número total de registros
            progressBar.attr('aria-valuemax', termsArray.length);

            termsArray.reduce(function (promise, item, index) {
                return promise.then(function () {
                    return new Promise(function (resolve, reject) {
                        linker.linkerAttributeToSearchTerms(
                            item.sku,
                            item.terms,
                            function (response) {
                                console.log(response);
                                if (response.res == 'error') {
                                    console.log(response.msg);
                                } else if (response.res) {
                                    progressBarChild.attr('style', 'width: ' + ((index + 1) / termsArray.length) * 100 + '%');
                                    progressBarChild.text(index + 1 + ' de ' + termsArray.length);
                                    if (index + 1 == termsArray.length) {
                                        progressBarChild.text('Finalizado');
                                        progressBarChild.removeClass('progress-bar-striped');
                                        progressBarChild.addClass('bg-success');
                                        preloaderActions('#alst_preloader', '.alst_card', false);
                                    }
                                    resolve();
                                } else {
                                    if (confirm(response.msg + "¿Quieres continuar con la importación de los demas productos?")) {
                                        progressBarChild.attr('style', 'width: ' + ((index + 1) / termsArray.length) * 100 + '%');
                                        progressBarChild.text(index + 1 + ' de ' + termsArray.length);
                                        if (index + 1 == termsArray.length) {
                                            progressBarChild.text('Finalizado');
                                            progressBarChild.removeClass('progress-bar-striped');
                                            progressBarChild.addClass('bg-success');
                                            preloaderActions('#alst_preloader', '.alst_card', false);
                                        }
                                        resolve();
                                    } else {
                                        alert("Solo se importaron " + (index + 1) + " productos, por favor revisa el producto " + item.sku + " y vuelve a intentarlo.");
                                        reject();
                                    }
                                }
                            },
                            function (error) {
                                preloaderActions('#alst_preloader', '.alst_card', false);
                                console.log(error);
                                reject();
                            }
                        );
                    });
                });
            }, Promise.resolve()); // Iniciar con una promesa resuelta para que el primer item se procese inmediatamente
        });

    });

})(jQuery);