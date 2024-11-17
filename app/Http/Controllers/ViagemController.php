<?php

namespace App\Http\Controllers;

use App\Exceptions\AtualizacaoStatusException;
use App\Exceptions\PedidoCriacaoException;
use App\Exceptions\PedidoNaoEncontradoException;
use App\Http\Requests\CriarPedidoRequest;
use App\Services\PedidoService;
use Exception;
use Symfony\Component\HttpFoundation\Response;
use App\Helpers\ResponseHelper;
use App\Http\Requests\AtualizaStatusRequest;
use App\Http\Requests\ListarPedidosRequest;
use Illuminate\Http\JsonResponse;
use Symfony\Component\Mailer\Exception\TransportException;

class ViagemController extends Controller
{

    protected $viagemService;

    public function __construct(PedidoService $viagemService)
    {
        $this->viagemService = $viagemService;
    }

    /**
     * Criar um novo pedido de viagem.
     *
     * @param CriarPedidoRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function criarPedido(CriarPedidoRequest $request)
    {
        try {
            $pedido = $this->viagemService->criarPedido($request->validated());
            return ResponseHelper::formatResponse(
                $pedido, 
                'Pedido Criado com sucesso', 
                Response::HTTP_CREATED
            );
        } catch (PedidoCriacaoException $e) {
            return ResponseHelper::formatResponse(
                [
                    'error' => $e->getMessage()
                ], 'Houve uma falha ao criar o pedido, tente novamente mais tarde.',
                $e->getStatusCode()
            );
        }
    }

    /**
     * Atualiza o status de um pedido de viagem.
     *
     * @param AtualizaStatusRequest $request A requisição contendo os dados de atualização.
     * @param int $id O ID do pedido a ser atualizado.
     * @return JsonResponse
     *
     * @throws PedidoUpdateException Se ocorrer um erro ao atualizar o status.
     */
    public function atualizarStatus(AtualizaStatusRequest $request, $id): JsonResponse
    {
        try {
            $pedido = $this->viagemService->atualizarStatus($id, $request->validated());

            return ResponseHelper::formatResponse($pedido, 'Status atualizado com sucesso');
        } catch (AtualizacaoStatusException $e) {
            return ResponseHelper::formatResponse([
                    'error' => $e->getMessage()
                ], 'Houve uma falha ao atualizar o status do pedido, tente novamente mais tarde.',
                $e->getStatusCode()
            );
        } catch (TransportException $e) {
            return ResponseHelper::formatResponse([
                'error' => $e->getMessage()
            ], 'Houve uma falha ao notificar o solicitante, tente novamente mais tarde.',
            Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Consulta um pedido de viagem específico pelo ID.
     *
     * @param int $id O ID do pedido a ser consultado.
     * @return \Illuminate\Http\JsonResponse
     */
    public function consultarPedido(int $id)
    {
        try {
            $pedido = $this->viagemService->consultarPedido($id);
            return ResponseHelper::formatResponse($pedido);
        } catch (PedidoNaoEncontradoException $e) {
            return ResponseHelper::formatResponse(null, null, Response::HTTP_NO_CONTENT);
        }
    }

    /**
     * Listar todos os pedidos de viagem.
     *
     * @return JsonResponse
     */
    public function listarPedidos(ListarPedidosRequest $request): JsonResponse
    {
        try {
            $pedidos = $this->viagemService->listarPedidos($request->validated());
            return ResponseHelper::formatResponse(
                $pedidos,
                "Lista de pedidos de viagem consultada com sucesso",
                ($pedidos->isEmpty()) ? Response::HTTP_NO_CONTENT : Response::HTTP_OK);
        } catch (\Exception $e) {
            return ResponseHelper::formatResponse(
                ['error' => $e->getMessage()],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
