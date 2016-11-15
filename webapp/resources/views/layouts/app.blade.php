<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8"/>
        <meta content="IE=edge" http-equiv="X-UA-Compatible"/>
        <meta content="width=device-width, initial-scale=1" name="viewport"/>
        <meta content="{{ csrf_token() }}" name="csrf-token"/>
        <title>
            Relay Interface
        </title>
        <!-- Fonts -->
        <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.1/css/font-awesome.min.css" rel="stylesheet"/>
        <link href="https://fonts.googleapis.com/css?family=Lato:100,300,400,700" rel="stylesheet" type="text/css"/>
        <!-- Styles -->
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet"/>
        <link href="/css/app.css" rel="stylesheet"/>
        <link href="/css/styles.css" rel="stylesheet"/>
        @stack('page_css')
    </head>
    <body id="app-layout"  class ="{{(isset($body_class)) ? $body_class : ''}}">
        <nav class="navbar navbar-default navbar-static-top">
            <div class="container">
                <div class="navbar-header">
                    <!-- Collapsed Hamburger -->
                    <button class="navbar-toggle collapsed" data-target="#app-navbar-collapse" data-toggle="collapse" type="button">
                        <span class="sr-only">
                            Toggle Navigation
                        </span>
                        <span class="icon-bar">
                        </span>
                        <span class="icon-bar">
                        </span>
                        <span class="icon-bar">
                        </span>
                    </button>
                    <!-- Branding Image -->
                    <a class="navbar-brand" href="{{{ (Auth::guest() ? url('/') : url('/relays')) }}} ">
                        <i aria-hidden="true" class="fa fa-fw fa-cogs">
                        </i>
                        Relay
                    </a>
                </div>
                <div class="collapse navbar-collapse" id="app-navbar-collapse">
                    <ul class="nav navbar-nav">
                        @include(config('laravel-menu.views.bootstrap-items'), array('items' => $LeftNavBar->roots()))
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                        @include(config('laravel-menu.views.bootstrap-items'), array('items' => $RightNavBar->roots()))
                    </ul>
                </div>
            </div>
        </nav>
        <section class="page-section {{(isset($section_class)) ? $section_class : ''}}">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12">
                        @if (count($errors) > 0)
                        <div class="alert alert-danger" role="alert">
                            <button aria-label="Close" class="close" data-dismiss="alert" type="button">
                                <span aria-hidden="true">
                                    ×
                                </span>
                            </button>
                            <i class="fa fa-fw fa-lg fa-exclamation-circle">
                            </i>
                            <strong>
                                Oops! There is something wrong here...
                            </strong>
                            <br/>
                            <br/>
                            <ul>
                                @foreach ($errors->all() as $error)
                                <li>
                                    {{ $error }}
                                </li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="container">
                @yield('content')
            </div>
        </section>
        <footer class="site-footer" id="site-footer">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12 text-muted">
                        <p>
                        </p>
                        <p>
                            ©2016 Relay Ltd.
                        </p>
                    </div>
                </div>
            </div>
        </footer>
        <!-- JavaScripts -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js">
        </script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js">
        </script>
        <script src="/js/app.js">
        </script>
		<script src="/js/modal.js"></script>
            <script type="text/javascript">
				$(function() {
                    $.positionFooter();
				});
    </script>
        @stack('page_js')
    </body>
</html>
