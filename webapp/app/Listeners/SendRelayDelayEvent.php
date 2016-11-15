<?php

namespace App\Listeners;

use App\Events\RelayControl;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use mikehaertl\shellcommand\Command;

class SendRelayDelayEvent implements ShouldQueue
{
    use InteractsWithQueue;

    protected $command;

    protected $relayCommand = "sainsmartrelay";
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        
    }

    /**
     * Handle the event.
     *
     * @param  RelayControl  $event
     * @return void
     */
    public function handle(RelayControl $event)
    {
         $this->makeCommandInstance();

         if(isset($event->delay)){
            sleep($event->delay);
         }
            if (isset($event->onState)) {
                $this->command->addArg('--off', $event->onState);
            }

            if ($this->command->execute()) {
                return true;
            } else {
                return false;
            }
    }

    private function makeCommandInstance()
    {
        $this->command = new Command("sudo " . $this->relayCommand);
    }
}
