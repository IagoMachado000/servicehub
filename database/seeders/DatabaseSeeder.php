<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Project;
use App\Models\Ticket;
use App\Models\TicketDetail;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Cria 3 Empresas
        Company::factory(3)->create()->each(function ($company) {

            // Cada empresa tem 3 Projetos
            Project::factory(3)->for($company)->create()->each(function ($project) {

                // Cada projeto tem 3 Usuários com Perfil
                $projectUsers = User::factory(3)
                    ->has(UserProfile::factory(), 'profile')
                    ->create();

                // Cria entre 5 e 15 Tickets por projeto
                for ($i = 0; $i < rand(5, 15); $i++) {
                    $status = fake()->randomElement(['open', 'pending', 'failed']);

                    $ticket = Ticket::factory()
                        ->for($project)
                        ->recycle($projectUsers)
                        ->create(['status' => $status]);

                    // Ajusta o detalhe baseado no status do ticket
                    $detailFactory = TicketDetail::factory()->for($ticket);

                    if ($status === 'pending' || $status === 'failed') {
                        $detailFactory->pending()->create();
                    } else {
                        $detailFactory->create();
                    }
                }
            });
        });
    }
}
