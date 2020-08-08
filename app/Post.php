<?php
namespace App;
use Illuminate\Database\Eloquent\Model;	

class Post extends Model{
	
	protected $guarded    = [];
	protected $table 	  = "posts"; 
	protected $primaryKey = "id";
}
?>