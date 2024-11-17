<?php

namespace App\Exceptions;

use Exception;

class AtualizacaoStatusException extends Exception
{
    /**
     * Código de erro HTTP customizado para a exceção.
     *
     * @var int
     */
    protected $statusCode = 511;

    /**
     * A construção da exceção de atualizacao do status do pedido.
     *
     * @param string $message
     * @param int $code
     * @return void
     */
    public function __construct($message = "Erro ao atualizar o status do pedido.", $code = 511, Exception $previous = null)
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
