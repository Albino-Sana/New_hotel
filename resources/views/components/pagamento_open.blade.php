 
@if (session('fatura_id') && session('origem_fatura') === 'pagamento')
<script>
    window.addEventListener('DOMContentLoaded', function() {
        setTimeout(function() {
            window.open("{{ route('pagamentos.fatura.pdf', session('fatura_id')) }}", '_blank');
        }, 1000);
    });
</script>
@endif

