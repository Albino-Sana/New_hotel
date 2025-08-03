
    @if (session('recibo_estadia_id'))
    <script>
        window.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                window.open("{{ route('recibo.estadia', session('recibo_estadia_id')) }}", '_blank');
            }, 1000);
        });
    </script>
    @endif