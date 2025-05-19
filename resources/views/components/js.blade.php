<script src="../assets/js/core/popper.min.js"></script>
<script src="../assets/js/core/bootstrap.min.js"></script>
<script src="../assets/js/plugins/perfect-scrollbar.min.js"></script>
<script src="../assets/js/plugins/smooth-scrollbar.min.js"></script>
<script src="../assets/js/plugins/chartjs.min.js"></script>

<!-- jQuery (obrigatório para Select2) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<!-- Github buttons -->
<script async defer src="https://buttons.github.io/buttons.js"></script>

<!-- Control Center for Soft Dashboard: parallax effects, scripts for the example pages etc -->
<script src="../assets/js/argon-dashboard.min.js?v=2.1.0"></script>
<script src="../assets/js/core/popper.min.js"></script>
<script src="../assets/js/core/bootstrap.min.js"></script>
<script src="../assets/js/plugins/perfect-scrollbar.min.js"></script>
<script src="../assets/js/plugins/smooth-scrollbar.min.js"></script>
<script src="  https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="//unpkg.com/alpinejs" defer></script>


<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.3/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/2.3.0/js/dataTables.js"></script>
<!-- DataTables PT-BR -->
<script src="https://cdn.datatables.net/plug-ins/1.11.5/i18n/pt-BR.json"></script>


<script>
  var win = navigator.platform.indexOf('Win') > -1;
  if (win && document.querySelector('#sidenav-scrollbar')) {
    var options = {
      damping: '0.5'
    }
    Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
  }
</script>

<script>
  var win = navigator.platform.indexOf('Win') > -1;
  if (win && document.querySelector('#sidenav-scrollbar')) {
    var options = {
      damping: '0.5'
    }
    Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
  }
</script>
<!-- Github buttons -->
<script async defer src="https://buttons.github.io/buttons.js"></script>
<!-- Control Center for Soft Dashboard: parallax effects, scripts for the example pages etc -->
<script src="../assets/js/argon-dashboard.min.js?v=2.1.0"></script>


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<script>
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Sucesso!',
            text: '{{ session('success') }}',
            confirmButtonText: 'Compreendi',
            confirmButtonColor: '#3085d6',
        });
    @endif

    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Erro!',
            text: '{{ session('error') }}',
            confirmButtonText: 'Compreendi',
            confirmButtonColor: '#d33',
        });
    @endif

    @if($errors->any())
        Swal.fire({
            icon: 'warning',
            title: 'Atenção!',
            html: `{!! implode('<br>', $errors->all()) !!}`,
            confirmButtonText: 'Compreendi',
            confirmButtonColor: '#f39c12',
        });
    @endif
</script>



<script>
document.querySelectorAll('.form-checkin').forEach(form => {
    form.addEventListener('submit', function(e) {
        e.preventDefault(); // evita o envio imediato
    });

    form.querySelector('button').addEventListener('click', function() {
        Swal.fire({
            title: 'Fazer Check-in?',
            text: "Deseja realmente realizar o check-in desta reserva?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#198754',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sim, confirmar!',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit(); // envia o formulário
            }
        });
    });
});
</script>


 
<!--preeche o valor a pagar ao selecionar o quarto do hospede-->
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Função que atualiza o valor do input baseado na opção selecionada
        function atualizarValor(select, input) {
            const selectedOption = select.options[select.selectedIndex];
            const preco = selectedOption.getAttribute('data-valor');
            if (preco !== null) {
                input.value = preco;
            }
        }

        // Atualiza o valor no formulário de criação
        const novoSelect = document.querySelector('#novo_quarto');
        const novoValor = document.querySelector('#novo_valor');

        if (novoSelect && novoValor) {
            novoSelect.addEventListener('change', () => {
                atualizarValor(novoSelect, novoValor);
            });
        }

        // Atualiza o valor nos formulários de edição
        document.querySelectorAll('select[id^="editar_quarto_"]').forEach(select => {
            const id = select.id.split('_').pop();
            const inputValor = document.querySelector(`#editar_valor_${id}`);

            if (inputValor) {
                select.addEventListener('change', () => {
                    atualizarValor(select, inputValor);
                });
            }
        });
    });
</script>

<!-- Inicialização com dropdownParent -->
<script>
    $(document).ready(function () {
        $('#modalCheckout').on('shown.bs.modal', function () {
            $('.select2').select2({
                dropdownParent: $('#modalCheckout'),
                width: '100%',
                placeholder: "Selecione os serviços"
            });
        });
    });
</script>

@if (session('error'))
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Acesso Negado',
            text: '{{ session('error') }}',
            confirmButtonText: 'Compreendi'
        });
    </script>
@endif

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>


    <!-- JavaScript Libraries para todo conteudo do site -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/wow/wow.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/counterup/counterup.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="lib/tempusdominus/js/moment.min.js"></script>
    <script src="lib/tempusdominus/js/moment-timezone.min.js"></script>
    <script src="lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js"></script>
<!-- Scripts globais -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/pt.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        flatpickr('.datepicker', {
            dateFormat: 'd/m/Y', // Formato de exibição: 12/05/2025
            locale: 'pt', // Localização para português
            altInput: true, // Campo visível com formato d/m/Y
            altFormat: 'd/m/Y', // Formato visível
            allowInput: true, // Permite digitação manual
            onChange: function(selectedDates, dateStr, instance) {
                // Garante que o valor do input original seja Y-m-d para o form
                instance.element.value = selectedDates[0]
                    ? flatpickr.formatDate(selectedDates[0], 'Y-m-d')
                    : '';
            }
        });
    });
</script>
    <!-- Template Javascript -->
    <script src="js/main.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/2.2.2/js/dataTables.min.js"></script>
<script src="https://cdn.datatables.net/2.2.2/js/dataTables.bootstrap5.min.js"></script>



<!-- DataTables Inicialização -->
<script>
    var table = $('#Table').DataTable({
      responsive: true,
      language: {
      url: "https://cdn.datatables.net/plug-ins/1.13.5/i18n/pt-BR.json",
        paginate: {
                first:    '«',
                previous: '←',
                next:     '→',
                last:     '»'
            }
      }
     

    });
</script>