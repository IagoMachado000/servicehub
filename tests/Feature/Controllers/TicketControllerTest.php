<?php

use App\Models\Project;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Bus;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

// Garante que o utilizador está autenticado antes de cada teste de rota
beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
});

test('deve exibir a página de listagem de tickets', function () {
    $response = $this->get(route('tickets.index'));

    $response->assertStatus(200);
});

test('deve exibir a página de criação de ticket', function () {
    $response = $this->get(route('tickets.create'));

    $response->assertStatus(200);
});

test('deve criar um ticket via requisição POST e redirecionar', function () {
    // Arrange
    Storage::fake('private');
    Bus::fake();

    $project = Project::factory()->create();
    $file = UploadedFile::fake()->create('log.txt', 100);

    $payload = [
        'project_id'  => $project->id,
        'title'       => 'Erro na API de integração',
        'description' => 'A API retorna 500 ao enviar o payload X.',
        'attachment'  => $file, // Simula o upload no form
    ];

    // Act
    $response = $this->post(route('tickets.store'), $payload);

    // Assert
    // 1. Verifica redirecionamento (comum em controllers após store)
    $response->assertRedirect();

    // 2. Verifica se o ticket foi persistido no banco
    $this->assertDatabaseHas('tickets', [
        'title'   => 'Erro na API de integração',
        'user_id' => $this->user->id,
    ]);

    // 3. Verifica se o detalhe foi criado corretamente
    $ticket = Ticket::where('title', 'Erro na API de integração')->first();
    $this->assertDatabaseHas('ticket_details', [
        'ticket_id'   => $ticket->id,
        'description' => 'A API retorna 500 ao enviar o payload X.',
    ]);
});

test('não deve permitir acesso a visitantes não autenticados', function () {
    Auth::logout(); // Força logout para este teste específico

    $response = $this->get(route('tickets.index'));

    $response->assertRedirect(route('login'));
});

test('deve validar campos obrigatórios ao criar ticket', function () {
    $response = $this->post(route('tickets.store'), []);

    $response->assertSessionHasErrors(['project_id', 'title']);
});

test('deve exibir os detalhes de um ticket específico', function () {
    $ticket = Ticket::factory()->for($this->user)->create();

    $response = $this->get(route('tickets.show', $ticket));

    $response->assertStatus(200);
});
