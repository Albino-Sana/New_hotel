<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    var ctxReservas = document.getElementById("reservasCancelamentosChart").getContext("2d");
    var reservasChart;

    const mesesPTBR = {
        'Jan': 'Janeiro', 'Feb': 'Fevereiro', 'Mar': 'Março', 'Apr': 'Abril', 'May': 'Maio',
        'Jun': 'Junho', 'Jul': 'Julho', 'Aug': 'Agosto', 'Sep': 'Setembro', 'Oct': 'Outubro',
        'Nov': 'Novembro', 'Dec': 'Dezembro'
    };

    function traduzirLabels(labels) {
        return labels.map(label => {
            const parts = label.split(' ');
            if (parts.length === 2 && mesesPTBR[parts[0]]) {
                return `${mesesPTBR[parts[0]]} ${parts[1]}`;
            }
            return label;
        });
    }

    function carregarDadosReservas(periodo = '7dias') {
        document.getElementById('grafico-titulo').textContent = 'Carregando...';
        document.getElementById('variacao-texto').textContent = 'Carregando...';

        fetch(`/relatorios/dados-reservas-cancelamentos?periodo=${periodo}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('grafico-titulo').textContent = data.titulo;
                const variacao = data.variacao ?? 0;
                document.getElementById('variacao-texto').textContent =
                    variacao > 0 ? `${variacao}% aumento` :
                    variacao < 0 ? `${Math.abs(variacao)}% redução` : 'Sem variação';

                if (reservasChart) reservasChart.destroy();

                var gradientCriadas = ctxReservas.createLinearGradient(0, 230, 0, 50);
                gradientCriadas.addColorStop(1, 'rgba(94, 114, 228, 0.2)');
                gradientCriadas.addColorStop(0.2, 'rgba(94, 114, 228, 0.0)');
                gradientCriadas.addColorStop(0, 'rgba(94, 114, 228, 0)');

                var gradientCanceladas = ctxReservas.createLinearGradient(0, 230, 0, 50);
                gradientCanceladas.addColorStop(1, 'rgba(255, 99, 132, 0.2)');
                gradientCanceladas.addColorStop(0.2, 'rgba(255, 99, 132, 0.0)');
                gradientCanceladas.addColorStop(0, 'rgba(255, 99, 132, 0)');

                var gradientApagadas = ctxReservas.createLinearGradient(0, 230, 0, 50);
                gradientApagadas.addColorStop(1, 'rgba(255, 159, 64, 0.2)');
                gradientApagadas.addColorStop(0.2, 'rgba(255, 159, 64, 0.0)');
                gradientApagadas.addColorStop(0, 'rgba(255, 159, 64, 0)');

                reservasChart = new Chart(ctxReservas, {
                    type: "line",
                    data: {
                        labels: traduzirLabels(data.labels),
                        datasets: [
                            {
                                label: "Reservas Criadas",
                                tension: 0.4,
                                pointRadius: 0,
                                borderColor: "#5e72e4",
                                backgroundColor: gradientCriadas,
                                borderWidth: 3,
                                fill: true,
                                data: data.data_criadas
                            },
                            {
                                label: "Reservas Canceladas",
                                tension: 0.4,
                                pointRadius: 0,
                                borderColor: "#ff6384",
                                backgroundColor: gradientCanceladas,
                                borderWidth: 3,
                                fill: true,
                                data: data.data_canceladas
                            },
                            {
                                label: "Reservas Apagadas",
                                tension: 0.4,
                                pointRadius: 0,
                                borderColor: "#ff9f40",
                                backgroundColor: gradientApagadas,
                                borderWidth: 3,
                                fill: true,
                                data: data.data_apagadas
                            }
                        ],
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: true, position: 'top' },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        const label = context.dataset.label;
                                        const value = context.parsed.y;
                                        return `${label}: ${value}`;
                                    }
                                }
                            }
                        },
                        interaction: {
                            intersect: false,
                            mode: 'index',
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: {
                                    drawBorder: false,
                                    display: true,
                                    drawOnChartArea: true,
                                    drawTicks: false,
                                    borderDash: [5, 5]
                                },
                                ticks: {
                                    display: true,
                                    padding: 10,
                                    color: '#fbfbfb',
                                    font: {
                                        size: 11,
                                        family: "Open Sans",
                                        style: 'normal',
                                        lineHeight: 2
                                    },
                                    callback: function(value) {
                                        return value + ' reservas';
                                    }
                                }
                            },
                            x: {
                                grid: {
                                    drawBorder: false,
                                    display: false,
                                    drawOnChartArea: false,
                                    drawTicks: false,
                                    borderDash: [5, 5]
                                },
                                ticks: {
                                    display: true,
                                    color: '#ccc',
                                    padding: 20,
                                    font: {
                                        size: 11,
                                        family: "Open Sans",
                                        style: 'normal',
                                        lineHeight: 2
                                    },
                                }
                            },
                        },
                    },
                });
            })
            .catch(error => {
                console.error('Erro ao carregar dados:', error);
                document.getElementById('grafico-titulo').textContent = 'Erro no gráfico';
                document.getElementById('variacao-texto').textContent = '-';
            });
    }

    // Função para baixar o PDF
    function baixarPDF(periodo) {
        const url = `/relatorios/relatorio-reservas-cancelamentos-pdf?periodo=${periodo}`;
        window.open(url, '_blank'); // Abre ou baixa o PDF dependendo do backend
    }

    document.querySelectorAll('.periodo-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            document.querySelectorAll('.periodo-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            carregarDadosReservas(this.dataset.periodo);
        });
    });

    // Adiciona evento ao botão de PDF
    document.getElementById('btn-pdf').addEventListener('click', function () {
        const activePeriodo = document.querySelector('.periodo-btn.active').dataset.periodo;
        baixarPDF(activePeriodo);
    });

    document.addEventListener('DOMContentLoaded', function () {
        carregarDadosReservas('7dias');
    });
</script>