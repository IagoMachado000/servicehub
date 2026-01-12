<?php

use App\Jobs\TicketProcessingJob;
use App\Models\Ticket;
use App\Models\TicketDetail;
use App\Models\User;
use App\Notifications\TicketProcessedNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('deve processar ticket com anexo JSON e atualizar detalhes', function () {
    // Arrange
    Notification::fake();
    Storage::fake('private');

    $user = User::factory()->create();
    $ticket = Ticket::factory()->create([
        'user_id' => $user->id,
        'status' => 'pending',
        'attachment_path' => 'tickets/data.json'
    ]);

    // Criamos o detalhe inicial que o Job irá atualizar
    TicketDetail::factory()->create(['ticket_id' => $ticket->id]);

    // Criamos um arquivo JSON falso no storage
    $jsonContent = json_encode(['error' => 'timeout', 'message' => 'Falha crítica no servidor']);
    Storage::put('tickets/data.json', $jsonContent);

    // Act
    // Executamos o handle diretamente para testar a lógica síncrona
    $job = new TicketProcessingJob($ticket);
    $job->handle();

    // Assert
    $ticket->refresh(); // Atualiza a instância com os dados do banco

    // 1. Verifica se o status mudou
    expect($ticket->status)->toBe('open');

    // 2. Verifica se os dados "inteligentes" foram salvos
    expect($ticket->detail->summary)->not->toBeEmpty()
        ->and($ticket->detail->keywords)->toContain('timeout', 'servidor')
        ->and($ticket->detail->sentiment_score)->toBeLessThan(0) // Texto negativo
        ->and($ticket->detail->processed_at)->not->toBeNull();

    // 3. Verifica se a notificação foi enviada ao usuário correto
    Notification::assertSentTo(
        $user,
        TicketProcessedNotification::class,
        function ($notification) use ($ticket) {
            return $notification->ticket->id === $ticket->id;
        }
    );
});

test('deve processar ticket sem anexo usando título e descrição', function () {
    // Arrange
    Notification::fake();

    $user = User::factory()->create();
    $ticket = Ticket::factory()->create([
        'user_id' => $user->id,
        'title'   => 'O servidor está apresentando instabilidade',
        'attachment_path' => null,
    ]);

    TicketDetail::factory()->create([
        'ticket_id'   => $ticket->id,
        'description' => 'O sistema está muito bom e rápido.'
    ]);

    // Act
    (new TicketProcessingJob($ticket))->handle();

    // Assert
    $ticket->refresh();

    expect($ticket->detail->keywords)->toContain('servidor')
        ->and($ticket->detail->sentiment_score)->toBeGreaterThan(0); // Texto positivo
});

test('deve marcar palavra-chave como geral se nenhum termo técnico for encontrado', function () {
    // Arrange
    Notification::fake();
    $ticket = Ticket::factory()->create(['title' => 'Abacaxi Azul']);
    TicketDetail::factory()->create(['ticket_id' => $ticket->id, 'description' => 'Sem termos técnicos aqui.']);

    // Act
    (new TicketProcessingJob($ticket))->handle();

    // Assert
    $ticket->refresh();
    expect($ticket->detail->keywords)->toBe(['geral']);
});
