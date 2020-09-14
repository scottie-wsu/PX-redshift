<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\MethodsRequest;
use App\Models\Methods;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Support\Facades\Route;


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
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
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
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {

        $count = Methods::count();

        for($i=0;$i<$count;$i++){
            $query = Methods::select('*')->where('method_id', $i+1)->first();
            //use first instead of get so no need to extract array from object
            $savedPath = $query->python_script_path;
            $explodedSavedPath = explode("/",$savedPath);
            //splitting the saved path on the slash so we only rename the file not the path
            if($query->method_name != $explodedSavedPath[1]){
                $query->python_script_path = "scripts/" . $query->method_name . "/" . $query->method_name .".py";
                $query->save();
            }
        }


        CRUD::column('method_name')->type('text');
        //CRUD::column('python_script_path')->type('file');
        CRUD::column('method_description')->type('text');
        CRUD::column('python_script_path')->type('text');

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
                        return $entry->created_at;
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
                    if($entry->updated_at > $entry->created_at){
                        return $entry->updated_at;

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

    /**
     * Define what happens when the Update operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
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
        //$this->crud->addSaveAction([
            //'name' => 'save_method',
            //'visible' => function ($crud) {
            //    return $crud->hasAccess('list');
            //},
            //'redirect' => function ($crud, $request, $itemId = null) {

               //return $request->has('http_referrer') ? $request->get('http_referrer') : $crud->route;
            //},
            //'button_text' => 'Save changes',
        //]);
        $this->crud->removeSaveActions(['save_and_new', 'save_and_edit', 'save_and_preview']);



    }
}
