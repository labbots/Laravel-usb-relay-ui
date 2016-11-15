<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Validator;
use Config;
use mikehaertl\shellcommand\Command as shellCommand;

class SetRelayCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'relay:set 
							{--o|on= : Relay number to turn on. This can be comma seperated values.} 
							{--f|off= : Relay number to turn off. This can be comma seperated values.}
							{--d|delay= : Set delay in seconds for automatic turn off of switched on relay.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to turn on/off connected relay device.';
	
	protected $relayCommand = "sainsmartrelay";
	protected $relayChannels;
	protected $relayNames;
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
         $this->relayChannels = Config::get('relay.relay_channels');
        $this->relayNames    = Config::get('relay.relay_names');
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $input = $this->option();
        if(!$input['on'] && !$input['off']){
			$this->error('No parameters passed.');
		}
		 $regRelay = $this->relayChannels - 1;
		$rules = [
            'on'  => ["regex:/^([1-{$this->relayChannels}])(,[1-{$this->relayChannels}]){0,{$regRelay}}$|^all$/i"],
            'off' => ["regex:/^([1-{$this->relayChannels}])(,[1-{$this->relayChannels}]){0,{$regRelay}}$|^all$/i"],
            'delay' => ["integer","min:10","max:300"],
        ];
        
        $validator = Validator::make($input, $rules)->setAttributeNames([
            'on'  => 'On',
            'off' => 'Off',
        ]);
        
        if ($validator->fails()) {
			foreach($validator->messages()->all() as $msg){
			  $this->error($msg);
			}
			 return 0;
        } else {
            $this->makeCommandInstance();

            if (isset($input['on'])) {
                $this->command->addArg('--on', $input['on']);
            }
            if (isset($input['off'])) {
                $this->command->addArg('--off', $input['off']);
            }
            
            $output = $this->executeCommand($this->command);
            if(!$output){
				return 1;
			}
            if(isset($input['delay']) && isset($input['on'])){
				$this->line('Delay triggered.');
				$bar = $this->output->createProgressBar($input['delay']);
				for($i = 0 ; $i < $input['delay']; $i++){
					sleep(1);
					$bar->advance();
				}
				$bar->finish();
				$this->line('');
				$this->makeCommandInstance();
				$this->command->addArg('--off', $input['on']);
				  $output = $this->executeCommand($this->command);
				  if(!$output){
					return 1;
			       }
            }
            return 0;
		}
    }
    
    private function executeCommand(shellCommand $command){
		 if ($command->execute()) {
                $output   = $command->getOutput();
                $output   = $this->processOutput($output);
                
                foreach($output as $key => $value){
					if(is_numeric($key)){
						$this->info($this->relayNames[$key].' : '.$value);
					}else{
						$this->info($key.' : '.$value);
					}
				}
				return true;
            } else {
                 $this->error($command->getError());
                 return false;
            }
	}
     private function makeCommandInstance()
    {
        $this->command = new shellCommand("sudo " . $this->relayCommand);
    }
    
    
       private function processOutput($string)
    {

        $response = [];
        $array    = explode("\n", $string);
        foreach ($array as $a) {
            $e               = array_map('trim', explode(':', $a));
            $response[$e[0]] = $e[1];
        }

        $allStatus = null;

        if (count(array_unique($response)) === 1) {
            $response['all'] = current($response);
        } else {
            $response['all'] = null;
        }
        return $response;
    }
}
