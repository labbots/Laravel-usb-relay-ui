@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-sm-12">
        <a class="btn btn-primary pull-right" href="/users/add">
            Create new user
        </a>
    </div>
</div>
<br/>
<div class="row">
    <div class="col-sm-12">
        {!! $dataTable->table(['class' => 'table table-striped']) !!}
    </div>
</div>
@endsection
@push('page_css')
    <link href="//cdn.datatables.net/1.10.7/css/jquery.dataTables.min.css" rel="stylesheet"/>
    <link href="//cdn.datatables.net/buttons/1.0.3/css/buttons.dataTables.min.css" rel="stylesheet"/>
    <link href="/css/datatables.bootstrap.css" rel="stylesheet"/>
@endpush
@push('page_js')
    <script src="//cdn.datatables.net/1.10.7/js/jquery.dataTables.min.js">
    </script>
    <script src="//cdn.datatables.net/buttons/1.0.3/js/dataTables.buttons.min.js">
    </script>
    <script src="/vendor/datatables/buttons.server-side.js">
    </script>
    {!! $dataTable->scripts() !!}
    <script type="text/javascript">
        $(function() {
                window.LaravelDataTables.dataTableBuilder.on( 'draw.dt', function () {
                    $.positionFooter();
                    $('button[name="delete_user"]').on('click', function(e){
                        var $form=$(this).closest('form'); 
                        e.preventDefault();
                        $.createConfirm({
                                        title:'Delete confirm',
                                        message: 'Are you sure?',
                                        scrollable:false
                                    });
                        $('#confirm-modal')
                            .one('click', '#delete', function() {
                                $form.trigger('submit'); // submit the form
                            });
                    // .one() is NOT a typo of .on()
                    });
                });
            });
    </script>
@endpush
