
<!-- Incluir SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
var ctx1 = document.getElementById("chart-line").getContext("2d");
var chartLine;

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

function carregarDadosGrafico(periodo = '7dias') {
    // Feedback visual
    document.getElementById('grafico-titulo').textContent = 'Carregando...';
    document.getElementById('variacao-texto').textContent = 'Carregando...';

    // Forçar dimensões do canvas
    const canvas = document.getElementById('chart-line');
    canvas.width = canvas.parentElement.offsetWidth;
    canvas.height = 300;

    fetch(`/dashboard/dados-grafico?periodo=${periodo}`)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            // Verificar se os dados são válidos
            if (!data.labels || !data.data) {
                throw new Error('Dados do gráfico inválidos');
            }

            // Atualiza título e variação
            document.getElementById('grafico-titulo').textContent = data.titulo;
            const variacao = data.variacao ?? 0;
            document.getElementById('variacao-texto').textContent =
                variacao > 0 ? `${variacao}% aumento` :
                variacao < 0 ? `${Math.abs(variacao)}% redução` : 'Sem variação';

            // Destruir gráfico anterior, se existir
            if (chartLine) {
                chartLine.destroy();
            }

            // Criar novo gráfico
            var gradientStroke1 = ctx1.createLinearGradient(0, 230, 0, 50);
            gradientStroke1.addColorStop(1, 'rgba(94, 114, 228, 0.2)');
            gradientStroke1.addColorStop(0.2, 'rgba(94, 114, 228, 0.0)');
            gradientStroke1.addColorStop(0, 'rgba(94, 114, 228, 0)');

            chartLine = new Chart(ctx1, {
                type: "line",
                data: {
                    labels: traduzirLabels(data.labels),
                    datasets: [{
                        label: "Reservas",
                        tension: 0.4,
                        pointRadius: 0,
                        borderColor: "#5e72e4",
                        backgroundColor: gradientStroke1,
                        borderWidth: 3,
                        fill: true,
                        data: data.data
                    }],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    interaction: {
                        intersect: false,
                        mode: 'index',
                    },
                    scales: {
                        y: {
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
            document.getElementById('grafico-titulo').textContent = 'Erro ao carregar o gráfico';
            document.getElementById('variacao-texto').textContent = 'Tente novamente';
            Swal.fire({
                icon: 'error',
                title: 'Erro',
                text: 'Não foi possível carregar o gráfico. Tente novamente mais tarde.',
                confirmButtonText: 'OK'
            });
        });
}

// Função para baixar o PDF
function baixarPDF(periodo) {
    const isAdmin = @json(auth()->user()->tipo === 'Administrador');
    if (!isAdmin) {
        Swal.fire({
            icon: 'error',
            title: 'Acesso Restrito',
            text: 'Esta função é exclusiva para administradores.',
            confirmButtonText: 'OK'
        });
        return;
    }
    const url = `/dashboard/relatorio-pdf?periodo=${periodo}`;
    window.open(url, '_blank');
}

document.querySelectorAll('.periodo-btn').forEach(btn => {
    btn.addEventListener('click', function () {
        document.querySelectorAll('.periodo-btn').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        carregarDadosGrafico(this.dataset.periodo);
    });
});

// Adiciona evento ao botão de PDF
document.getElementById('btn-pdf').addEventListener('click', function () {
    const activePeriodo = document.querySelector('.periodo-btn.active').dataset.periodo;
    baixarPDF(activePeriodo);
});

// Carregar gráfico na inicialização
document.addEventListener('DOMContentLoaded', function () {
    carregarDadosGrafico('7dias');
});
</script>