<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTicketRequest;
use App\Models\Project;
use App\Services\TicketService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class TicketController extends Controller
{
    public function __construct(
        protected TicketService $ticketService
    ) {}

    public function create(): Response
    {
        // Listagem dos projetos para o select no frontend
        // Idealmente filtrar apenas projetos da Company do usuário, mas faremos geral por enquanto
        return Inertia::render('Tickets/Create', [
            'projects' => Project::select('id', 'name')->get()
        ]);
    }

    public function store(StoreTicketRequest $request): RedirectResponse
    {
        $this->ticketService->createTicket(
            user: $request->user(),
            data: $request->validated(),
            file: $request->file('attachment')
        );

        return to_route('tickets.create')
            ->with('message', 'Ticket criado com sucesso! O processamento iniciará em breve.');
    }
}
