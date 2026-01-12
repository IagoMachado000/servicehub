<?php

namespace App\Services;

use App\Jobs\TicketProcessingJob;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class TicketService
{
    /**
     * Cria o Ticket, salva o anexo e cria o detalhe inicial.
     */
    public function createTicket(User $user, array $data, ?UploadedFile $file = null): Ticket
    {
        return DB::transaction(function () use ($user, $data, $file) {

            $path = null;

            // Lógica de Upload
            if ($file) {
                // Salva em storage/app/private/tickets (por segurança, já que contém dados sensíveis)
                $path = $file->store('tickets');
            }

            // 1. Criar o Ticket

            $ticket = Ticket::create([
                'project_id'      => $data['project_id'],
                'user_id'         => $user->id,
                'title'           => $data['title'],
                'status'          => 'pending',
                'attachment_path' => $path,
            ]);

            // 2. Criar o Detalhe (1:1)
            $ticket->detail()->create([
                'description' => $data['description'] ?? null,
            ]);

            TicketProcessingJob::dispatch($ticket);

            return $ticket;
        });
    }
}
