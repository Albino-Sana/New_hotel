<?php

namespace App\Providers;
use Illuminate\Support\Facades\Gate;


use Illuminate\Support\ServiceProvider;


class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Admin tem acesso total
        Gate::define('admin-only', function ($user) {
            return $user->tipo === 'Administrador';
        });
    
        // Recepcionista tem acesso limitado
        Gate::define('recepcionista-only', function ($user) {
            return $user->tipo === 'Recepcionista' || $user->tipo === 'Administrador';
        });
        
        // Gate específico para reservas (admin + recepcionista)
        Gate::define('gerenciar-reservas', function ($user) {
            return in_array($user->tipo, ['Administrador', 'Recepcionista']);
        });
    }
    /**
     * Register any application services.
     */

     
}
