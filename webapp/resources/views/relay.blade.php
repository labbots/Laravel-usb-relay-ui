@extends('layouts.app',['section_class' => 'section-relay','body_class' => 'body-relay'])

@section('content')
@if(Auth::user()->hasRole(['administrator','user']))

<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="refresh-container">
            <a class="button-click pull-right" id="relay-refresh" href="#" role="button"><span>Refresh</span></a>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="switch-content tristate-content">
            <div class="tristate-checkbox-container">
                <div class="tristate-switch">
                    <span class="switch-led switch-led-green">
                        <span class="switch-led-border">
                            <span class="switch-led-light">
                                <span class="switch-led-glow">
                                </span>
                            </span>
                        </span>
                    </span>
                  <input id="maintoggle" type="checkbox" {{!isset($status) ? '' : (($status['all'] == 'ON') ? 'checked' : ($status['all'] == 'OFF') ? '' : ($status['all'] == null) ? 'indeterminate' : '') }}  
                  {{ isset($active) ? $active : '' }}/>
                  <span class="state" data-state="on"></span>
                  <span class="state" data-state="neutral"></span>
                  <span class="state" data-state="off"></span>
                  <label for="maintoggle" class="indicator"></label>
                    <span class="switch-led switch-led-red">
                        <span class="switch-led-border">
                            <span class="switch-led-light">
                                <span class="switch-led-glow">
                                </span>
                            </span>
                        </span>
                    </span>
                </div>
            </div>
            <span class="tristate-checkbox-title">
                <p>
                    Master Switch
                </p>
            </span>                      
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="switch-content">
            @for($i = 1; $i <= $relay_channels; $i++)
@if($i % 5 === 0)
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="switch-content">
            @endif
            <span class="switch">
                <span class="switch-border1">
                    <span class="switch-border2">
                        <input {{!isset($status) ? '' : (($status[$i] == 'ON') ? 'checked' : '') }} {{ isset($active) ? $active : '' }} id="switch-{{$i}}" name="switch-{{$i}}" type="checkbox"/>
                        <label for="switch-{{$i}}">
                        </label>
                        <span class="switch-top">
                        </span>
                        <span class="switch-shadow">
                        </span>
                        <span class="switch-handle">
                        </span>
                        <span class="switch-handle-left">
                        </span>
                        <span class="switch-handle-right">
                        </span>
                        <span class="switch-handle-top">
                        </span>
                        <span class="switch-handle-bottom">
                        </span>
                        <span class="switch-handle-base">
                        </span>
                        <span class="switch-led switch-led-green">
                            <span class="switch-led-border">
                                <span class="switch-led-light">
                                    <span class="switch-led-glow">
                                    </span>
                                </span>
                            </span>
                        </span>
                        <span class="switch-led switch-led-red">
                            <span class="switch-led-border">
                                <span class="switch-led-light">
                                    <span class="switch-led-glow">
                                    </span>
                                </span>
                            </span>
                        </span>
                        <span class="switch-title">
                            <p>
                                {{$relay_names[$i]}}
                            </p>
                        </span>
                    </span>
                </span>
            </span>
            @endfor
        </div>
    </div>
</div>
@endif
@endsection
@push('page_css')
<link href="/css/tristate-checkbox.css" rel="stylesheet"/>
@endpush
@push('page_js')
    <script type="text/javascript" src="/js/tristate-checkbox.js"></script>
    <script type="text/javascript">
        $(function() {

             var mainToggle = $("#maintoggle").triStateCheckbox().data("plugin_triStateCheckbox");
             // Function to trigger relay change.
             var currentStatus = null;
             mainToggle.setChangeHook("click", function(){
                    if(currentStatus != mainToggle.getCheckboxState()){
                        currentStatus = mainToggle.getCheckboxState();
                        console.log(currentStatus);
                        if(currentStatus == 'on')
                         postRelay('all',true);
                        if(currentStatus == 'off')
                         postRelay('all',false);
                    }
            });
             //function to change on/off led colour switch.
             mainToggle.setChangeHook("click", function(){
                    var currentStatus = mainToggle.getCheckboxState();

                    $('.tristate-switch').attr('class','tristate-switch').addClass(currentStatus);
            });

             function connectionError(message){

                if(!message){
                    message = 'Connection problem occured. Please check all data are fine or try again later.';
                }
                $.createSmallModal({
                            title:'Error',
                            message: message,
                            scrollable:false
                        });
            }

            function postRelay(relay,status,elem = null){
                $.ajax({
                    url: "/set_relay",
                    type: "POST",
                    data: {relay:relay,status: status},
                    success: function (data, textStatus, jqXHR) {
                        checkSwitchStatus(data.data);
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
						if(relay != 'all'){
							toggle(elem);
						}else{
							mainToggle.setCheckboxState();
						}
                        var json = $.parseJSON(jqXHR.responseText);
                        var message = null;
                        if(json.message){
                            message = json.message;
                        }
                        connectionError(message);
                    }
                });
            }

            function getRelayStatus(){
                $.ajax({
                    url: "/get_relay_status",
                    type: "GET",
                    success: function (data, textStatus, jqXHR) {
                        checkSwitchStatus(data.data);
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        var json = $.parseJSON(jqXHR.responseText);
                        var message = null;
                        if(json.message){
                            message = json.message;
                        }
                        connectionError(message);
                    }
                });
            }

            function checkSwitchStatus(arr){
                $.each( arr, function( index, value ){
					if(index != 'all'){
						var state = $("input[name=switch-"+index+"]").prop("checked");
						if(value == "ON"){
							state = true;
						}else{
							state = false;
						}
						$("input[name=switch-"+index+"]").prop("checked",state);
					}else{
						var state = mainToggle.getCheckboxState();
						
						 if(value == null){
							state = null;
						}else{
							state = value.toLowerCase();
						}
						mainToggle.setCheckboxState(state);
						
					}
                });
 
            }

            function toggle(elem){
                if (elem.prop('checked')) elem.prop('checked',false)
                else elem.prop('checked',true)
            }

            $("input[name^=switch-]").click(function(){
                var $this   = $(this);
                var switch_id = $this.attr('id');
                var relay = switch_id.split('-');
                postRelay(relay[1],$this.prop('checked'),$this);
            })

            $( "#relay-refresh" ).on( "click", function(e) {
                e.preventDefault();
                getRelayStatus();
            });


            //setTimeout(getRelayStatus,60000);
        });
    </script>
@endpush
