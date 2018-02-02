<?php

namespace App\Console\Commands;

use App\Jobs\ProcessCalculatePopularNews;
use App\Models\News;
use Illuminate\Console\Command;

class CalculatePopularNews extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cron:calculate-popular-news';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run calculate popular field in DB news';

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
        $query = News::where(['status' => News::STATUS_APPROVED]);
        $partLimit = 500;
        $total = $query->count();
        $parts = $total / $partLimit;
        for ($i = 0; $i < $parts; $i++) {
            $offset = $i * $partLimit;
            $newsData = $query->offset($offset)->limit($partLimit)->get();

            dispatch(new ProcessCalculatePopularNews($newsData));
        }
    }
}
