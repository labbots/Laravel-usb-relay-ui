<?php

namespace App\Http\Controllers;

use Config;
use Exception;
use Illuminate\Http\Request;
use mikehaertl\shellcommand\Command;
use Validator;
use App\Events\RelayControl;
use Event;

class RelayController extends Controller
{
    protected $command;
    protected $relayCommand = "sainsmartrelay";
    protected $relayChannels;
    protected $relayNames;
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->relayChannels = Config::get('relay.relay_channels');
        $this->relayNames    = Config::get('relay.relay_names');
        $this->command       = new Command("sudo " . $this->relayCommand);
    }

    /**
     * Show the relay dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $view = view('relay');
        $view->with('relay_channels', $this->relayChannels);
        $view->with('relay_names', $this->relayNames);
        try {
            $status = $this->relayStatus();
            $view->with('status', $status);

        } catch (Exception $e) {
            $view->with('active', 'disabled');
            $view->withErrors(['errors' => $e->getMessage()]);
        }
        return $view;
    }

    public function postRelay(Request $request)
    {
        $input           = $request->all();
        $input['status'] = filter_var($input['status'], FILTER_VALIDATE_BOOLEAN);
        $rules           = [
            'relay'  => ['required'],
            'status' => ['required', 'boolean'],
        ];

        if (isset($input['relay']) && filter_var($input['relay'], FILTER_VALIDATE_INT)) {
            $rules['relay'] = array_merge($rules['relay'], ['integer', 'between:1,' . $this->relayChannels]);
        } else {
            $rules['relay'] = array_merge($rules['relay'], ['string', 'in:all']);
        }
        $validator = Validator::make($input, $rules)->setAttributeNames([
            'relay'  => 'Relay',
            'status' => 'Status',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages(), 400);
        } else {
            $response = [];

            $this->makeCommandInstance();

            $args = ($input['status']) ? '--on' : '--off';

            $this->command->addArg($args, $input['relay']);
            if ($this->command->execute()) {
                $output             = $this->command->getOutput();
                $response['status'] = '200';
                $response['data']   = $this->processOutput($output);
            } else {
                $response['status']  = '500';
                $response['message'] = $this->command->getError();
            }
            return response()->json($response, $response['status']);
        }
    }

    public function getRelayStatus(Request $request)
    {
        $response = [];
        try {
            $response['status'] = 200;
            $response['data']   = $this->relayStatus();

        } catch (Exception $e) {
            $response['status']  = 500;
            $response['message'] = $e->getMessage();
        }
        return response()->json($response, $response['status']);
    }

    public function setRelay(Request $request)
    {
        $input = $request->all();

        $regRelay = $this->relayChannels - 1;

        if(empty($input)){
            $message[] = "No parameters passed";
            return response()->json($message, 400);
        }
        $rules = [
            'on'  => ["regex:/^([1-{$this->relayChannels}])(,[1-{$this->relayChannels}]){0,{$regRelay}}$|^all$/i"],
            'off' => ["regex:/^([1-{$this->relayChannels}])(,[1-{$this->relayChannels}]){0,{$regRelay}}$|^all$/i"],
            'delay' => ["integer","min:10"],
        ];

        $validator = Validator::make($input, $rules)->setAttributeNames([
            'on'  => 'On',
            'off' => 'Off',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages(), 400);
        } else {
            $response = [];

            $this->makeCommandInstance();

            if (isset($input['on'])) {
                $this->command->addArg('--on', $input['on']);
            }
            if (isset($input['off'])) {
                $this->command->addArg('--off', $input['off']);
            }

            if ($this->command->execute()) {
                $output             = $this->command->getOutput();
                $response['status'] = '200';
                $response['data']   = $this->processOutput($output);
            } else {
                $response['status']  = '500';
                $response['message'] = $this->command->getError();
            }
            if(isset($input['delay']) && isset($input['on'])){
                Event::fire(new RelayControl($input['on'],null,$input['delay']));
            }
            return response()->json($response, $response['status']);
        }
    }
    /**
     * Get relayStatus
     */
    private function relayStatus()
    {
        $this->makeCommandInstance();
        $this->command->addArg('--status', 'all');
        if ($this->command->execute()) {
            $output = $this->command->getOutput();
            $output = $this->processOutput($output);
            return $output;
        } else {
            throw new Exception(trim($this->command->getError()));
        }

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

        //dd(count(array_unique($response)));

        if (count(array_unique($response)) === 1) {
            $response['all'] = current($response);
        } else {
            $response['all'] = null;
        }
        return $response;
    }

    /**
     * Turn on relay
     */
    private function relayOn($relayNumber)
    {
        $this->makeCommandInstance();
        if (is_array($relayNumber)) {
            $relayNumber = implode(',', $relayNumber);
        }
        $this->command->addArg('--on', $relayNumber);
        if ($this->command->execute()) {
            $output = $this->command->getOutput();

            $output = $this->processOutput($output);
            return $output;
        } else {
            throw new Exception(trim($this->command->getError()));
        }
    }

    /**
     * Turn off relay
     */
    private function relayOff($relayNumber)
    {
        $this->makeCommandInstance();
        if (is_array($relayNumber)) {
            $relayNumber = implode(',', $relayNumber);
        }
        $this->command->addArg('--off', $relayNumber);
        if ($this->command->execute()) {
            $output = $this->command->getOutput();

            $output = $this->processOutput($output);
            return $output;
        } else {
            throw new Exception(trim($this->command->getError()));
        }
    }

    private function makeCommandInstance()
    {
        $this->command = new Command("sudo " . $this->relayCommand);
    }
}
