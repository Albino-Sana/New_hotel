<?php

   namespace App\Livewire;

   use App\Models\Quarto;
   use Livewire\Component;

   class QuartosStatus extends Component
   {
    public $quartos = [];
    public $novosQuartos = [];
  

      
       public function loadQuartos()
       {
           $quartosAtuais = $this->quartos ? array_column($this->quartos, 'id') : [];
           $this->quartos = Quarto::with(['tipo', 'checkin', 'hospede'])
               ->whereIn('status', ['Disponível', 'Ocupado', 'Reservado'])
               ->orderBy('numero')
               ->get()
               ->map(function ($quarto) {
                   return [
                       'id' => $quarto->id,
                       'numero' => $quarto->numero,
                       'andar' => $quarto->andar,
                       'tipo' => $quarto->tipo->nome,
                       'preco_noite' => number_format($quarto->preco_noite, 2, ',', '.'),
                       'status' => $quarto->status,
                       'checkin' => $quarto->checkin ? ['id' => $quarto->checkin->id] : null,
                       'hospede' => $quarto->hospede ? ['id' => $quarto->hospede->id] : null,
                   ];
               })->toArray();

           // Detectar novos quartos
           $novosIds = array_column($this->quartos, 'id');
           $this->novosQuartos = array_filter($this->quartos, function ($quarto) use ($quartosAtuais) {
               return !in_array($quarto['id'], $quartosAtuais);
           });

           // Disparar notificação se houver novos quartos
           if (!empty($this->novosQuartos)) {
               $this->dispatchBrowserEvent('mostrar-notificacao', [
                   'mensagem' => 'Novo(s) quarto(s) cadastrado(s): ' . implode(', ', array_column($this->novosQuartos, 'numero'))
               ]);
           }
       }

       public function render()
       {
           return view('livewire.quartos-status');
       }
   }
   