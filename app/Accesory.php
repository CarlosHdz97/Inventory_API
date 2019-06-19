<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
class Accesory extends Model{
    protected $table = 'accesory';
    protected $fillable = [
        'name', 'existencia', 'stockMin', 'stockMax'
    ];
}