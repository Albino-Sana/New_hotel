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

<!-- DataTables -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/2.2.2/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.2.2/js/dataTables.bootstrap5.js"></script>


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

<!-- Bootstrap JS com Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@if (session('success'))
<script>
  Swal.fire({
    icon: 'success',
    title: 'Sucesso!',
    text: '{{ session('success') }}',
    timer: 3000,
    showConfirmButton: false
  });
</script>
@endif

@if (session('error'))
<script>
  Swal.fire({
    icon: 'error',
    title: 'Erro!',
    text: '{{ session('error') }}',
    timer: 4000,
    confirmButtonText: 'Compreendi',
    showConfirmButton: true

});
</script>
@endif

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


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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

<script>
  $(document).ready(function() {
    try {
        $('#Table').DataTable({
            "language": {
                "emptyTable": "Nenhum registro encontrado",
                "info": "Mostrando _START_ até _END_ de _TOTAL_ registros",
                "infoEmpty": "Mostrando 0 até 0 de 0 registros",
                "infoFiltered": "(Filtrados de _MAX_ registros totais)",
                "infoThousands": ".",
                "lengthMenu": "Exibir _MENU_ registros por página",
                "loadingRecords": "Carregando...",
                "processing": "Processando...",
                "zeroRecords": "Nenhum registro correspondente encontrado",
                "search": "Pesquisar:",
                "paginate": {
                    "next": "Próximo",
                    "previous": "Anterior",
                    "first": "Primeiro",
                    "last": "Último"
                },
                "aria": {
                    "sortAscending": ": Ordem crescente",
                    "sortDescending": ": Ordem decrescente"
                },
                "select": {
                    "rows": {
                        "_": "%d linhas selecionadas",
                        "0": "Nenhuma linha selecionada",
                        "1": "1 linha selecionada"
                    }
                }
            },
            "columnDefs": [
                { 
                    "targets": '_all', 
                    "defaultContent": "-" // Valor padrão para células vazias
                }
            ],
            "initComplete": function(settings, json) {
                if (!json || json.data.length === 0) {
                    console.log('Tabela vazia - sem dados para exibir');
                }
            },
            "error": function(settings, techNote, message) {
                console.error('Erro no DataTables:', message);
                $('.dataTables_wrapper').find('.dataTables_error').hide();
            }
        });
    } catch (e) {
        console.error('Erro ao inicializar DataTables:', e);
    }
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






