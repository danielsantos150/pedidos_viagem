<?php

namespace App\Services;

use App\Exceptions\PedidoNaoEncontradoException;
use App\Repositories\PedidoRepository;
use App\Models\Pedido;
use App\Notifications\PedidoStatusNotificado;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Notification;

class PedidoService
{
    protected $pedidoRepository;

    public function __construct(PedidoRepository $pedidoRepository)
    {
        $this->pedidoRepository = $pedidoRepository;
    }

    /**
     * Criar um novo pedido de viagem.
     *
     * @param array $data
     * @return Pedido
     */
    public function criarPedido(array $data): Pedido
    {
        return $this->pedidoRepository->create($data);
    }

    /**
     * Atualiza o status de um pedido de viagem.
     *
     * @param int $id O ID do pedido a ser atualizado.
     * @param string $status O novo status do pedido.
     * @return bool
     */
    public function atualizarStatus(int $id, array $dados): Pedido
    {
        $pedido = $this->consultarPedido($id);
        if ($pedido) {
            $pedidoAtualizado = $this->pedidoRepository->updateStatus($pedido, $dados["status"]);
            if ($dados["notificar"] && in_array($dados["status"], ["aprovado", "cancelado"])) {
                Notification::route('mail', $pedidoAtualizado->email)
                        ->notify(new PedidoStatusNotificado($pedidoAtualizado->status, $pedidoAtualizado));
            }
            return $pedidoAtualizado;
        }
        throw new PedidoNaoEncontradoException("O pedido inexistente.");
        
    }

    /**
     * Consulta um pedido específico pelo ID.
     *
     * @param int $id O ID do pedido a ser consultado.
     * @return mixed
     *
     * @throws PedidoNotFoundException Se o pedido não for encontrado.
     */
    public function consultarPedido(int $id)
    {
        return $this->pedidoRepository->findById($id);
    }

    /**
     * Listar pedidos com filtros de status, período e destino.
     *
     * @param array $filters
     * @return Collection
     */
    public function listarPedidos(array $filters): Collection
    {
        return $this->pedidoRepository->filtrarPedidos($filters);
    }
}
