@extends('email.canary')

@section('content')

    @include ('email.canary.heading' , [
        'heading' => 'Dear '.$user->name.',',
        'level' => 'h1',
    ])

    @include('email.canary.contentStart')

        <p>Click the button below to reset your password.</p>

    @include('email.canary.contentEnd')
    
    @include('email.canary.button', [
            'title' => 'Password Reset',
            'link' => url('password/reset', $token).'?email='.urlencode($user->getEmailForPasswordReset())
    ])

    @include('email.canary.contentStart')

      <br/>
      If you didn't request a reset, there is no need to take any action.<br/><br/>
      Thanks,<br/>
      Autodata.

    @include('email.canary.contentEnd')

@stop