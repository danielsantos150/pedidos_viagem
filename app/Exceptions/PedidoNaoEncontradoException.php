<?php

namespace App\Exceptions;

use Exception;

class PedidoNaoEncontradoException extends Exception
{
    /**
     * Código de erro HTTP customizado para a exceção.
     *
     * @var int
     */
    protected $statusCode = 513;

    /**
     * A construção da exceção de buscar o pedido.
     *
     * @param string $message
     * @param int $code
     * @return void
     */
    public function __construct($message = "Erro ao buscar o pedido", $code = 513, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    /**
     * Recupera o código de status HTTP.
     *
     * @return int
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }
}
