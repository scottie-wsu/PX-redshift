<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\MethodsRequest;
use App\Models\Methods;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Support\Facades\Route;
use mysql_xdevapi\Exception;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Prologue\Alerts\Facades\Alert;
use App\Http\Requests\MethodsCreateRequest as MethodsCreateRequest;





/**
 * Class MethodsCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class MethodsCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    //use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Methods::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/methods');
        CRUD::setEntityNameStrings('methods', 'methods');
		$this->crud->addButtonFromView('line', 'softDelete', 'softDelete', 'end');

	}

	public function softDelete()
	{

		$param = Route::current()->parameter('id');
		Methods::where('method_id', $param)->update(['removed' => 1]);
		Alert::success('Method soft deleted.')->flash();
		return back();
	}

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
		CRUD::column('method_name')->type('text');
		CRUD::column('method_description')->type('text');
		CRUD::column('python_script_path')->type('text');

		$this->crud->addClause('where', 'removed', '=', 0);

		//dump($count);

    }

    protected function setupShowOperation()
    {

        $this->crud->set('show.setFromDb', false);

        $this->crud->addColumn(
            [
            'name' => 'method_name',
            'label' => 'Method name',
            'type' => 'text',
            ]
        );
        $this->crud->addColumn(
            [
                'name' => 'method_description',
                'label' => 'Method description',
                'type' => 'text',
            ]
        );
        $this->crud->addColumn(
            [
                'name' => 'python_script_path',
                'label' => 'Path',
                'type' => 'closure',
                'function' => function($entry){
                    return 'storage/app/public/' .$entry->python_script_path;
                }
            ]
        );
        $this->crud->addColumn(
            [
                'name' => 'created_at',
                'label' => 'Created at',
                'type' => 'closure',
                'function' => function($entry) {
                    if ($entry->created_at) {
						$sqlDate = strtotime($entry->created_at);
						$newDate = date("g:i:sA, jS M Y", $sqlDate);
						return $newDate;
                    }
                }
            ]
        );

        $this->crud->addColumn(
            [
                'name' => 'updated_at',
                'label' => 'Last updated at',
                'type' => 'closure',
                'function' => function($entry){
					if ($entry->updated_at != $entry->created_at) {
						$sqlDate = strtotime($entry->updated_at);
						$newDate = date("g:i:sA, jS M Y", $sqlDate);
						return $newDate;
					}
                }
            ]
        );



    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
		//this will go to the Requests/MethodsRequest.php file, which will in turn call the Rules/PyScript.php file that checks file ext
		CRUD::setValidation(MethodsCreateRequest::class);

        $fields[] = [
            'name' => 'python_script_path',
            'label' => 'Select python file to add.',
            'type' => 'upload',
            'upload' => true,
            'disk' => 'public',

        ];

        $this->crud->addFields($fields);
        $this->crud->addField('method_name');
        $this->crud->addField('method_description');
        $this->crud->removeSaveActions(['save_and_new', 'save_and_edit', 'save_and_preview']);

    }

    /**
     * Define what happens when the Update operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
    	//this will go to the Requests/MethodsRequest.php file, which will in turn call the Rules/PyScript.php file that checks file ext
		CRUD::setValidation(MethodsRequest::class);

		$fields[] = [
            'name' => 'python_script_path',
            'label' => 'Select python file to add.',
            'type' => 'upload',
            'upload' => true,
            'disk' => 'public',
		];

        $this->crud->addFields($fields);
        $this->crud->addField('method_name');
        $this->crud->addField('method_description');
		$this->crud->removeSaveActions(['save_and_new', 'save_and_edit', 'save_and_preview']);



    }
}
