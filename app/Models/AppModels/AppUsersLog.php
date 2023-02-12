<?php

namespace App\Models\AppModels;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppUsersLog extends Model
{
    use HasFactory;

    protected $fillable = ['id'];

    public function createRecord($data)
    {
        return $this::create($data)->id;
    }

    public function retrieveRecord($id)
    {
        return $this::where('id',$id)->first();
    }

    public function updateRecord($data,$id)
    {
        $this::where('id',$id)->update($data);
        return $this->retrieveAppUser($id);
    }

    public function deleteRecord($id)
    {
       $this::where('id',$id)->delete();
    }
}
