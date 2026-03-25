import './bootstrap';
import $ from 'jquery';
import 'select2';

window.$ = window.jQuery = $;

$(document).ready(function() {
    window.initSelect2 = function(selector = '.select2') {
        $(selector).select2({
            width: '100%',
            placeholder: 'Seleccionar...',
            allowClear: true,
            language: {
                noResults: function() {
                    return "No se encontraron resultados";
                }
            }
        });
    }

    initSelect2();

    // Auto-focus search field when opening Select2
    $(document).on('select2:open', () => {
        document.querySelector('.select2-search__field').focus();
    });

    // Re-init on modal open if needed
    $(document).on('modal-opened', function() {
        initSelect2();
    });
});
