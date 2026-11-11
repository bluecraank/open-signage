{{-- Two-list picker for devices and groups --}}
<script src="/data/js/jquery-3.7.1.min.js"></script>
<script src="/data/js/jquery.multi-select.js"></script>
<link rel="stylesheet" href="/data/css/multi-select.css">
<script>
    $(document).ready(function() {
        $('.multiselect').multiSelect({
            'selectableHeader': '<div class="multiselect-heading">{{ __('Selectable') }}</div>',
            'selectionHeader': '<div class="multiselect-heading">{{ __('Selected') }}</div>'
        });
    });
</script>
