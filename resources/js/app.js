import './bootstrap';

$(document).ready(function() {
    // Auto-focus search field when opening Select2
    $(document).on('select2:open', () => {
        const searchField = document.querySelector('.select2-search__field');
        if (searchField) {
            searchField.focus();
        }
    });

    // Re-init on modal open if needed
    $(document).on('modal-opened', function() {
        if (typeof window.initSelect2 === 'function') {
            window.initSelect2();
        }
    });
});
