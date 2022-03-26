<?php

namespace App\Console\Commands;

use App\Events\Game\RemainingTimeChanged;
use App\Events\Game\WinnerNumberGenerated;
use Illuminate\Console\Command;

class GameExecutor extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'game:execute';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Starts game execution';

    private int $time = 15;


    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        while (true) {
            broadcast(new RemainingTimeChanged($this->time . 's'));
            $this->time--;
            sleep(1);
            if ($this->time === 0) {
                broadcast(new RemainingTimeChanged('Esperando para empezar...'));
                broadcast(new WinnerNumberGenerated(mt_rand(1, 12)));
                sleep(5);
                $this->time = 15;
            }
        }
    }
}
