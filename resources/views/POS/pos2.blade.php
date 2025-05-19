<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotel POS - Consumos</title>
  @include('components.pos')

</head>
<body>
    <!-- Top Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <i class="fas fa-hotel me-2"></i>Hotel POS
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.html"><i class="fas fa-home me-1"></i> Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="#"><i class="fas fa-utensils me-1"></i> Consumos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#"><i class="fas fa-history me-1"></i> Histórico</a>
                    </li>
                </ul>
                <div class="d-flex">
                    <span class="navbar-text text-white me-3">
                        <i class="fas fa-user-circle me-1"></i> Usuário Admin
                    </span>
                    <button class="btn btn-outline-light btn-sm">
                        <i class="fas fa-sign-out-alt"></i>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container-fluid main-content">
        <div class="row">
            <!-- Left Content Area (vazia para customização futura) -->
            <div class="col-lg-9 col-md-8 py-3">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i> Área para customização futura. Aqui pode ser exibido o menu do restaurante, serviços adicionais, etc.
                </div>
                
                <!-- Exemplo de conteúdo que pode ser adicionado -->
                <div class="card mb-4 d-none">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-utensils me-2"></i>Cardápio do Restaurante</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <div class="card h-100">
                                    <img src="https://via.placeholder.com/300x200?text=Café+da+Manhã" class="card-img-top" alt="Café da Manhã">
                                    <div class="card-body">
                                        <h5 class="card-title">Café da Manhã</h5>
                                        <p class="card-text">Pão, frios, frutas, suco e café.</p>
                                        <button class="btn btn-sm btn-outline-primary w-100">Adicionar</button>
                                    </div>
                                    <div class="card-footer bg-transparent">
                                        <small class="text-muted">R$ 25,00</small>
                                    </div>
                                </div>
                            </div>
                            <!-- Mais itens do cardápio -->
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Sidebar (Área de Consumos) -->
            <div class="col-lg-3 col-md-4 sidebar py-3">
                <h5 class="mb-3"><i class="fas fa-shopping-cart me-2 text-primary"></i>Consumos</h5>
                
                <div class="mb-3">
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                        <input type="text" class="form-control" placeholder="Buscar quarto...">
                    </div>
                </div>
                
                <div class="list-group mb-3">
                    <a href="#" class="list-group-item list-group-item-action active">
                        <div class="d-flex w-100 justify-content-between">
                            <h6 class="mb-1">Quarto 102</h6>
                            <small>Ocupado</small>
                        </div>
                        <p class="mb-1">João da Silva</p>
                        <small>Check-in: 10/05/2023</small>
                    </a>
                    <a href="#" class="list-group-item list-group-item-action">
                        <div class="d-flex w-100 justify-content-between">
                            <h6 class="mb-1">Quarto 105</h6>
                            <small>Ocupado</small>
                        </div>
                        <p class="mb-1">Maria Souza</p>
                        <small>Check-in: 09/05/2023</small>
                    </a>
                    <a href="#" class="list-group-item list-group-item-action">
                        <div class="d-flex w-100 justify-content-between">
                            <h6 class="mb-1">Quarto 201</h6>
                            <small>Ocupado</small>
                        </div>
                        <p class="mb-1">Carlos Oliveira</p>
                        <small>Check-in: 11/05/2023</small>
                    </a>
                </div>
                
                <hr>
                
                <h6 class="mb-3">Consumos - Quarto 102</h6>
                
                <div class="consumo-list mb-3">
                    <div class="consumo-item p-3 mb-2 bg-white rounded shadow-sm">
                        <div class="d-flex justify-content-between">
                            <strong>Café da Manhã</strong>
                            <span>R$ 25,00</span>
                        </div>
                        <div class="d-flex justify-content-between text-muted small">
                            <span>Qtd: 2</span>
                            <span>Total: R$ 50,00</span>
                        </div>
                        <div class="text-end mt-1">
                            <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                        </div>
                    </div>
                    
                    <div class="consumo-item p-3 mb-2 bg-white rounded shadow-sm">
                        <div class="d-flex justify-content-between">
                            <strong>Lavanderia</strong>
                            <span>R$ 30,00</span>
                        </div>
                        <div class="d-flex justify-content-between text-muted small">
                            <span>Qtd: 1</span>
                            <span>Total: R$ 30,00</span>
                        </div>
                        <div class="text-end mt-1">
                            <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                        </div>
                    </div>
                    
                    <div class="consumo-item p-3 mb-2 bg-white rounded shadow-sm">
                        <div class="d-flex justify-content-between">
                            <strong>Jantar</strong>
                            <span>R$ 50,00</span>
                        </div>
                        <div class="d-flex justify-content-between text-muted small">
                            <span>Qtd: 1</span>
                            <span>Total: R$ 50,00</span>
                        </div>
                        <div class="text-end mt-1">
                            <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                        </div>
                    </div>
                </div>
                
                <div class="card border-primary mb-3">
                    <div class="card-header bg-light">
                        <h6 class="mb-0">Resumo</h6>
                    </div>
                    <div class="card-body p-2">
                        <div class="d-flex justify-content-between mb-1">
                            <span>Diárias (3):</span>
                            <span>R$ 1.050,00</span>
                        </div>
                        <div class="d-flex justify-content-between mb-1">
                            <span>Consumos:</span>
                            <span>R$ 130,00</span>
                        </div>
                        <hr class="my-1">
                        <div class="d-flex justify-content-between fw-bold">
                            <span>Total:</span>
                            <span>R$ 1.180,00</span>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent p-2">
                        <button class="btn btn-sm btn-primary w-100" data-bs-toggle="modal" data-bs-target="#checkoutModal">
                            <i class="fas fa-door-closed me-1"></i> Check-out
                        </button>
                    </div>
                </div>
                
                <button class="btn btn-sm btn-outline-primary w-100 mb-2">
                    <i class="fas fa-plus-circle me-1"></i> Adicionar Consumo
                </button>
                <button class="btn btn-sm btn-outline-secondary w-100">
                    <i class="fas fa-print me-1"></i> Prévia de Conta
                </button>
            </div>
        </div>
    </div>

    <!-- Footer Menu -->
    <div class="footer-menu py-2">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-auto">
                    <button class="btn btn-sm btn-primary">
                        <i class="fas fa-user-plus"></i> Novo Hóspede
                    </button>
                </div>
                <div class="col-auto">
                    <button class="btn btn-sm btn-success">
                        <i class="fas fa-key"></i> Check-in Rápido
                    </button>
                </div>
                <div class="col-auto">
                    <button class="btn btn-sm btn-danger">
                        <i class="fas fa-door-closed"></i> Check-out
                    </button>
                </div>
                <div class="col-auto">
                    <button class="btn btn-sm btn-warning">
                        <i class="fas fa-file-invoice-dollar"></i> Faturas
                    </button>
                </div>
                <div class="col-auto">
                    <button class="btn btn-sm btn-info">
                        <i class="fas fa-cog"></i> Configurações
                    </button>
                </div>
            </div>
        </div>
    </div>

    @include('components.posjs')
    <!-- Bootstrap JS Bundle with Popper -->

</body>
</html>