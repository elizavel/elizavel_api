<?php

namespace App\Models\AppModels;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppUser extends Model
{
    use HasFactory;

    protected $fillable =  ['id'];

    public function createRecord($data)
    {
        return $this::create($data)->id;
    }

    public function retrieveRecord($id)
    {
        return $this::where('id',$id)->select(['id','name','email','token'])->first();
    }

    public function updateRecord($data,$id)
    {
        $this::where('id',$id)->update($data);
        return $this->retrieveRecord($id);
    }

    public function deleteRecord($id)
    {
       $this::where('id',$id)->delete();
    }
    
    public function emailExists($email)
    {
        if($this::where('email',$email)->count() > 0){
            return true;
        }
    }

    public function validCredential($email,$password)
    {
        $res = $this::where('email',$email)->where('password',$password)->get();
        if(count($res) > 0){
            $data = [
                'valid'=>true,
                'user'=>$this->retrieveRecord($res[0]['id'])
            ];
        }
        else {
            $data = [
                'valid'=>false, 
            ];
        }

        return $data;
    }
}
