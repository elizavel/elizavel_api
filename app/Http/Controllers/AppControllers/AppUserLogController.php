<?php

namespace App\Http\Controllers\AppControllers;

use App\Http\Controllers\Controller;
use App\Models\AppModels\AppUsersLog;
use Illuminate\Http\Request;

class AppUserLogController extends Controller
{
    private $appUsersLogModel;

    public function __construct()
    { 
        $this->appUsersLogModel = new AppUsersLog();
    }

    public function createRecordAppUserLog($data)
    {
        $this->appUsersLogModel->app_user_id = $data['user_id'];
        $this->appUsersLogModel->module = $data['module'];
        $this->appUsersLogModel->activity = $data['activity'];
        $this->appUsersLogModel->description = $data['description'];
        $this->appUsersLogModel->payload = $data['payload'];
        $this->appUsersLogModel->save();
    }
}
