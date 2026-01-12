<?php

namespace App\Http\Controllers;

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
        $recentTickets = Ticket::with('project:id,name')
            ->where('user_id', $user->id)
            ->latest() // Ordena por created_at desc
            ->limit(5)
            ->get()
            ->map(function ($ticket) {
                // Dados para o frontend (ViewModel simples)
                return [
                    'id' => $ticket->id,
                    'title' => $ticket->title,
                    'project' => $ticket->project->name ?? 'N/A',
                    'status' => $ticket->status,
                    'created_at_formatted' => $ticket->created_at->format('d/m/Y H:i'),
                ];
            });

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
