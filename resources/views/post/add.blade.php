@extends('layouts.app')
@section("content")
<?php 
if(!empty($arr_form_data)){
    foreach($arr_form_data as $key=>$arr_data){
	if($key == "arr_form_data"){
		extract($arr_data[key($arr_data)]);
	}
	
	global $$key;
	$$key = $arr_data;
	if(is_array($arr_data)){
		extract($arr_data);
	}
    }
}

$shortNm = "Post";
$section_name = "Add ".$shortNm;
if(isset($update_id)){
	$section_name = "Edit ".$shortNm;
}
?>

<script src="<?php echo config('admin_js_validation_path').'/PostValidation.js'; ?>"></script>
			  <div class="right_col" role="main">
    <div class="">
      <div class="page-title">
        <div class="PageTitle">
        </div>
      </div>
      <div class="clearfix"></div>
      <div class="row">
        <div class="col-md-12 col-xs-12" style="margin-left:200px">
            <div class="x_title">
              <h2><span class="icon"><i class="fa fa-list-alt"></i></span><?php echo $section_name;?></h2>
              <div class="clearfix"></div>
            </div>
<div class="x_content"> <br />{!! Form::open(['class'=>'form-horizontal form-label-left input_mask','id'=>'frm_posts','enctype' =>'multipart/form-data','onsubmit'=>'return PostValidation()']) !!}
<div class="form-group">
<label class="control-label col-md-3 col-sm-3 col-xs-12">Post Name</label>
<div class="col-md-9 col-sm-9 col-xs-12 controls">
		{!! Form::text("post[post_name]",isset($post_name) ? stripslashes($post_name) : "",["id"=>"post_name","class"=>"form-control"]) !!}
<div class="serr_msg" id="post_name_derror_msg"> @if($errors->has("post.post_name")) {{ $errors->first("post.post_name") }} @endif </div></div>
</div>

<div class="form-group">
<label class="control-label col-md-3 col-sm-3 col-xs-12">Category Name</label>
<div class="col-md-9 col-sm-9 col-xs-12 controls">
{{Form::select('post[cat_id]',[null=>'Select'] +$arr_cat_id_list,$selected_cat_id,['id'=>'cat_id','class'=>'form-control']) }} <div class="serr_msg" id="cat_id_derror_msg"> @if($errors->has("post.cat_id")) {{ $errors->first("post.cat_id") }} @endif </div></div>
</div>

<div class="form-group">
	<label class="control-label col-md-3 col-sm-3 col-xs-12">Post Image</label>
	<div class="col-md-9 col-sm-9 col-xs-12 controls">
	<div class="left_file">
	<?php $post_image = isset($post_image) ? $post_image : ''; ?> 
			{!! Form::file('post[post_image]', array("id"=>"post_image","class"=>"form-control")) !!}
	<div class="serr_msg" id="post_image_derror_msg"> @if($errors->has("post.post_image")) {{ $errors->first("post.post_image") }} @endif </div>

	{!! Form::hidden("post_image",isset($post_image) ? $post_image : '',array("id"=>"hdn_post_image")) !!}
</div>
	@if($post_image !="")
	<span id="image_display_block_post_image">
	{!! Form::hidden("post[delete_image][old_post_image]",isset($post_image) ? $post_image : '',array("id"=>"old_post_image")) !!}
	<img class="display_image"style="margin-top:5px" src="<?php echo config('post_image_url_100X100').$post_image; ?>" />
	</span>
	@endif
	</div>
</div>

	<div class="form-group">
		<label class="control-label col-md-3 col-sm-3 col-xs-12">Post Description</label>
	<div class="col-md-9 col-sm-9 col-xs-12 controls">
	{!! Form::textarea("post[post_description]",isset($post_description) ? stripslashes($post_description) : "",["id"=>"post_description","class"=>"form-control"]) !!}
	

	<div class="serr_msg" id="post_description_derror_msg"> @if($errors->has("post.post_description")) {{ $errors->first("post.post_description") }} @endif </div></div>
	</div>

		</div>
		<div class="form-group">
				<div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3 formButton">
			{!! Form::hidden("update_id",isset($update_id) ? $update_id : '',['id'=>'update_id']) !!} 
		{!! Form::submit('Submit',['class' => 'btn_submit btn btn-primary','name'=>'btn_submit']) !!}
		<button type="button" class="btn_back  btn btn-success" onClick="event.preventDefault(); window.location = '{{ route('home') }}';">Back</button>

			</div>
		</div>
                </div>
		<input type="hidden" name="_token" value="{{ csrf_token() }}">
		{!! Form::close() !!}

		</div>
		</div>
	</div>
	</div>
</div>


<script>
	function PostValidation(){
		var is_form_have_to_submit = true;

		var obj_post_name = $("#post_name");
		var obj_cat_id = $("#cat_id");
		var obj_post_image = $("#post_image");
		var obj_post_description = $("#post_description");
		
		var obj_post_name_derror_msg = $("#post_name_derror_msg");
		var obj_cat_id_derror_msg = $("#cat_id_derror_msg");
		var obj_post_image_derror_msg = $("#post_image_derror_msg");
		var obj_post_description_derror_msg = $("#post_description_derror_msg");
		
		
		obj_post_name_derror_msg.html("");
		obj_cat_id_derror_msg.html("");
		obj_post_image_derror_msg.html("");
		obj_post_description_derror_msg.html("");
		
		if(obj_post_name.val() == ""){
			obj_post_name_derror_msg.html("Please Enter Post Name");
			is_form_have_to_submit = false;
		}

		if(obj_cat_id.val() == ""){
			obj_cat_id_derror_msg.html("Please Select Category Name");
			is_form_have_to_submit = false;
		}

		if(check_single_file_required(obj_post_image.get(0))){
			obj_post_image_derror_msg.html("Please Select Post Image");
			is_form_have_to_submit = false;
		}

		if(obj_post_image.val() != ""){
			valid_content_type  = ["image/gif", "image/jpeg", "image/jpg", "image/pjpeg","image/x-png","image/png"];
			is_valid_type = valid_content_type.indexOf(obj_post_image.get(0).files[0].type);
			if(is_valid_type == -1){
				obj_post_image_derror_msg.html("You are only allowed to upload jpg,jpeg,png,gif files only");
				is_form_have_to_submit = false;
			}

		}

		if(obj_post_description.val() == ""){
			obj_post_description_derror_msg.html("Please Enter Post Description");
			is_form_have_to_submit = false;
		}

		return is_form_have_to_submit;
	}
</script>
<script src="//cdn.ckeditor.com/4.14.0/standard/ckeditor.js"></script>
<script type="text/javascript">
	CKEDITOR.replace("post_description", {
	//skin: "kama",
	language: "en",
	toolbar:"Full"					
	});
	</script>
@endsection