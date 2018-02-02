<?php

namespace App\Console\Commands;

use App\Events\LogEvents;
use App\Models\Event;
use App\Models\EventsLog;
use App\Models\Game;
use Carbon\Carbon;
use Illuminate\Console\Command;

class NotificationEvents extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cron:notification-events';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run notification events for users';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $events = Event::with('sport')->with('game')->where([
            'events.status' => 1,
            'model_type' => Game::class,
            'game.date' => Carbon::now()->format('Y-m-d')
        ])->leftJoin('game', 'game.id', '=', 'events.model_id')->get();

        foreach ($events as $event) {
            event(new LogEvents($event));
        }
    }
}
