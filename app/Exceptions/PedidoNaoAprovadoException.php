<?php

namespace App\Exceptions;

use Exception;

class PedidoNaoAprovadoException extends Exception
{
    /**
     * Código de erro HTTP customizado para a exceção.
     *
     * @var int
     */
    protected $statusCode = 514;

    /**
     * Constructor
     */
    public function __construct($message = 'O pedido não pode ser cancelado pois não está aprovado.', $code = 514, Exception $previous = null)
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