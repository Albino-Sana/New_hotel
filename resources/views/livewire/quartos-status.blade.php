<div wire:poll.30s="loadQuartos" class="row g-3">
    @foreach ($quartos as $quarto)
        <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-2">
            <div class="card card-hover h-100 shadow-sm rounded-lg overflow-hidden"
                {{ $quarto['status'] === 'Disponível' ? 'data-bs-toggle="modal" data-bs-target="#checkinModal' . $quarto['id'] . '" style="cursor: pointer;"' : '' }}>
                
                <!-- Cabeçalho do Card -->
                <div class="card-header p-3 bg-gradient-{{ $quarto['status'] === 'Disponível' ? 'success' : ($quarto['status'] === 'Reservado' ? 'warning' : 'danger') }} text-dark">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 text-dark fw-bold">Quarto #{{ $quarto['numero'] }}</h6>
                        <span class="badge bg-white text-dark rounded-pill">{{ $quarto['andar'] }}º Andar</span>
                    </div>
                </div>
                
                <!-- Corpo do Card -->
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-sm text-muted">{{ $quarto['tipo'] }}</span>
                        <span class="badge bg-{{ $quarto['status'] === 'Disponível' ? 'success' : ($quarto['status'] === 'Reservado' ? 'warning' : 'danger') }} text-white rounded-pill">{{ $quarto['status'] }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-end mt-3">
                        <div>
                            <h5 class="mb-0 fw-bold text-primary">{{ $quarto['preco_noite'] }} Kz</h5>
                            <small class="text-muted">por noite</small>
                        </div>
                        <i class="fas fa-bed fa-2x text-secondary opacity-25"></i>
                    </div>
                </div>
                
                <!-- Rodapé do Card -->
                <div class="card-footer p-3 bg-light">
                    @if (strtolower($quarto['status']) === 'Ocupado')
                        <div class="d-grid gap-2">
                            <button class="btn btn-sm btn-outline-primary rounded-pill"
                                    data-bs-toggle="modal"
                                    data-bs-target="#consumoModal{{ $quarto['id'] }}">
                                <i class="fas fa-plus-circle me-1"></i> Adicionar Serviço
                            </button>
                            @if ($quarto['checkin'])
                                <button class="btn btn-sm btn-danger rounded-pill"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalCheckoutReserva-{{ $quarto['checkin']['id'] }}">
                                    <i class="fas fa-door-open me-1"></i> Fazer Check-out
                                </button>
                            @elseif ($quarto['hospede'])
                                <button class="btn btn-sm btn-danger rounded-pill"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalCheckoutHospede-{{ $quarto['hospede']['id'] }}">
                                    <i class="fas fa-door-open me-1"></i> Fazer Check-out
                                </button>
                            @else
                                <span class="text-muted d-block text-center">Sem dados de ocupação</span>
                            @endif
                        </div>
                    @elseif (strtolower($quarto['status']) === 'Disponível')
                        <div class="d-grid">
                            <span class="badge bg-primary bg-gradient text-white px-3 py-2 rounded-pill"
                                data-bs-toggle="modal"
                                data-bs-target="#modalNovoHospede"
                                data-quarto-id="{{ $quarto['id'] }}"
                                data-numero="{{ $quarto['numero'] }}"
                                data-andar="{{ $quarto['andar'] }}"
                                style="cursor: pointer;">
                                <i class="fas fa-user-plus me-2"></i> Novo Hóspede
                            </span>
                        </div>
                    @else
                        <div class="d-grid">
                            <span class="badge bg-warning bg-gradient text-dark px-3 py-2 rounded-pill">
                                <i class="fas fa-calendar-check me-1"></i> Reservado
                            </span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endforeach
</div>

   <script>
       window.addEventListener('mostrar-notificacao', event => {
           const mensagem = event.detail.mensagem;
           const notificacao = `
               <div class="alert alert-success alert-dismissible fade show fixed-top mx-auto mt-3" role="alert" style="max-width: 500px;">
                   <i class="fas fa-bell me-2"></i> ${mensagem}
                   <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
               </div>
           `;
           document.body.insertAdjacentHTML('afterbegin', notificacao);
           setTimeout(() => $('.alert').alert('close'), 5000);
       });
   </script>