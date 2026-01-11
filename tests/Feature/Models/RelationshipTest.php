<?php

use App\Models\Company;
use App\Models\Project;
use App\Models\Ticket;
use App\Models\TicketDetail;
use App\Models\User;
use App\Models\UserProfile;

test('company has many projects', function () {
    $company = Company::factory()->create();
    $project = Project::factory()->for($company)->create();

    expect($company->projects)->toHaveCount(1)
        ->and($company->projects->first()->id)->toBe($project->id);
});

test('user has one profile', function () {
    $user = User::factory()->create();
    $profile = UserProfile::factory()->for($user)->create();

    expect($user->profile->id)->toBe($profile->id);
});

test('ticket belongs to a project and user', function () {
    $project = Project::factory()->create();
    $user = User::factory()->create();
    $ticket = Ticket::factory()->for($project)->for($user)->create();

    expect($ticket->project->id)->toBe($project->id)
        ->and($ticket->user->id)->toBe($user->id);
});

test('ticket has one detail', function () {
    $ticket = Ticket::factory()->create();
    $detail = TicketDetail::factory()->for($ticket)->create();

    expect($ticket->detail->id)->toBe($detail->id);
});
