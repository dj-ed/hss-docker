<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ProcessCalculatePopularNews implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var $news
     */
    protected $news;

    /**
     * Create a new job instance.
     *
     * @param $news
     * @return void
     */
    public function __construct($news)
    {
        $this->news = $news;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach ($this->news as $item) {
            $popular = ($item->comments->count() * 10) + ($item->likes->count() * 5) + ($item->reviews->count() * 1);

            $item->update(['popular' => $popular]);
        }
    }
}
