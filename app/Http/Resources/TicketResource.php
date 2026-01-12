<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'project' => $this->project->name ?? 'N/A',
            'status' => $this->status,
            'created_at_formatted' => $this->created_at->format('d/m/Y H:i'),
            'opener' => [
                'name' => $this->user->name,
                'job_title' => $this->user->profile?->job_title ?? 'Não informado',
                'phone' => $this->user->profile?->phone ?? 'Não informado',
            ],
        ];
    }
}
