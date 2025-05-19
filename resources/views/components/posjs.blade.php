    <!-- Bootstrap JS Bundle with Popper -->

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
