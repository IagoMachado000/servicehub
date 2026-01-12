<?php

namespace App\Http\Controllers;

use App\Http\Resources\TicketResource;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(Request $request): Response
    {
        $user = $request->user();

        // 1. Calcular Estatísticas
        $totalTickets = Ticket::where('user_id', $user->id)->count();
        $pendingTickets = Ticket::where('user_id', $user->id)->where('status', 'pending')->count();
        $openTickets = Ticket::where('user_id', $user->id)->where('status', 'open')->count();
        $failedTickets = Ticket::where('user_id', $user->id)->where('status', 'failed')->count();

        // 2. Buscar Tickets Recentes (com Paginação ou Limite)
        // Carregamos 'project' para mostrar o nome do projeto na tabela
        $recentTickets = TicketResource::collection(
            Ticket::with('project')->where('user_id', $user->id)->latest()->limit(5)->get()
        );

        return Inertia::render('Dashboard', [
            'stats' => [
                'total' => $totalTickets,
                'pending' => $pendingTickets,
                'open' => $openTickets,
                'failed' => $failedTickets,
            ],
            'recentTickets' => $recentTickets,
        ]);
    }
}
