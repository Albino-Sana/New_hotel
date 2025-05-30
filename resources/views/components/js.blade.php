<script src="../assets/js/core/popper.min.js"></script>
<script src="../assets/js/core/bootstrap.min.js"></script>
<script src="../assets/js/plugins/perfect-scrollbar.min.js"></script>
<script src="../assets/js/plugins/smooth-scrollbar.min.js"></script>
<script src="../assets/js/plugins/chartjs.min.js"></script>

<!-- jQuery (obrigatório para Select2) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

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

<!-- Github buttons -->
<script async defer src="https://buttons.github.io/buttons.js"></script>
<!-- Control Center for Soft Dashboard: parallax effects, scripts for the example pages etc -->
<script src="../assets/js/argon-dashboard.min.js?v=2.1.0"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Sucesso!',
                text: '{{ session('success') }}',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Compreendi'
            });
        @endif

        @if (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Erro!',
                text: '{{ session('error') }}',
                confirmButtonColor: '#d33',
                confirmButtonText: 'Compreendi'
            });
        @endif

        @if (session('warning'))
            Swal.fire({
                icon: 'warning',
                title: 'Atenção!',
                text: '{{ session('warning') }}',
                confirmButtonColor: '#f0ad4e',
                confirmButtonText: 'Compreendi'
            });
        @endif


        @if ($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Erro!',
                text: '@foreach ($errors->all() as $error){{ $error }}@if (!$loop->last), @endif @endforeach',
                confirmButtonColor: '#d33',
                confirmButtonText: 'Compreendi'
            });
        @endif
    });
</script>

<script>
document.querySelectorAll('.form-checkin').forEach(form => {
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

<script>
// Ativa todos os tooltips na página
document.addEventListener('DOMContentLoaded', function() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl, {
            trigger: 'hover focus'
        });
    });
});
</script>
<script>
    $(document).ready(function() {
        // Desativa todos os avisos do DataTables
        $.fn.dataTable.ext.errMode = 'none';

        $('.table').DataTable({
            select: true,
            responsive: true,
            ordering: false,
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/pt-BR.json'
            },
            lengthMenu: [
                [5, 10, 25, -1],
                [5, 10, 25, "Todos"]
            ],
        });
    });
</script>

<!-- Confirmações de ações consolidadas -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Confirmação para exclusão, cancelamento e eliminação permanente
    const actionButtons = {
        '.btn-delete': {
            title: 'Tem certeza?',
            text: 'Esta ação não poderá ser desfeita!',
            confirmText: 'Sim, excluir!',
            confirmColor: '#d33',
            cancelColor: '#6c757d'
        },
        '.btn-delete-permanent': {
            title: 'Eliminar permanentemente?',
            text: 'Esta ação não pode ser desfeita e removerá todos os dados da reserva!',
            confirmText: 'Sim, eliminar!',
            confirmColor: '#d33',
            cancelColor: '#3085d6'
        }
    };

    Object.keys(actionButtons).forEach(selector => {
        document.querySelectorAll(selector).forEach(button => {
            button.addEventListener('click', function (e) {
                e.preventDefault();

                const { title, text, confirmText, confirmColor, cancelColor } = actionButtons[selector];
                Swal.fire({
                    title: title,
                    text: text,
                    icon: selector === '.btn-delete-permanent' ? 'error' : 'warning',
                    showCancelButton: true,
                    confirmButtonColor: confirmColor,
                    cancelButtonColor: cancelColor,
                    confirmButtonText: confirmText,
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        const form = button.closest('form');
                        if (form) {
                            form.submit();
                        }
                    }
                });
            });
        });
    });
});
</script>