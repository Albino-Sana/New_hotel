<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    var ctxServicosExtras = document.getElementById("servicosExtrasChart").getContext("2d");
    var servicosExtrasChart;

    const mesesPTBR = {
        'Jan': 'Janeiro',
        'Feb': 'Fevereiro',
        'Mar': 'Março',
        'Apr': 'Abril',
        'May': 'Maio',
        'Jun': 'Junho',
        'Jul': 'Julho',
        'Aug': 'Agosto',
        'Sep': 'Setembro',
        'Oct': 'Outubro',
        'Nov': 'Novembro',
        'Dec': 'Dezembro'
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

    function formatarMoeda(valor) {
        return 'Kz ' + valor.toFixed(2).replace('.', ',').replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    function carregarDadosServicosExtras(periodo = '7dias') {
        document.getElementById('grafico-titulo').textContent = 'Carregando...';
        document.getElementById('variacao-texto').textContent = 'Carregando...';

        fetch(`/relatorios/dados-servicos-extras?periodo=${periodo}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('grafico-titulo').textContent = data.titulo;
                const variacao = data.variacao ?? 0;
                document.getElementById('variacao-texto').textContent =
                    variacao > 0 ? `${variacao}% aumento` :
                    variacao < 0 ? `${Math.abs(variacao)}% redução` : 'Sem variação';

                if (servicosExtrasChart) servicosExtrasChart.destroy();

                var gradientStroke = ctxServicosExtras.createLinearGradient(0, 230, 0, 50);
                gradientStroke.addColorStop(1, 'rgba(94, 114, 228, 0.2)');
                gradientStroke.addColorStop(0.2, 'rgba(94, 114, 228, 0.0)');
                gradientStroke.addColorStop(0, 'rgba(94, 114, 228, 0)');

                servicosExtrasChart = new Chart(ctxServicosExtras, {
                    type: "line",
                    data: {
                        labels: traduzirLabels(data.labels),
                        datasets: [{
                            label: "Serviços Extras",
                            tension: 0.4,
                            pointRadius: 0,
                            borderColor: "#5e72e4",
                            backgroundColor: gradientStroke,
                            borderWidth: 3,
                            fill: true,
                            data: data.data
                        }],
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return formatarMoeda(context.parsed.y);
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
                                        return formatarMoeda(value);
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
    function baixarPDFServicosExtras(periodo) {
        const url = `/relatorios/relatorio-servicos-extras-pdf?periodo=${periodo}`;
        window.open(url, '_blank'); // Abre ou baixa o PDF dependendo do backend
    }

    document.querySelectorAll('.periodo-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            document.querySelectorAll('.periodo-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            carregarDadosServicosExtras(this.dataset.periodo);
        });
    });

    // Adiciona evento ao botão de PDF
    document.getElementById('btn-pdf-servicos-extras').addEventListener('click', function () {
        const activePeriodo = document.querySelector('.periodo-btn.active').dataset.periodo;
        baixarPDFServicosExtras(activePeriodo);
    });

    document.addEventListener('DOMContentLoaded', function () {
        carregarDadosServicosExtras('7dias');
    });
</script>