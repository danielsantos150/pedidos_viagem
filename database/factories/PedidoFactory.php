<?php

namespace Database\Factories;

use App\Models\Pedido;
use Illuminate\Database\Eloquent\Factories\Factory;

class PedidoFactory extends Factory
{
    // Definir o modelo a ser utilizado pela fábrica
    protected $model = Pedido::class;

    /**
     * Definir o estado de um pedido para a criação.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'nome_solicitante' => $this->faker->name,
            'destino' => $this->faker->city,
            'data_ida' => $this->faker->dateTimeBetween('now', '+1 year'), 
            'data_volta' => $this->faker->dateTimeBetween('+1 year', '+2 years'),
            'status' => $this->faker->randomElement(['solicitado', 'aprovado', 'cancelado']),
            'email' => $this->faker->safeEmail
        ];
    }
}