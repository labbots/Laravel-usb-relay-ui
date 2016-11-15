<?php

namespace App\DataTables;

use Yajra\Datatables\Services\DataTable;
use App\Models\User;
use Carbon\Carbon;
use Log;

class UserDataTable extends DataTable
{
    /**
     * Display ajax response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        $request = $this->request();
        return $this->datatables
            ->eloquent($this->query())
              ->addColumn('action', function ($user) {
                 return '<form action="/users/delete" method="POST">
                                            '.csrf_field().'
                                            <input type="text" name="id" class="form-control hidden" value="'.$user->id .'" readonly>
                                            <button type="submit" name="delete_user" class="btn btn-sm btn-danger pull-right"><i class="fa fa-fw fa-lg fa-trash-o"></i></button>
                                        </form>
                                        <a href="/users/update/'.$user->id.'" class="btn btn-sm btn-info pull-right"><i class="fa fa-fw fa-lg fa-pencil"></i></i></a>';
            })
              ->editColumn('role.name', function ($user) {
                    if($user->role->name== 'Administrator'){
                                        return '<td><span class="label label-danger">'.$user->role->name .'</span></td>';
                                    }else{
                                        return '<td><span class="label label-default">'.$user->role->name .'</span></td>';
                                    }
            })
            ->make(true);
    }

    /**
     * Get the query object to be processed by datatables.
     *
     * @return \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $user = User::query()->with('role');
        return $this->applyScopes($user);
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\Datatables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
                    ->addColumn(['width' => '20px','data' => 'id', 'name' => 'id', 'title' => 'Id'])
                    ->addColumn(['data' => 'name', 'name' => 'name', 'title' => 'Name'])
                    ->addColumn(['data' => 'email', 'name' => 'email', 'title' => 'Email'])
                    ->addColumn(['data' => 'role.name', 'name' => 'role.name', 'title' => 'Role'])
                    ->addColumn(['data' => 'created_at', 'name' => 'created_at', 'title' => 'Created At'])
                    ->addColumn(['data' => 'updated_at', 'name' => 'updated_at', 'title' => 'Updated At'])
                    ->addAction(['width' => '80px','printable' => false])

                    ->ajax(['url' => url('/users')])
                    ->parameters([
                                //'dom' => 'Blfrtip',
                                'dom' => "<'row'<'col-xs-12'<'col-xs-6'Bf><'col-xs-6'lp>>r><'row'<'col-xs-12't>><'row'<'col-xs-12'<'col-xs-6'i><'col-xs-6'p>>>",
                                'buttons' => [ 'reset', 'reload'],
                                "paging"=> true,
                                "pageLength"=> 20,
                                "lengthMenu"=> [ 50, 75, 100 ]
                                ]);
    }

    /**
     * Get columns.
     *
     * @return array
     */
    private function getColumns()
    {
        return [
            'id',
            'name',
            'email', 
            'role_id',
            'created_at',
            'updated_at',
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'user';
    }

     protected function getAjaxResponseData()
    {
        $this->datatables->getRequest();

        $response = $this->ajax();
        $data     = $response->getData(true);

        return $data['data'];
    }
}
