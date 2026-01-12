<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTicketRequest;
use App\Http\Resources\TicketResource;
use App\Models\Project;
use App\Models\Ticket;
use App\Services\TicketService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class TicketController extends Controller
{
    public function __construct(
        protected TicketService $ticketService
    ) {}

    public function index()
    {
        // Busca os tickets do usuário logado, com relacionamentos necessários
        // Ordena pelos mais recentes e pagina 10 por vez
        $tickets = TicketResource::collection(
            Ticket::with('project')->where('user_id', Auth::id())->latest()->paginate(10)
        );

        return Inertia::render('Tickets/Index', [
            'tickets' => $tickets
        ]);
    }

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
        try {
            $this->ticketService->createTicket(
                user: $request->user(),
                data: $request->validated(),
                file: $request->file('attachment')
            );

            return to_route('dashboard')
                ->with('message', 'Ticket criado. Em breve nossa equipe te responderá.');
        } catch (\Throwable $e) {
            return back()
                ->with('error', 'Houve um erro ao criar o ticket. Tente novamente.');
        }
    }

    public function show(Ticket $ticket): Response
    {
        // Garante que o usuário só veja seus próprios tickets (Segurança básica)
        if ($ticket->user_id !== Auth::id()) {
            abort(403, 'Você não tem permissão para ver este ticket.');
        }

        // Carrega os detalhes e o projeto para exibir na tela
        $ticket->load(['detail', 'project']);

        return Inertia::render('Tickets/Show', [
            'ticket' => $ticket
        ]);
    }
}
