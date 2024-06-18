<?php
namespace App\Jobs;

use App\Mail\ShareSurvey;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendSurveyEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $usuario;
    protected $encuesta;

    public function __construct($usuario, $encuesta)
    {
        $this->usuario = $usuario;
        $this->encuesta = $encuesta;
    }

    public function handle()
    {
        Mail::send('emails.share', ['encuesta' => $this->encuesta], function ($message) {
            $message->to($this->usuario->correoElectronico)
                ->subject('Se ha compartido una encuesta contigo');
        });
    }
}