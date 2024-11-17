<?php

namespace App\Exceptions;

use Exception;

class PedidoCriacaoException extends Exception
{
    /**
     * Código de erro HTTP customizado para a exceção.
     *
     * @var int
     */
    protected $statusCode = 510;

    /**
     * A construção da exceção de criação de pedido.
     *
     * @param string $message
     * @param int $code
     * @return void
     */
    public function __construct($message = "Erro ao criar pedido de viagem", $code = 510, Exception $previous = null)
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
