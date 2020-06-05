<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

class UserModel extends Eloquent
{
    public $incrementing = true;
    public $timestamps = false;

    protected $table = "users";
    protected $primaryKey = "id";
    protected $keyType = "integer";
}