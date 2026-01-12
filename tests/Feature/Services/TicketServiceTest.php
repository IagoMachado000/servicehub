<?php

use App\Models\Project;
use App\Models\User;
use App\Services\TicketService;
use App\Jobs\TicketProcessingJob;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Bus;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->service = new TicketService();
});

test('deve criar um ticket com sucesso, salvar anexo e disparar job', function () {
    // Arrange (Preparação)
    Storage::fake('local'); // Simula o disco padrão
    Bus::fake();            // Simula a fila de jobs

    $user = User::factory()->create();
    $project = Project::factory()->create();
    $file = UploadedFile::fake()->image('documento.pdf');

    $data = [
        'project_id'  => $project->id,
        'title'       => 'Erro no sistema de login',
        'description' => 'Não consigo acessar com minha senha resetada.'
    ];

    // Act (Ação)
    $ticket = $this->service->createTicket($user, $data, $file);

    // Assert (Verificações)

    // 1. Verificar se o ticket foi salvo no banco
    $this->assertDatabaseHas('tickets', [
        'id'         => $ticket->id,
        'title'      => 'Erro no sistema de login',
        'status'     => 'pending',
        'user_id'    => $user->id,
        'project_id' => $project->id,
    ]);

    // 2. Verificar se o detalhe (1:1) foi criado
    $this->assertDatabaseHas('ticket_details', [
        'ticket_id'   => $ticket->id,
        'description' => 'Não consigo acessar com minha senha resetada.',
    ]);

    // 3. Verificar se o arquivo físico existe no storage
    expect($ticket->attachment_path)->not->toBeNull();
    Storage::disk('local')->assertExists($ticket->attachment_path);

    // 4. Verificar se o Job foi enviado para a fila
    Bus::assertDispatched(TicketProcessingJob::class);
});

test('deve criar ticket sem anexo quando nenhum arquivo é enviado', function () {
    // Arrange
    Bus::fake();
    $user = User::factory()->create();
    $project = Project::factory()->create();

    $data = [
        'project_id'  => $project->id,
        'title'       => 'Ticket sem anexo',
        'description' => 'Apenas uma dúvida.'
    ];

    // Act
    $ticket = $this->service->createTicket($user, $data, null);

    // Assert
    expect($ticket->attachment_path)->toBeNull();
    $this->assertDatabaseHas('tickets', ['title' => 'Ticket sem anexo']);
});
