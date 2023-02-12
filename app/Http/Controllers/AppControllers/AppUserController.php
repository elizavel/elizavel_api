<?php

namespace App\Http\Controllers\AppControllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateAppUserRequest;
use App\Models\AppModels\AppUser;
use App\Models\AppModels\AppUsersLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AppUserController extends Controller
{

    CONST MODULE = 'APP_USER';
    private $appUserModel;
    private $appUserLogController;

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

    public function createRecord(Request $request)
    { 

        if($this->appUserModel->emailExists($request->input('email'))){
            $returnMessage = [
                'success'=>0,
                'message'=>'Email already exists'
            ];
            return json_encode($returnMessage);
        }

        $token = $this->generateToken(); 
        $this->appUserModel->email = $request->input('email');
        $this->appUserModel->name = $request->input('name');
        $this->appUserModel->password = $request->input('password');
        $this->appUserModel->token = $token;
        $this->appUserModel->save();
        $userId = $this->appUserModel->id;  
 
        $logData = [
            'user_id'=>$userId,
            'module'=>$this::MODULE,
            'activity'=>'create',
            'description'=>'create',
            'payload'=>json_encode($request->all())
        ];
        
        $this->appUserLogController->createRecordAppUserLog($logData);
        
        $user_data = $this->appUserModel->retrieveRecord($userId);

        $returnMessage = [
            'success'=>1,
            'message'=>'Registration successful!',
            'user_data'=>$user_data
        ];
        return json_encode($returnMessage);
    }

    public function updateRecord(Request $request)
    {  
        $userId = $request->id;
        $token = $this->generateToken(); 
        $dataToUpdate = [
            'email' => $request->input('email'),
            'name' => $request->input('name'),
            'password' => $request->input('password')
        ]; 
        $this->appUserModel->token = $token;
        $user_data = $this->appUserModel->updateRecord($dataToUpdate,$userId); 
 
        $logData = [
            'user_id'=>$userId,
            'module'=>$this::MODULE,
            'activity'=>'update',
            'description'=>'update',
            'payload'=>json_encode($request->all())
        ];
        
        $this->appUserLogController->createRecordAppUserLog($logData); 

        $returnMessage = [
            'success'=>1,
            'message'=>'Update successful!',
            'user_data'=>$user_data
        ];
        return json_encode($returnMessage);
    }

    public function deleteRecord(Request $request)
    {  
        $userId = $request->id; 
        $this->appUserModel->deleteRecord($userId); 
 
        $logData = [
            'user_id'=>$userId,
            'module'=>$this::MODULE,
            'activity'=>'delete',
            'description'=>'delete',
            'payload'=>json_encode($request->all())
        ]; 
        $this->appUserLogController->createRecordAppUserLog($logData); 

        $returnMessage = [
            'success'=>1,
            'message'=>'Record deleted!', 
        ];
        return json_encode($returnMessage);
    }

    



    

    
}
