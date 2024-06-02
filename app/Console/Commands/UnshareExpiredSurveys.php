<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Encuesta;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class UnshareExpiredSurveys extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'surveys:unshare-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Unshare surveys that have passed their expiration date';

    /**
     * Execute the console command.
     *
     * @return int
     */

    public function handle()
    {
        Log::info('Running the surveys:unshare-expired command');

        // Get all shared surveys
        $encuestas = Encuesta::where('compartida', true)->get();

        Log::info('Found ' . $encuestas->count() . ' shared surveys');

        foreach ($encuestas as $encuesta) {
            // If the survey's expiration date has passed, unshare it
            if (Carbon::now()->greaterThan($encuesta->fechaVencimiento)) {
                Log::info('Survey expiration date: ' . $encuesta->fechaVencimiento);

                $encuesta->compartida = false;
                $encuesta->compartida_con = null;
                $encuesta->save();

                Log::info('Unshared survey with ID ' . $encuesta->id);
            }
        }

        return 0;
    }
}