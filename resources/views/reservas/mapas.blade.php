<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
    <link rel="icon" type="image/png" href="../assets/img/favicon.png">
    <title>DAT Hotelaria - Mapa de Reservas</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @include('components.css')
    <style>
        :root {
            --primary-color: #3A416F;
            --secondary-color: #8392AB;
            --success-color: #4CAF50;
            --warning-color: #FF9800;
            --info-color: #00BCD4;
            --danger-color: #F44336;
            --light-bg: #F8F9FA;
            --border-radius: 12px;
            --box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            --transition: all 0.3s ease;
        }
        
        body {
            background-color: #f5f7fa;
            color: #344767;
            font-family: 'Inter', 'Segoe UI', Roboto, sans-serif;
        }
        
        .main-content {
            padding: 25px 1.5rem;
        }
        
        .mapa-container {
            background: #fff;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            padding: 1.5rem;
            margin-top: 1.5rem;
        }
        
        .mapa-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 0;
            flex-wrap: wrap;
            gap: 15px;
            margin-bottom: 1.5rem;
            border-bottom: 1px solid #edf2f7;
        }
        
        .status-badges {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }
        
        .badge {
            padding: 8px 12px;
            border-radius: 8px;
            font-weight: 500;
            font-size: 0.8rem;
            display: flex;
            align-items: center;
            gap: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }
        
        .badge i {
            font-size: 0.7rem;
        }
        
        .week-navigation {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .btn-nav {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid #D2D6DA;
            background: white;
            color: var(--primary-color);
            transition: var(--transition);
        }
        
        .btn-nav:hover {
            background: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
        }
        
        #weekRange {
            font-weight: 600;
            color: var(--primary-color);
            min-width: 220px;
            text-align: center;
        }
        
        .table-responsive {
            border-radius: var(--border-radius);
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.03);
            max-height: 70vh;
        }
        
        .table {
            margin-bottom: 0;
            border-collapse: separate;
            border-spacing: 0;
        }
        
        .table thead th {
            background: var(--primary-color);
            color: white;
            font-weight: 600;
            padding: 1rem 0.75rem;
            border: none;
            position: sticky;
            top: 0;
            z-index: 10;
        }
        
        .table th:first-child {
            position: sticky;
            left: 0;
            z-index: 11;
            background: var(--primary-color);
        }
        
        .table td:first-child {
            position: sticky;
            left: 0;
            background: white;
            z-index: 1;
            font-weight: 600;
            box-shadow: 2px 0 5px rgba(0,0,0,0.05);
        }
        
        .table td {
            padding: 0.9rem 0.75rem;
            vertical-align: middle;
            border: 1px solid #edf2f7;
            transition: var(--transition);
            height: 70px;
            max-width: 180px;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        .table tbody tr {
            transition: var(--transition);
        }
        
        .table tbody tr:hover {
            background-color: rgba(58, 65, 111, 0.02);
        }
        
        .table tbody tr:hover td:first-child {
            background-color: #f8f9fa;
        }
        
        .reserva-cell {
            border-radius: 6px;
            padding: 8px;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            font-size: 0.85rem;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
            transition: var(--transition);
            cursor: pointer;
        }
        
        .reserva-cell:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        
        .cliente-nome {
            font-weight: 600;
            margin-bottom: 3px;
        }
        
        .reserva-detalhes {
            font-size: 0.75rem;
            opacity: 0.9;
        }
        
        .bg-primary { background: linear-gradient(135deg, #3A416F 0%, #2A2F4F 100%); }
        .bg-warning { background: linear-gradient(135deg, #FF9800 0%, #F57C00 100%); color: white !important; }
        .bg-success { background: linear-gradient(135deg, #4CAF50 0%, #388E3C 100%); }
        .bg-info { background: linear-gradient(135deg, #00BCD4 0%, #0097A7 100%); color: white !important; }
        .bg-secondary { background: linear-gradient(135deg, #6c757d 0%, #495057 100%); }
        .bg-dark { background: linear-gradient(135deg, #343a40 0%, #212529 100%); }
        
        /* Loading animation for cells */
        @keyframes pulse {
            0% { opacity: 0.6; }
            50% { opacity: 1; }
            100% { opacity: 0.6; }
        }
        
        .loading {
            animation: pulse 1.5s infinite;
            background: #f0f0f0;
        }
        
        /* Responsividade */
        @media (max-width: 768px) {
            .mapa-header {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .week-navigation {
                width: 100%;
                justify-content: space-between;
                margin-top: 10px;
            }
            
            .table-responsive {
                overflow-x: auto;
            }
            
            .table td {
                min-width: 150px;
            }
            
            .table td:first-child {
                position: sticky;
                left: 0;
                z-index: 1;
            }
        }
        
        /* Tooltip para informações completas */
        [data-tooltip] {
            position: relative;
        }
        
        [data-tooltip]:hover::after {
            content: attr(data-tooltip);
            position: absolute;
            bottom: 100%;
            left: 50%;
            transform: translateX(-50%);
            padding: 8px 12px;
            background: #333;
            color: white;
            border-radius: 4px;
            font-size: 0.8rem;
            white-space: nowrap;
            z-index: 100;
            margin-bottom: 5px;
        }
        
        /* Filtros adicionais */
        .filtros-rapidos {
            display: flex;
            gap: 10px;
            margin-bottom: 1rem;
            flex-wrap: wrap;
        }
        
        .filtro-btn {
            padding: 6px 12px;
            border-radius: 20px;
            border: 1px solid #D2D6DA;
            background: white;
            font-size: 0.85rem;
            transition: var(--transition);
        }
        
        .filtro-btn:hover, .filtro-btn.active {
            background: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
        }
        
        /* Modal para detalhes da reserva */
        .modal-content {
            border-radius: var(--border-radius);
            border: none;
            box-shadow: var(--box-shadow);
        }
    </style>
</head>
<body class="g-sidenav-show bg-gray-100">
    @include('layouts.sidebar')
    <main class="main-content position-relative border-radius-lg">
        @php $titulo = 'Mapa de Reservas'; @endphp
        @include('layouts.navbar', ['titulo' => $titulo])

        <div class="container-fluid">
            <div class="mapa-container">
                <!-- Header -->
                <div class="mapa-header">
                    <div class="status-badges">
                        <span class="badge bg-primary"><i class="fas fa-sign-in-alt"></i> Check-in</span>
                        <span class="badge bg-warning"><i class="fas fa-sign-out-alt"></i> Checkout</span>
                        <span class="badge bg-success"><i class="fas fa-calendar-check"></i> Reservado</span>
                        <span class="badge bg-info"><i class="fas fa-user-times"></i> No-show</span>
                        <span class="badge bg-secondary"><i class="fas fa-lock"></i> Cancelado</span>
                        <span class="badge bg-dark"><i class="fas fa-tools"></i> Manutenção</span>
                    </div>
                    
                    <div class="week-navigation">
                        <button class="btn-nav" id="prevWeek">
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        <span id="weekRange" class="mx-2 fw-bold"></span>
                        <button class="btn-nav" id="nextWeek">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                        <button class="btn-nav" id="currentWeek">
                            <i class="fas fa-calendar-day"></i>
                        </button>
                    </div>
                </div>
                
                <!-- Filtros rápidos -->
                <div class="filtros-rapidos">
                    <button class="filtro-btn active" data-filter="all">Todos</button>
                    <button class="filtro-btn" data-filter="checkin">Check-in</button>
                    <button class="filtro-btn" data-filter="checkout">Checkout</button>
                    <button class="filtro-btn" data-filter="reservado">Reservado</button>
                </div>

                <!-- Tabela Mapa -->
                <div class="table-responsive">
                    <table class="table table-bordered text-center align-middle" id="mapaReservas">
                        <thead class="table-light text-dark">
                            <tr>
                                <th>Quarto</th>
                                <!-- Datas geradas via JS -->
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Linhas geradas via JS -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

    <!-- Modal para detalhes da reserva -->
    <div class="modal fade" id="reservaModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detalhes da Reserva</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="reservaDetalhes">
                    <!-- Conteúdo preenchido via JavaScript -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                    <button type="button" class="btn btn-primary">Editar Reserva</button>
                </div>
            </div>
        </div>
    </div>

    @include('layouts.customise')
    @include('components.js')

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const reservas = @json($reservas);
        const quartos = @json($quartos);
        const tabela = document.getElementById('mapaReservas');
        const thead = tabela.querySelector('thead tr');
        const tbody = tabela.querySelector('tbody');
        const weekRange = document.getElementById('weekRange');
        const reservaModal = new bootstrap.Modal(document.getElementById('reservaModal'));

        let currentStart = startOfWeek(new Date());

        // Função: início da semana (segunda-feira)
        function startOfWeek(date) {
            const d = new Date(date);
            const day = d.getDay();
            const diff = d.getDate() - day + (day === 0 ? -6 : 1);
            return new Date(d.setDate(diff));
        }

        // Função: formatar data
        function formatDate(date) {
            return date.toISOString().split('T')[0];
        }

        // Formatar data para exibição
        function formatDisplayDate(date) {
            return date.toLocaleDateString('pt-BR', { weekday: 'short', day: '2-digit', month: 'short' });
        }

        // Renderizar tabela
        function renderTable(startDate) {
            // Cabeçalho
            thead.innerHTML = '<th>Quarto</th>';
            const dates = [];
            for (let i = 0; i < 7; i++) {
                const d = new Date(startDate);
                d.setDate(startDate.getDate() + i);
                dates.push(formatDate(d));
                thead.innerHTML += `<th>${formatDisplayDate(d)}</th>`;
            }

            // Intervalo no título  
            const endDate = new Date(startDate);
            endDate.setDate(startDate.getDate() + 6);
            weekRange.textContent = `${startDate.toLocaleDateString('pt-BR')} - ${endDate.toLocaleDateString('pt-BR')}`;

            // Corpo
            tbody.innerHTML = '';
            quartos.forEach(quarto => {
                let row = `<tr><td>${quarto.nome}</td>`;
                dates.forEach(dataAtual => {
                    const reserva = reservas.find(r => 
                        r.quarto_id === quarto.id &&
                        dataAtual >= r.data_entrada &&
                        dataAtual <= r.data_saida
                    );

                    if (reserva) {
                        let corClasse = '';
                        switch (reserva.status) {
                            case 'reservado': corClasse = 'bg-success text-white'; break;
                            case 'checkin': corClasse = 'bg-primary text-white'; break;
                            case 'checkout': corClasse = 'bg-warning text-dark'; break;
                            case 'no-show': corClasse = 'bg-info text-dark'; break;
                            case 'bloqueio': corClasse = 'bg-secondary text-white'; break;
                            case 'manutencao': corClasse = 'bg-dark text-white'; break;
                            default: corClasse = 'bg-light';
                        }
                        
                        // Tooltip com informações completas
                        const tooltipText = `Cliente: ${reserva.cliente_nome}\\nCheck-in: ${reserva.data_entrada}\\nCheckout: ${reserva.data_saida}\\nStatus: ${reserva.status}`;
                        
                        row += `<td>
                            <div class="reserva-cell ${corClasse}" data-tooltip="${tooltipText}" 
                                 onclick="showReservaDetails(${JSON.stringify(reserva).replace(/"/g, '&quot;')})">
                                <div class="cliente-nome">${reserva.cliente_nome}</div>
                                <div class="reserva-detalhes">
                                    ${reserva.data_entrada} - ${reserva.data_saida}
                                </div>
                            </div>
                        </td>`;
                    } else {
                        row += `<td></td>`;
                    }
                });
                row += `</tr>`;
                tbody.innerHTML += row;
            });
        }

        // Botões de navegação
        document.getElementById('prevWeek').addEventListener('click', () => {
            currentStart.setDate(currentStart.getDate() - 7);
            renderTable(currentStart);
        });

        document.getElementById('nextWeek').addEventListener('click', () => {
            currentStart.setDate(currentStart.getDate() + 7);
            renderTable(currentStart);
        });
        
        document.getElementById('currentWeek').addEventListener('click', () => {
            currentStart = startOfWeek(new Date());
            renderTable(currentStart);
        });

        // Filtros rápidos
        document.querySelectorAll('.filtro-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.filtro-btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                
                // Implementar lógica de filtro aqui
                // Por enquanto apenas muda a aparência, pode ser expandido posteriormente
            });
        });

        // Primeira renderização
        renderTable(currentStart);
    });
    
    // Função para mostrar detalhes da reserva
    function showReservaDetails(reserva) {
        const modalBody = document.getElementById('reservaDetalhes');
        modalBody.innerHTML = `
            <div class="mb-3">
                <h6>Cliente</h6>
                <p>${reserva.cliente_nome}</p>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <h6>Check-in</h6>
                    <p>${reserva.data_entrada}</p>
                </div>
                <div class="col-md-6">
                    <h6>Checkout</h6>
                    <p>${reserva.data_saida}</p>
                </div>
            </div>
            <div class="mb-3">
                <h6>Status</h6>
                <span class="badge ${getStatusClass(reserva.status)}">${reserva.status}</span>
            </div>
            <div class="mb-3">
                <h6>Quarto</h6>
                <p>${reserva.quarto_id}</p>
            </div>
        `;
        
        reservaModal.show();
    }
    
    function getStatusClass(status) {
        switch(status) {
            case 'reservado': return 'bg-success';
            case 'checkin': return 'bg-primary';
            case 'checkout': return 'bg-warning text-dark';
            case 'no-show': return 'bg-info text-dark';
            case 'bloqueio': return 'bg-secondary';
            case 'manutencao': return 'bg-dark';
            default: return 'bg-light';
        }
    }
    </script>
</body>
</html>