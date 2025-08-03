    <!-- Bootstrap JS Bundle with Popper -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/2.2.2/js/dataTables.min.js"></script>
<script src="https://cdn.datatables.net/2.2.2/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Scripts simples para demonstração
        document.addEventListener('DOMContentLoaded', function() {
            // Ativar tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });
            
            // Atualizar data de entrada para hoje
            var today = new Date().toISOString().split('T')[0];
            document.getElementById('dataEntrada').value = today;
            
            // Calcular data de saída padrão (amanhã)
            var tomorrow = new Date();
            tomorrow.setDate(tomorrow.getDate() + 1);
            document.getElementById('dataSaida').value = tomorrow.toISOString().split('T')[0];
        });
    </script>
    <script>
        // Scripts simples para demonstração
        document.addEventListener('DOMContentLoaded', function() {
            // Ativar tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });
        });
    </script>
    <script>
document.addEventListener('DOMContentLoaded', function() {
    // Preenche os campos quando seleciona uma reserva
    document.getElementById('reserva_id').addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        
        if(selectedOption.value) {
            // Preenche os campos visíveis
            document.getElementById('cliente').value = selectedOption.dataset.cliente;
            document.getElementById('quarto_vis').value = 'Quarto ' + selectedOption.dataset.quarto;
            document.getElementById('preco').value = parseFloat(selectedOption.dataset.preco).toLocaleString('pt-BR', {
                style: 'currency',
                currency: 'AOA'
            });
            document.getElementById('pessoas').value = selectedOption.dataset.pessoas;
            
            // Formata as datas
            const entrada = new Date(selectedOption.dataset.entrada);
            const saida = new Date(selectedOption.dataset.saida);
            
            document.getElementById('entrada_vis').value = entrada.toLocaleString('pt-BR');
            document.getElementById('saida_vis').value = saida.toLocaleString('pt-BR');
            
            // Preenche os campos ocultos
            document.getElementById('quarto').value = selectedOption.dataset.quarto;
            document.getElementById('entrada').value = selectedOption.dataset.entrada;
            document.getElementById('saida').value = selectedOption.dataset.saida;
            document.getElementById('num_pessoas').value = selectedOption.dataset.pessoas;
        }
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
    $(document).ready(function() {
        // Desativa todos os avisos do DataTables
        $.fn.dataTable.ext.errMode = 'none';

        $('.table').DataTable({
            select: true,
            responsive: true,
            ordering: false,
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/pt-BR.json',
                paginate: {
                    first:    '«',
                    previous: '←',
                    next:     '→',
                    last:     '»'
                }
            },
            pagingType: "simple_numbers", // ou "full_numbers" para mostrar todos os números
            lengthMenu: [
                [5, 10, 25, -1],
                [5, 10, 25, "Todos"]
            ],
        });
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
    function atualizarRelogio() {
        const agora = new Date();
        const dia = String(agora.getDate()).padStart(2, '0');
        const mes = String(agora.getMonth() + 1).padStart(2, '0'); // Mês começa em 0
        const ano = agora.getFullYear();
        const horas = String(agora.getHours()).padStart(2, '0');
        const minutos = String(agora.getMinutes()).padStart(2, '0');
        const segundos = String(agora.getSeconds()).padStart(2, '0');
        const dataHora = `${dia}/${mes}/${ano} ${horas}:${minutos}:${segundos}`;
        document.getElementById('relogio').textContent = dataHora;
    }

    // Atualizar imediatamente
    atualizarRelogio();
    // Atualizar a cada segundo
    setInterval(atualizarRelogio, 1000);
});
</script>

<script>
     // Verificar o checkbox gerar_pdf
                    if (data.gerar_pdf) {
                        // Abrir PDF em nova janela
                        window.open(data.pdf_url, '_blank');
                    } else {
                        // Exibir SweetAlert para perguntar sobre impressão
                        Swal.fire({
                            icon: 'question',
                            title: 'Imprimir Fatura?',
                            text: 'Deseja imprimir a fatura da reserva?',
                            showCancelButton: true,
                            confirmButtonText: 'Sim',
                            cancelButtonText: 'Não',
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.open(data.pdf_url, '_blank');
                            }
                        });
                    }
</script>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    $('#historicoModal').on('show.bs.modal', function () {
        carregarHistorico();
    });

    // Atualizar histórico ao mudar filtros
    $('#filtroDataInicio, #filtroDataFim, #filtroQuarto, #filtroStatus').on('change', function () {
        carregarHistorico();
    });

    function carregarHistorico() {
        const dataInicio = $('#filtroDataInicio').val();
        const dataFim = $('#filtroDataFim').val();
        const quartoId = $('#filtroQuarto').val();
        const status = $('#filtroStatus').val();

        $.ajax({
            url: '{{ route('historico.index') }}',
            method: 'GET',
            data: {
                data_inicio: dataInicio,
                data_fim: dataFim,
                quarto_id: quartoId,
                status: status
            },
            success: function (data) {
                const tbody = $('#historicoTabela');
                tbody.empty();

                if (data.length === 0) {
                    tbody.append('<tr><td colspan="9" class="text-center">Nenhum registro encontrado.</td></tr>');
                    return;
                }

                data.forEach(item => {
                    // Definir ícones de ação com tooltips
                    let acoes = `
                   
                    `;
         if (item.tipo === 'Reserva') {
    acoes += `
        <a href="/reservas/${item.id}/fatura" target="_blank" class="btn btn-sm btn-success" data-bs-toggle="tooltip" title="Ver Recibo">
            <i class="bi bi-printer"></i>
        </a>
    `;
} else if (item.tipo === 'Check-in' || item.tipo === 'Hóspede Direto') {
    acoes += `
        <a href="${item.tipo === 'Hóspede Direto' ? '/hospedes/fatura/' + item.id : '/pagamentos/checkin/' + item.id + '/fatura'}"
            target="_blank" class="btn btn-sm btn-info" data-bs-toggle="tooltip" title="Ver Fatura">
            <i class="bi bi-printer"></i>
        </a>
    `;
}


                    tbody.append(`
                        <tr>
                            <td>${item.tipo}</td>
                            <td>${item.id}</td>
                            <td>${item.cliente}</td>
                            <td>${item.quarto}</td>
                            <td>${item.data_entrada}</td>
                            <td>${item.data_saida}</td>
                            <td>${item.valor_total}</td>
                            <td>${item.status}</td>
                            <td>${acoes}</td>
                        </tr>
                    `);
                });

                // Inicializar tooltips após carregar a tabela
                const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
                const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));
            },
            error: function () {
                alert('Erro ao carregar o histórico. Tente novamente.');
            }
        });
    }

    window.enviarEmail = function (id, tipo) {
        // Lógica para enviar e-mail (ex.: abrir modal ou redirecionar para rota de envio)
        alert(`Enviar e-mail para ${tipo} ID ${id}`);
    };

window.verFatura = function (id, tipo) {
    let url = '';

    if (tipo === 'Hóspede Direto') {
        url = `/hospedes/fatura/${id}`;
    } else if (tipo === 'Check-in') {
        url = `/pagamentos/checkin/${id}/fatura`;
    }

    if (url) {
        window.open(url, '_blank');
    } else {
        alert('Tipo não suportado para fatura.');
    }
};

window.verRecibo = function (id) {
    const url = `/reservas/${id}/fatura`;
    window.open(url, '_blank');
};


});
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    atualizarContadorReservas();

    // Opcional: atualizar a cada 60 segundos
    setInterval(atualizarContadorReservas, 60000);
});

function atualizarContadorReservas() {
    $.ajax({
        url: '{{ route('contador.reservas') }}',
        method: 'GET',
        success: function (data) {
            $('#contadorReservas').text(data.count);
        },
        error: function () {
            console.error('Erro ao carregar contador de reservas.');
        }
    });
}
</script>