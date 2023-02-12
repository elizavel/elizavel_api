<?php

namespace App\Http\Controllers\AppControllers;

use App\Http\Controllers\Controller;
use App\Models\AppModels\AppUser;
use Illuminate\Http\Request;

class AccessController extends Controller
{
    private $appUserModel;
    private $appUserLogController;
    const MODULE = 'APP_ACCESS';

    public function __construct()
    {
        $this->appUserModel = new AppUser();
        $this->appUserLogController = new AppUserLogController();
    }

    private function generateToken(){

        $expiration = date('Y-m-d H:i:s',strtotime('60 minute'));
        $data = [
            'expiration'=>$expiration
        ]; 
        return base64_encode(json_encode($data));
    }
    

    public function userLogin(Request $request)
    {
        if(!$this->appUserModel->emailExists($request->input('email'))){
            $returnMessage = [
                'success' => 0,
                'message' => 'Email does not exists'
            ];
            return json_encode($returnMessage);
        }

        $checkCredentials = $this->appUserModel->validCredential($request->input('email'),$request->input('password'));
        if($checkCredentials['valid']){
             
            $userId = $checkCredentials['user']->id;
            $token = $this->generateToken(); 
            $dataToUpdate = [ 
                'token' => $token
            ];  
            $user_data = $this->appUserModel->updateRecord($dataToUpdate,$userId); 


            $logData = [
                'user_id'=>$user_data->id,
                'module'=>$this::MODULE,
                'activity'=>'login',
                'description'=>'login',
                'payload'=>json_encode($request->all())
            ];
            
            $this->appUserLogController->createRecordAppUserLog($logData);
            $returnMessage = [
                'success' => 1,
                'message' => 'Welcome back!',
                'user_data'=> $user_data
            ];
            return json_encode($returnMessage);

        }else{
            $returnMessage = [
                'success' => 0,
                'message' => 'Incorrect password'
            ];
            return json_encode($returnMessage);
        }

        
    }


}
