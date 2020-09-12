<?php

namespace App\Http\Controllers\Admin;



use App\Http\Requests\UserRequest;
use App\User;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use \App\Http\Controllers\Admin\CustomOperations\ResetPasswordOperation;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Route;
use Prologue\Alerts\Facades\Alert;


/**
 * Class MethodsCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class UsersCrudController extends CrudController
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
        CRUD::setModel(User::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/users');
        CRUD::setEntityNameStrings('users', 'users');
        $this->crud->addButtonFromView('line', 'reset', 'reset', 'beginning');


    }


    public function reset()
    {
        //do some if checking on whether id actually exists, error out if not
        $param = Route::current()->parameter('id');
        $emailQuery = User::select('email')->where('id', $param)->get();
        $email = $emailQuery[0]->email;
        Password::broker()->sendResetLink(['email'=>$email]);

        Alert::success('Password reset email sent successfully.')->flash();
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
        //CRUD::setFromDb(); // columns

        CRUD::column('email')->type('email');
        CRUD::column('name')->type('name');
        $this->crud->denyAccess('operation');
        $this->crud->denyAccess(['create', 'delete']);
        $this->crud->allowAccess(['read', 'update']);
        //$this->crud->addButtonFromView('line', 'reset', 'reset', 'beginning');



        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * - CRUD::column('price')->type('number');
         * - CRUD::addColumn(['name' => 'price', 'type' => 'number']);
         */
    }

    protected function setupShowOperation()
    {
        $this->crud->setFromDb(); // columns
        //$this->setupListOperation();


        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * - CRUD::column('price')->type('number');
         * - CRUD::addColumn(['name' => 'price', 'type' => 'number']);
         */
    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(UserRequest::class);

        CRUD::setFromDb(); // fields

        /**
         * Fields can be defined using the fluent syntax or array syntax:
         * - CRUD::field('price')->type('number');
         * - CRUD::addField(['name' => 'price', 'type' => 'number']));
         */
    }

    /**
     * Define what happens when the Update operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {

        //$this->setupCreateOperation();
        $param = Route::current()->parameter('id');
        $paramQuery = User::select('level')->where('id', $param)->get();

        if($paramQuery[0]->level != 0){
            $fields[] = [   // CustomHTML
                'name'      => 'separator',
                'type'      => 'custom_html',
                'value'     => '<p>User current status: <b>Administrator</b></p>',
            ];
        }
        else{
            $fields[] = [   // CustomHTML
                'name'      => 'separator',
                'type'      => 'custom_html',
                'value'     => '<p>User current status: <b>Non-administrator</b></p>',
            ];
        }


        $fields[] = [
            'name' => 'level',
            'label' => 'Enable administrator access',
            'type' => 'checkbox',
            'value' => $paramQuery[0]->level,


        ];

        $this->crud->addFields($fields);
        $this->crud->removeSaveActions(['save_and_new', 'save_and_edit']);


    }


}
