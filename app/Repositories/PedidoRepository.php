<?php

namespace App\Repositories;

use App\Exceptions\AtualizacaoStatusException;
use App\Exceptions\PedidoCriacaoException;
use App\Exceptions\PedidoNaoAprovadoException;
use App\Exceptions\PedidoNaoEncontradoException;
use App\Models\Pedido;
use Exception;
use Illuminate\Support\Collection;

class PedidoRepository
{
    /**
     * Criar um novo pedido.
     *
     * @param array $data
     * @return Pedido
     * 
     * @throws PedidoCriacaoExpcetion Se ocorrer um erro ao criar o pedido.
     */
    public function create(array $data): Pedido
    {
        try {
            return Pedido::create($data);
        } catch (Exception $e) {
            throw new PedidoCriacaoException('Falha ao criar o pedido de viagem.');
        }
    }

    /**
     * Atualiza o status de um pedido no banco de dados.
     *
     * @param Pedido $pedido O pedido a ser atualizado.
     * @param string $status O novo status do pedido.
     * @return Pedido
     *
     * @throws AtualizacaoStatusException Se ocorrer um erro ao atualizar o pedido.
     */
    public function updateStatus(Pedido $pedido, string $status)
    {
        try {
            if ($status === 'cancelado' && $pedido->status !== 'aprovado') {
                throw new PedidoNaoAprovadoException();
            }
            $pedido->status = $status;
            $pedido->save();

            return $pedido;
        } catch (PedidoNaoAprovadoException $e) {
            throw new PedidoNaoAprovadoException('Apenas será possível cancelar um pedido após sua aprovação.');
        } catch (Exception $e) {
            throw new AtualizacaoStatusException('Erro ao atualizar status do pedido.');
        }
    }

    /**
     * Busca um pedido específico pelo ID.
     *
     * @param int $id O ID do pedido a ser consultado.
     * @return Pedido
     *
     * @throws PedidoNaoEncontradoException Se o pedido não for encontrado.
     */
    public function findById($id)
    {
        $pedido = Pedido::find($id);
        
        if (!$pedido) {
            throw new PedidoNaoEncontradoException("Pedido não encontrado.");
        }
        
        return $pedido;
    }

    /**
     * Filtrar pedidos com base nos parâmetros fornecidos.
     *
     * @param array $filters
     * @return Collection
     */
    public function filtrarPedidos(array $filters): Collection
    {
        $query = Pedido::query();

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['data_ida'])) {
            $query->where('data_ida', '>=', $filters['data_ida']);
        }

        if (!empty($filters['data_volta'])) {
            $query->where('data_volta', '<=', $filters['data_volta']);
        }

        if (!empty($filters['destino'])) {
            $query->where('destino', 'like', '%' . $filters['destino'] . '%');
        }

        return $query->get();
    }
}

