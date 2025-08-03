@if (session('fatura_id') && session('origem_fatura') === 'hospede')
<script>
    window.addEventListener('DOMContentLoaded', function() {
        setTimeout(function() {
            window.open("{{ route('hospedes.fatura', session('fatura_id')) }}", '_blank');
        }, 1000);
    });
</script>
@endif