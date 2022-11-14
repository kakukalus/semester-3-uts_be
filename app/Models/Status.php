<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    use HasFactory;
    protected $table = 'statuses';
    public $incrementing = true;
    protected $primaryKey = 'id_status';
    public $timestamps = false;

    public function patient()
    {
        return $this->hasOne('App\Models\Patients','id','id_status');
    }


}
