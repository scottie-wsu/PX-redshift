<?php

namespace App\Http\Controllers\Admin\CustomOperations;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Password;

trait ResetPasswordOperation
{
    protected function setupResetPasswordRoutes($segment, $routeName, $controller)
    {
        Route::get($segment.'/{id}/ResetPassword', [
            'as'        => $routeName.'.ResetPassword',
            'uses'      => $controller.'@ResetPassword',
            'operation' => 'ResetPassword',
        ]);
    }

    protected function setupResetPasswordDefaults(){
        $this->crud->allowAccess('ResetPassword');
        $this->crud->operation('list', function(){
            $this->crud->addButton('line', 'ResetPassword', 'view', 'buttons.ResetPassword', 'beginning');
        });
    }

    public function ResetPassword($user){
        //$this->crud->setOperation('Reset Password');
        $email = User::where('email', $user)->first();
        Password::sendResetLink($email);
    }
}
