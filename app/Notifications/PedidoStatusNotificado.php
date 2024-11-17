<?php
namespace App\Notifications;

use App\Models\Pedido;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class PedidoStatusNotificado extends Notification
{
    use Queueable;

    public $status;
    public $solicitanteNome;
    public $pedido;

    /**
     * Criar uma nova notificação.
     *
     * @param string $status
     * @param string $solicitanteNome
     */
    public function __construct(string $status, Pedido $pedido)
    {
        $this->status = $status;
        $this->solicitanteNome = $pedido->nome_solicitante;
    }

    /**
     * Determina quais canais a notificação deve usar.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Notifique o usuário via SMS, email ou banco de dados.
     *
     * @param  mixed  $notifiable
     * @return void
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('Status do Pedido Atualizado')
                    ->line('O status do seu pedido de viagem foi atualizado para: ' . $this->status)
                    ->line('Solicitante: ' . $this->solicitanteNome);
    }

        /**
     * Configurar o canal de banco de dados, caso você queira armazenar no banco também.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toDatabase($notifiable)
    {
        return [
            'pedido_id' => $this->pedido->id,
            'status' => $this->status,
        ];
    }

}
