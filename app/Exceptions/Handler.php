<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * Uma lista das exceções que são reportadas.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
    ];

    /**
     * Registra as exceções para tratamento.
     *
     * @param  \Throwable  $exception
     * @return void
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Renderiza a resposta de uma exceção para a solicitação.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Throwable $exception)
    {
        // Verificando se a exceção é de autorização
        if ($exception instanceof AuthorizationException) {
            // Redireciona de volta com uma mensagem de erro
            return redirect()->back()->with('error', 'Acesso permitido apenas para administradores!');
        }

        // Passa a exceção para o próximo handler se não for do tipo AuthorizationException
        return parent::render($request, $exception);
    }
}
