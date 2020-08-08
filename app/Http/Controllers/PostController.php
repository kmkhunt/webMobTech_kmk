<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Validator;
use Session;
use Redirect;
use DB;
use Request as Input;
use Datatables;
use App\Post;
use Auth;


class PostController extends Controller {

  public function __construct(){
    //parent::__construct();
  }
	  
  public function index(Request $request) {

	$query = Post::query();
	if($request->get('created_date') != ""){
		$query->where('created_at','=',date('Y-m-d',strtotime($request->get('created_date'))));
	}
	return Datatables::of($query)
		->addColumn('post_image', function ($row) { 
		return '<img src="'.config('post_image_url_100X100').$row->post_image.'" border="0" width="40" class="img-rounded" align="center" />';
		})->addColumn('created_at', function ($row) { 
			return date('d-M-Y',strtotime($row->created_at));
		})->addColumn('action', function ($row) {
			return '<a href="'.route('post_edit',[$row->id]).'" class="btn btn-primary">Edit</a>';
		})->rawColumns(['action' => 'action','post_image' => 'post_image'])
		->make(true);
   }

   public function list() {
	return view('post/list');
	}

	public function add(Request $request){
		$arr_form_data  = Input::all();
		$arr_form_data = empty($arr_form_data) ? array("post"=>array()) : $arr_form_data;
		extract($arr_form_data["post"]);
		
		$cat_id = isset($cat_id) ? $cat_id : ""; 
		$arr_form_data["selected_cat_id"] = $cat_id; 
		$arr_form_data["arr_cat_id_list"] = $this->getDBSelectPluckList("categories",["id","category_name"]);
		
		if($request->isMethod("get")) {
			return view("post.add")->with("arr_form_data", $arr_form_data);
		}
		
		$arr_validation = $this->PostValidation();
		$validator = validator::make($arr_form_data, $arr_validation["rule"], $arr_validation["messages"]);
		if($validator->fails()) {
			return view("post.add")->withErrors($validator)->with("arr_form_data", $arr_form_data);
		}

		$arr_form_data["post"]["create_thumb"]["post_image"][config('post_image_root_100X100')] ="100,100";
		$arr_form_data["post"]["post_image_image_root"] = config("post_image_root");
		doUpload($arr_form_data,"post",$_FILES);
		$arr_form_data["post"]["user_id"] = auth()->user()->id;
		$post = Post::create($arr_form_data["post"]);

		$arr_slug["post_slug"] = str_replace(" ","-",strtolower($arr_form_data["post"]["post_name"]."-".$post->id));
		$result = Post::where("id",$post->id)->update($arr_slug);

		if($post->id > 0){
			Session::flash("success", "Post is successfully added");
			return redirect::route("home");
		}

	}
	
	public function edit(Request $request){
		$arr_form_data  = Input::all();
		$update_id	= $request->id;
		
		if(empty($arr_form_data)){
			$arr_form_data["post"]	=	$this->getDBSelectListSingle("posts","*",array("id"=>$update_id));
			extract($arr_form_data["post"]);
		}
		extract($arr_form_data["post"]);
		
		$cat_id = isset($cat_id) ? $cat_id : ""; 
		$arr_form_data["selected_cat_id"] = $cat_id; 
		$arr_form_data["arr_cat_id_list"] = $this->getDBSelectPluckList("categories",["id","category_name"]);
			
		$arr_form_data["update_id"] = $update_id;
		
		if($request->isMethod("get")) {
			return view("post.add",compact("arr_form_data"));
		}
		
		$arr_validation = $this->PostValidation($update_id);
		$validator = validator::make($arr_form_data, $arr_validation["rule"], $arr_validation["messages"]);
		if($validator->fails()) {
			return view("post.add")->withErrors($validator)->with("arr_form_data", $arr_form_data);
		}
		
		$arr_form_data["post"]["create_thumb"]["post_image"][config('post_image_root_100X100')] ="100,100";
		$arr_form_data["post"]["post_image_image_root"] = config("post_image_root");
		doUpload($arr_form_data,"post",$_FILES);
		
		$arr_form_data["post"]["user_id"] = auth()->user()->id;
		$arr_form_data["post"]["post_slug"] = str_replace(" ","-",strtolower($arr_form_data["post"]["post_name"]."-".$update_id));
		$result = Post::where("id",$update_id)->update($arr_form_data["post"]);

		Session::flash("success", "Post is successfully updated");
		return redirect::route("home");				
	}
	
	
	function PostValidation($update_id = NULL){

		$arr_post_data  = Input::all();
		$arr_form_data  = $arr_post_data["post"];

		$arr_validation["rule"] = array(
			"post.post_name" =>"required|unique:posts,post_name,$update_id,id",
			"post.cat_id" =>"required",
			"post.post_image" =>(empty($arr_post_data["post_image"])) ? "required|mimes:".config("image_mimes") : "",
			"post.post_description" =>"required"
		);

		$arr_validation["messages"] = array(
			"post.post_name.required" =>  "Please Enter Post Name",
			"post.post_name.unique" => "Post Name is allready exist",
			"post.cat_id.required" =>  "Please Select Category Name",
			"post.post_image.required" =>  "Please Select Post Image",
			"post.post_image.mimes" =>  "You are only allowed to upload ".config("image_mimes")." files only",
			"post.post_description.required" =>  "Please Enter Post Description"
		);

		return $arr_validation;
	}
	
}