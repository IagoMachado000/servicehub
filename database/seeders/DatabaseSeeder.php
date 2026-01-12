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
        // 1. Cria 3 Empresas
        Company::factory(3)->create()->each(function ($company) {

            // 2. Para cada empresa, cria 3 Projetos
            Project::factory(3)
                ->for($company)
                ->create()
                ->each(function ($project) {

                    // 3. Para cada projeto, cria 3 Usuários com Perfil
                    $projectUsers = User::factory(3)
                        ->has(UserProfile::factory(), 'profile')
                        ->create();

                    // 4. Para cada projeto, cria entre 5 e 15 Tickets
                    Ticket::factory(rand(5, 15))
                        ->for($project)
                        // Distribui os tickets entre os 3 usuários criados acima
                        ->recycle($projectUsers)
                        // Cria o detalhe para cada ticket
                        ->has(TicketDetail::factory(), 'detail')
                        ->create();
                });
        });
    }
}
