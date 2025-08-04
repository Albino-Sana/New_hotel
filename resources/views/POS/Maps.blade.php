<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mapa de Reservas - Hotel</title>
    @include('components.maps')
</head>
<body>
    <div class="header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h1><i class="bi bi-building"></i> Mapa de Reservas</h1>
                    <p class="mb-0">Visualize e gerencie os quartos do hotel</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <div class="d-inline-block bg-white p-2 rounded-3 shadow-sm">
                        <span id="current-date" class="fw-bold"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="floor-tabs p-2">
            <div class="floor-tab active" data-floor="all">Todos</div>
            <div class="floor-tab" data-floor="1">Piso 1</div>
            <div class="floor-tab" data-floor="2">Piso 2</div>
            <div class="floor-tab" data-floor="3">Piso 3</div>
            <div class="floor-tab" data-floor="north">Ala Norte</div>
            <div class="floor-tab" data-floor="south">Ala Sul</div>
        </div>

        <div class="filter-container">
            <h5 class="mb-3"><i class="bi bi-funnel"></i> Filtros</h5>
            <div class="row">
                <div class="col-md-3 col-6 mb-2">
                    <div class="form-check">
                        <input class="form-check-input filter-checkbox" type="checkbox" value="available" id="filter-available" checked>
                        <label class="form-check-label" for="filter-available">
                            <span class="status-badge status-available"></span> Livre
                        </label>
                    </div>
                </div>
                <div class="col-md-3 col-6 mb-2">
                    <div class="form-check">
                        <input class="form-check-input filter-checkbox" type="checkbox" value="booked" id="filter-booked" checked>
                        <label class="form-check-label" for="filter-booked">
                            <span class="status-badge status-booked"></span> Reservado
                        </label>
                    </div>
                </div>
                <div class="col-md-3 col-6 mb-2">
                    <div class="form-check">
                        <input class="form-check-input filter-checkbox" type="checkbox" value="occupied" id="filter-occupied" checked>
                        <label class="form-check-label" for="filter-occupied">
                            <span class="status-badge status-occupied"></span> Ocupado
                        </label>
                    </div>
                </div>
                <div class="col-md-3 col-6 mb-2">
                    <div class="form-check">
                        <input class="form-check-input filter-checkbox" type="checkbox" value="maintenance" id="filter-maintenance" checked>
                        <label class="form-check-label" for="filter-maintenance">
                            <span class="status-badge status-maintenance"></span> Manutenção
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <div id="rooms-container">
            <!-- Rooms will be loaded here by JavaScript -->
        </div>
    </div>

    <!-- Room Details Modal -->
    <div class="modal fade" id="roomModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalRoomTitle">Detalhes do Quarto</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-6">
                            <p class="mb-1"><strong>Número:</strong></p>
                            <p id="modalRoomNumber">-</p>
                        </div>
                        <div class="col-6">
                            <p class="mb-1"><strong>Tipo:</strong></p>
                            <p id="modalRoomType">-</p>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-6">
                            <p class="mb-1"><strong>Status:</strong></p>
                            <p id="modalRoomStatus">-</p>
                        </div>
                        <div class="col-6">
                            <p class="mb-1"><strong>Piso/Ala:</strong></p>
                            <p id="modalRoomFloor">-</p>
                        </div>
                    </div>
                    <div class="mb-3" id="guestInfoSection">
                        <p class="mb-1"><strong>Hóspede:</strong></p>
                        <p id="modalGuestName">-</p>
                        <p class="mb-1"><strong>Check-in:</strong></p>
                        <p id="modalCheckIn">-</p>
                        <p class="mb-1"><strong>Check-out:</strong></p>
                        <p id="modalCheckOut">-</p>
                    </div>
                    <div class="alert alert-info" id="noGuestInfo">
                        Este quarto está disponível para reserva.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                    <button type="button" class="btn btn-primary" id="bookBtn">Reservar</button>
                    <button type="button" class="btn btn-warning" id="checkInBtn">Check-in</button>
                    <button type="button" class="btn btn-danger" id="checkOutBtn">Check-out</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Booking Modal -->
    <div class="modal fade" id="bookingModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Reservar Quarto <span id="bookingRoomNumber"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="bookingForm">
                        <div class="mb-3">
                            <label for="guestName" class="form-label">Nome do Hóspede</label>
                            <input type="text" class="form-control" id="guestName" required>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="checkInDate" class="form-label">Check-in</label>
                                <input type="date" class="form-control" id="checkInDate" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="checkOutDate" class="form-label">Check-out</label>
                                <input type="date" class="form-control" id="checkOutDate" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="specialRequests" class="form-label">Pedidos Especiais</label>
                            <textarea class="form-control" id="specialRequests" rows="3"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="confirmBookingBtn">Confirmar Reserva</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Sample data for rooms
        const roomsData = [
            { number: '101', type: 'Single', status: 'available', floor: '1', block: 'north', guest: null },
            { number: '102', type: 'Single', status: 'booked', floor: '1', block: 'north', guest: { name: 'João Silva', checkIn: '2023-06-15', checkOut: '2023-06-20' } },
            { number: '103', type: 'Duplo', status: 'occupied', floor: '1', block: 'north', guest: { name: 'Maria Santos', checkIn: '2023-06-10', checkOut: '2023-06-17' } },
            { number: '104', type: 'Suite', status: 'maintenance', floor: '1', block: 'north', guest: null },
            { number: '105', type: 'Single', status: 'available', floor: '1', block: 'north', guest: null },
            { number: '201', type: 'Duplo', status: 'available', floor: '2', block: 'south', guest: null },
            { number: '202', type: 'Duplo', status: 'booked', floor: '2', block: 'south', guest: { name: 'Carlos Oliveira', checkIn: '2023-06-18', checkOut: '2023-06-25' } },
            { number: '203', type: 'Suite', status: 'occupied', floor: '2', block: 'south', guest: { name: 'Ana Costa', checkIn: '2023-06-12', checkOut: '2023-06-19' } },
            { number: '204', type: 'Single', status: 'available', floor: '2', block: 'south', guest: null },
            { number: '205', type: 'Suite', status: 'maintenance', floor: '2', block: 'south', guest: null },
            { number: '301', type: 'Suite', status: 'available', floor: '3', block: 'north', guest: null },
            { number: '302', type: 'Duplo', status: 'booked', floor: '3', block: 'north', guest: { name: 'Pedro Alves', checkIn: '2023-06-22', checkOut: '2023-06-29' } },
            { number: '303', type: 'Single', status: 'available', floor: '3', block: 'north', guest: null },
            { number: '304', type: 'Suite', status: 'occupied', floor: '3', block: 'north', guest: { name: 'Luísa Fernandes', checkIn: '2023-06-14', checkOut: '2023-06-21' } },
            { number: '305', type: 'Duplo', status: 'available', floor: '3', block: 'north', guest: null },
        ];

        // DOM elements
        const roomsContainer = document.getElementById('rooms-container');
        const floorTabs = document.querySelectorAll('.floor-tab');
        const filterCheckboxes = document.querySelectorAll('.filter-checkbox');
        const currentDateElement = document.getElementById('current-date');
        
        // Modal elements
        const roomModal = new bootstrap.Modal(document.getElementById('roomModal'));
        const bookingModal = new bootstrap.Modal(document.getElementById('bookingModal'));
        
        // Current selected room
        let selectedRoom = null;

        // Initialize the app
        document.addEventListener('DOMContentLoaded', function() {
            // Set current date
            const today = new Date();
            currentDateElement.textContent = today.toLocaleDateString('pt-BR', { 
                weekday: 'long', 
                year: 'numeric', 
                month: 'long', 
                day: 'numeric' 
            });
            
            // Load rooms
            renderRooms('all');
            
            // Set up event listeners
            setupEventListeners();
        });

        // Set up event listeners
        function setupEventListeners() {
            // Floor tabs
            floorTabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    floorTabs.forEach(t => t.classList.remove('active'));
                    this.classList.add('active');
                    renderRooms(this.dataset.floor);
                });
            });
            
            // Filter checkboxes
            filterCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const activeTab = document.querySelector('.floor-tab.active');
                    renderRooms(activeTab.dataset.floor);
                });
            });
            
            // Modal buttons
            document.getElementById('bookBtn').addEventListener('click', function() {
                roomModal.hide();
                showBookingModal();
            });
            
            document.getElementById('checkInBtn').addEventListener('click', function() {
                if (selectedRoom) {
                    selectedRoom.status = 'occupied';
                    renderRooms(document.querySelector('.floor-tab.active').dataset.floor);
                    roomModal.hide();
                    showAlert('Check-in realizado com sucesso!', 'success');
                }
            });
            
            document.getElementById('checkOutBtn').addEventListener('click', function() {
                if (selectedRoom) {
                    selectedRoom.status = 'available';
                    selectedRoom.guest = null;
                    renderRooms(document.querySelector('.floor-tab.active').dataset.floor);
                    roomModal.hide();
                    showAlert('Check-out realizado com sucesso!', 'success');
                }
            });
            
            document.getElementById('confirmBookingBtn').addEventListener('click', function() {
                const guestName = document.getElementById('guestName').value;
                const checkInDate = document.getElementById('checkInDate').value;
                const checkOutDate = document.getElementById('checkOutDate').value;
                
                if (guestName && checkInDate && checkOutDate && selectedRoom) {
                    selectedRoom.status = 'booked';
                    selectedRoom.guest = {
                        name: guestName,
                        checkIn: checkInDate,
                        checkOut: checkOutDate
                    };
                    
                    renderRooms(document.querySelector('.floor-tab.active').dataset.floor);
                    bookingModal.hide();
                    showAlert('Reserva confirmada com sucesso!', 'success');
                } else {
                    showAlert('Por favor, preencha todos os campos obrigatórios.', 'danger');
                }
            });
        }

        // Render rooms based on selected floor and filters
        function renderRooms(floor) {
            // Get active filters
            const activeFilters = Array.from(filterCheckboxes)
                .filter(checkbox => checkbox.checked)
                .map(checkbox => checkbox.value);
            
            // Filter rooms
            let filteredRooms = roomsData.filter(room => {
                // Filter by status
                if (!activeFilters.includes(room.status)) return false;
                
                // Filter by floor/block
                if (floor === 'all') return true;
                if (floor === room.floor) return true;
                if (floor === room.block) return true;
                
                return false;
            });
            
            // Sort rooms by number
            filteredRooms.sort((a, b) => a.number.localeCompare(b.number));
            
            // Clear container
            roomsContainer.innerHTML = '';
            
            // Add rooms to container
            if (filteredRooms.length === 0) {
                roomsContainer.innerHTML = '<div class="col-12 text-center py-5 text-muted">Nenhum quarto encontrado com os filtros selecionados.</div>';
                return;
            }
            
            const roomGrid = document.createElement('div');
            roomGrid.className = 'room-grid fade-in';
            
            filteredRooms.forEach(room => {
                const roomCard = document.createElement('div');
                roomCard.className = `room-card ${room.status}`;
                roomCard.innerHTML = `
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h5 class="mb-0">${room.number}</h5>
                        <span class="status-badge status-${room.status}"></span>
                    </div>
                    <div class="d-flex align-items-center text-muted mb-2">
                        <i class="bi ${getRoomTypeIcon(room.type)} room-type-icon"></i>
                        <small>${room.type}</small>
                    </div>
                    <small class="d-block"><strong>${getStatusText(room.status)}</strong></small>
                `;
                
                roomCard.addEventListener('click', () => showRoomDetails(room));
                roomGrid.appendChild(roomCard);
            });
            
            roomsContainer.appendChild(roomGrid);
        }

        // Show room details in modal
        function showRoomDetails(room) {
            selectedRoom = roomsData.find(r => r.number === room.number);
            
            // Set modal title
            document.getElementById('modalRoomTitle').textContent = `Quarto ${room.number}`;
            
            // Set room info
            document.getElementById('modalRoomNumber').textContent = room.number;
            document.getElementById('modalRoomType').textContent = room.type;
            document.getElementById('modalRoomStatus').innerHTML = `<span class="status-badge status-${room.status}"></span> ${getStatusText(room.status)}`;
            document.getElementById('modalRoomFloor').textContent = getFloorText(room);
            
            // Set guest info if available
            if (room.guest) {
                document.getElementById('guestInfoSection').style.display = 'block';
                document.getElementById('noGuestInfo').style.display = 'none';
                
                document.getElementById('modalGuestName').textContent = room.guest.name;
                document.getElementById('modalCheckIn').textContent = formatDate(room.guest.checkIn);
                document.getElementById('modalCheckOut').textContent = formatDate(room.guest.checkOut);
            } else {
                document.getElementById('guestInfoSection').style.display = 'none';
                document.getElementById('noGuestInfo').style.display = 'block';
            }
            
            // Set button visibility based on status
            const bookBtn = document.getElementById('bookBtn');
            const checkInBtn = document.getElementById('checkInBtn');
            const checkOutBtn = document.getElementById('checkOutBtn');
            
            bookBtn.style.display = room.status === 'available' ? 'block' : 'none';
            checkInBtn.style.display = room.status === 'booked' ? 'block' : 'none';
            checkOutBtn.style.display = room.status === 'occupied' ? 'block' : 'none';
            
            // Show modal
            roomModal.show();
        }

        // Show booking modal
        function showBookingModal() {
            if (!selectedRoom) return;
            
            // Set room number
            document.getElementById('bookingRoomNumber').textContent = selectedRoom.number;
            
            // Set default dates (today and tomorrow)
            const today = new Date();
            const tomorrow = new Date();
            tomorrow.setDate(today.getDate() + 1);
            
            document.getElementById('checkInDate').valueAsDate = today;
            document.getElementById('checkOutDate').valueAsDate = tomorrow;
            
            // Clear other fields
            document.getElementById('guestName').value = '';
            document.getElementById('specialRequests').value = '';
            
            // Show modal
            bookingModal.show();
        }

        // Helper functions
        function getStatusText(status) {
            const statusMap = {
                'available': 'Livre',
                'booked': 'Reservado',
                'occupied': 'Ocupado',
                'maintenance': 'Manutenção'
            };
            return statusMap[status] || status;
        }

        function getFloorText(room) {
            if (room.block === 'north') return 'Ala Norte';
            if (room.block === 'south') return 'Ala Sul';
            return `Piso ${room.floor}`;
        }

        function getRoomTypeIcon(type) {
            const iconMap = {
                'Single': 'bi-person',
                'Duplo': 'bi-people',
                'Suite': 'bi-stars'
            };
            return iconMap[type] || 'bi-door-closed';
        }

        function formatDate(dateString) {
            if (!dateString) return '-';
            const date = new Date(dateString);
            return date.toLocaleDateString('pt-BR');
        }

        function showAlert(message, type) {
            const alert = document.createElement('div');
            alert.className = `alert alert-${type} position-fixed top-0 end-0 m-3 fade-in`;
            alert.style.zIndex = '1100';
            alert.style.maxWidth = '300px';
            alert.textContent = message;
            
            document.body.appendChild(alert);
            
            setTimeout(() => {
                alert.classList.add('fade-out');
                setTimeout(() => alert.remove(), 300);
            }, 3000);
        }
    </script>
</body>
</html>