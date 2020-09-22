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

        ////hides current user from listing
        $this->crud->addClause('where', 'id', '!=', auth()->id());
        //hide guest from listing
		$this->crud->addClause('where', 'id', '!=', 1);

		//because the guestuser right now is id 1 in users table
		//$this->crud->addClause('where', 'id', '!=', 1);

    }

    protected function setupShowOperation()
    {
        $this->crud->denyAccess(['delete']);
        $this->crud->set('show.setFromDb', false);
        CRUD::column('email')->type('email');
        CRUD::column('name')->type('name');
        CRUD::column('institution')->type('name');

        CRUD::column('level')->type('boolean')->label('Admin privileges enabled');
        //$this->crud->setFromDb(); // columns
        $this->setupListOperation();

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
        $adminCheck = User::select('*')
			->where('level', 1)->count();
			//->selectRaw('count(*) as count')
			//->groupBy('level')->get();
		//dump($adminCheck);

		//this way leaves open the possibility of admin loading two pages
		//and de-admining the two last admins. Solution to fix this would
		//probably be to implement a custom save action and do the check there

		$currentUserId = auth()->id();

		if((int)$param == $currentUserId){
			echo '<script>window.location.replace("'. backpack_url('users') .'")</script>';
		}

		if($adminCheck == 1 && $paramQuery[0]->level != 0){
			$fields[] = [   // CustomHTML
				'name'      => 'separator',
				'type'      => 'custom_html',
				'value'     => '<p><b>This is the last admin. Cannot have privileges removed.</b></p>',
			];
		}
		else{
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
		}
		$this->crud->addField('name');
		$this->crud->addField('institution');
        $this->crud->addFields($fields);

        $this->crud->removeSaveActions(['save_and_new', 'save_and_edit', 'save_and_preview']);


    }


}
