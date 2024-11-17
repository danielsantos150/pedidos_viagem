<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Services\PedidoService;
use App\Repositories\PedidoRepository;
use App\Models\Pedido;
use Illuminate\Support\Facades\Notification;
use App\Notifications\PedidoStatusNotificado;
use App\Exceptions\PedidoNaoEncontradoException;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PedidoServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $pedidoService;
    protected $pedidoRepository;

    protected function setUp(): void
    {
        parent::setUp();

        // Mock do repositório de pedidos
        $this->pedidoRepository = $this->createMock(PedidoRepository::class);
        $this->pedidoService = new PedidoService($this->pedidoRepository);
    }

    /** @test */
    public function deve_criar_um_novo_pedido_com_sucesso()
    {
        $dados = [
            'solicitante_nome' => 'João Silva',
            'destino' => 'Rio de Janeiro',
            'data_ida' => '2024-12-01',
            'data_volta' => '2024-12-10',
            'status' => 'pendente',
            'email' => 'danielmaia150@gmail.com',
        ];

        $pedido = new Pedido($dados);

        // Define a expectativa do método create no repositório
        $this->pedidoRepository
            ->expects($this->once())
            ->method('create')
            ->with($dados)
            ->willReturn($pedido);

        $resultado = $this->pedidoService->criarPedido($dados);

        $this->assertEquals($pedido, $resultado);
    }

    /** @test */
    public function deve_falhar_ao_tentar_atualizar_status_de_um_pedido_inexistente()
    {
        $this->expectException(PedidoNaoEncontradoException::class);

        // Simula que o pedido não é encontrado
        $this->pedidoRepository
            ->expects($this->once())
            ->method('findById')
            ->with(999);

        $this->pedidoService->atualizarStatus(999, ['status' => 'aprovado', 'notificar' => 0]);
    }

    /** @test */
    public function deve_retornar_pedido_ao_consultar_pedido_existente()
    {
        $pedido = Pedido::factory()->create();

        $this->pedidoRepository
            ->expects($this->once())
            ->method('findById')
            ->with($pedido->id)
            ->willReturn($pedido);

        $resultado = $this->pedidoService->consultarPedido($pedido->id);

        $this->assertEquals($pedido, $resultado);
    }

    /** @test */
    public function deve_listar_pedidos_com_filtros_aplicados()
    {
        $filtros = ['status' => 'aprovado'];
        $pedidos = collect([Pedido::factory()->make(['status' => 'aprovado'])]);

        $this->pedidoRepository
            ->expects($this->once())
            ->method('filtrarPedidos')
            ->with($filtros)
            ->willReturn($pedidos);

        $resultado = $this->pedidoService->listarPedidos($filtros);

        $this->assertCount(1, $resultado);
        $this->assertEquals('aprovado', $resultado->first()->status);
    }
}