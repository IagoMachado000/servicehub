<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Project;
use App\Models\Ticket;
use App\Models\TicketDetail;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Cria empresas e projetos base
        // Criamos 3 empresas, cada uma com 3 projetos.
        // Isso evita criar 1 empresa nova para cada Ticket gerado depois.
        $companies = Company::factory(3)
            ->has(Project::factory()->count(3)) // Cada empresa tem 3 projetos
            ->create();

        // Recupera todos os projetos criados para distribuir os tickets entre eles
        $projects = Project::all();

        // ---------------------------------------------------------
        // 2. O SEU USUÁRIO (Cenário de Teste Principal)
        // ---------------------------------------------------------
        $myUser = User::factory()
            ->has(UserProfile::factory(), 'profile') // Cria o perfil junto
            ->create([
                'name' => 'Admin Developer',
                'email' => 'admin@example.com', // Login fácil para dev
                'password' => bcrypt('password'),
            ]);

        // Cria Tickets variados para o SEU usuário
        foreach ($projects as $project) {
            // Cria 2 tickets normais em cada projeto para você
            Ticket::factory(2)
                ->for($myUser)   // Pertence a você
                ->for($project)  // Pertence a um projeto existente
                ->has(TicketDetail::factory(), 'detail') // Com detalhe padrão
                ->create();

            // Cria 1 ticket COM ANEXO e PROCESSADO (Cenário completo)
            Ticket::factory()
                ->for($myUser)
                ->for($project)
                ->withAttachment() // State do TicketFactory
                ->has(
                    TicketDetail::factory()->processed(),
                    'detail' // State do TicketDetailFactory
                )
                ->create(['title' => 'Ticket Processado Completo - ' . $project->name]);
        }

        // ---------------------------------------------------------
        // 3. POPULAÇÃO GERAL (Outros usuários para volume)
        // ---------------------------------------------------------
        User::factory(10) // 10 outros usuários aleatórios
            ->has(UserProfile::factory(), 'profile')
            ->create()
            ->each(function ($user) use ($projects) {
                // Cada usuário aleatório ganha 1 ou 2 tickets em projetos aleatórios
                Ticket::factory(rand(1, 2))
                    ->for($user)
                    ->for($projects->random()) // Associa a um projeto existente aleatório
                    ->has(TicketDetail::factory(), 'detail')
                    ->create();
            });

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
