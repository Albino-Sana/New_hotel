
@if (session('fatura_id') && session('origem_fatura') === 'reserva')
<script>
    window.addEventListener('DOMContentLoaded', function() {
        setTimeout(function() {
            window.open("{{ route('reservas.fatura', session('fatura_id')) }}", '_blank');
        }, 1000);
    });
</script>
@endif