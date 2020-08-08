/*
==================================================== UPLOAD controll validation ===================================================================
*/

function addmore(ctrl_name)
{
	str_ctrl_order = "";
	if(document.getElementById("hdn_img_order_"+ctrl_name).value == "yes")
	{
		str_ctrl_order = '<input type="text" class="gallery_order" name="img_order[]" id="img_order[]">&nbsp';
	}
	
	var hdn_validation_method = document.getElementById("hdn_validation_method").value;
	var div_obj = document.getElementsByClassName(ctrl_name+"_cnt_lst_div");
	current = 0;
	
	if(div_obj.length > 0)
	{
		current = giveLastId(div_obj);
	}
	
	counter=current+1;
	str_ctrl_active = "";	  
	if(document.getElementById("hdn_img_active_"+ctrl_name).value == "yes")
	{
		str_ctrl_active = '<input type="checkbox"  class="cls_active_'+ctrl_name+'" name="img_active['+ctrl_name+']['+counter+']" id="img_active[]"><input type="hidden" class="cls_active" name="hdn_img_active['+ctrl_name+']['+counter+']"  id="hdn_img_active[]"/>&nbsp';
	}
	
  str = '<div class="gallery_raw '+ctrl_name+'_cnt_lst_div" id="'+ctrl_name+"_"+counter+'"><div class="gallery_col_file"><input type="file" '+hdn_validation_method+' value="" class="gallery_file" id="'+ctrl_name+counter+'" name="'+ctrl_name+'[]"></div><div class="gallery_col_order">'+str_ctrl_order+'</div><div class="gallery_col_active">'+str_ctrl_active+'</div><div class="gallery_col_delete"><a href="javascript:void(0)" onClick=javascript:deleteGalleryStaticImage("'+counter+'","'+ctrl_name+'")><img src="images/fileclose.png" width="20" height="20" border="0" alt="Remove" title="Remove"></a></div><span id="msg_'+ctrl_name+counter+'" class="gallery_status_error"></span></div>';
   // document.getElementById("counter").value = counter;
  if(current == 0)
	{
		document.getElementById(ctrl_name+"_"+counter).innerHTML = str;
	}
	else
	{
		document.getElementById(ctrl_name+"_"+current).insertAdjacentHTML("afterEnd",str);
	}
}

function giveLastId(div_obj)
{
	for(i=0;i<div_obj.length;i++)
	{
		last_div_id = div_obj[i].id;
	}
	arr = last_div_id.split("_");
	index = arr.length-1;
	last_id = arr[index];
	return parseInt(last_id);
}


function changeStatus_backup(request_url,tbl_name,update_field_name,status,where_field_name,where_field_value,checked_on,msg_id,additional_where){
	
	extra_where = "";
	if(typeof additional_where !='undefined'){
		extra_where = additional_where;
	}
	/* 
		checked on is for Y OR 1 vlaue
	*/
	
	if(typeof checked_on =='undefined' || checked_on == 1){
		if(status == true){
			str_status = 1;
		}else{
			str_status = 0;
		}
	}else{
		if(status == true){
			str_status = "Y";
		}else{
			str_status = "N";
		}
	}
	
	if(checked_on =='checked_from_cmb'){
		str_status = status;
	}
	
	//msg_id= "#msg_chk_status_change_"+pk_id;
	var post_data = 
	{
		"mode": "change_status",
		"tbl_name": tbl_name,
		"update_field_name":  update_field_name,
		"update_field_value": str_status,
		"where_field_name":   where_field_name,
		"where_field_value":  where_field_value,
		"additional_where":   extra_where
	}
	$.post(request_url, post_data, function(response){
		if(response.success == 1) {
			$("#"+msg_id).addClass('cls_success');
			$("#"+msg_id).removeClass('cls_error');
			$("#"+msg_id).html(response.message).show().delay(300).fadeOut('slow', function() {
    			$("#"+msg_id).html("").show();
  			});
		}else{
			$("#"+msg_id).removeClass('cls_success');
			$("#"+msg_id).addClass('cls_error');
			$("#"+msg_id).html(response.errors);
		}
	},'json');
}

function changeStatus(request_url,status,where_field_value,checked_on,msg_id,additional_where){
	
	extra_where = "";
	if(typeof additional_where !='undefined'){
		extra_where = additional_where;
	}
	/* 
		checked on is for Y OR 1 vlaue
	*/
	
	if(typeof checked_on =='undefined' || checked_on == 1){
		if(status == true){
			str_status = 1;
		}else{
			str_status = 0;
		}
	}else{
		if(status == true){
			str_status = "Y";
		}else{
			str_status = "N";
		}
	}
	
	if(checked_on =='checked_from_cmb'){
		str_status = status;
	}
	
	//msg_id= "#msg_chk_status_change_"+pk_id;
	var post_data = 
	{
		"mode": "change_status",
		"tbl_name": tbl_name,
		"update_field_value": str_status,
		"where_field_value":  where_field_value,
		"additional_where":   extra_where
	}
	$.post(request_url, post_data, function(response){
		if(response.success == 1) {
			$("#"+msg_id).addClass('cls_success');
			$("#"+msg_id).removeClass('cls_error');
			$("#"+msg_id).html(response.message).show().delay(300).fadeOut('slow', function() {
    			$("#"+msg_id).html("").show();
  			});
		}else{
			$("#"+msg_id).removeClass('cls_success');
			$("#"+msg_id).addClass('cls_error');
			$("#"+msg_id).html(response.errors);
		}
	},'json');
}


function deleteImage_backup(request_url,tbl_name,delete_image_name,update_field_name,where_field_name,where_field_value,str_delete_image_path,msg_id)
{
/*	
	var did 	  = $("#confirm_"+where_field_value).data("id");
	var type 	  = $("#confirm_"+where_field_value).data("type");
	var msg 	  = $("#confirm_"+where_field_value).data("msg");
	var specialid = $("#confirm_"+where_field_value).data("specialid");

	var clicked = function(){
		$.fallr('hide');
*/		
		var post_data = 
		{
			"mode": "delete_update_image",
			"tbl_name": tbl_name,
			"delete_image_name" : delete_image_name,
			"update_field_name" : update_field_name,
			"update_field_value" : '',
			"where_field_name"  : where_field_name,
			"where_field_value": where_field_value,
			"str_delete_image_path": str_delete_image_path,
		}
		//post_data = $(frmid).serialize();
		$.post(request_url, post_data, function(response){
			if(response.success == 1) {
				$("#"+msg_id).addClass('cls_success');
				$("#"+msg_id).removeClass('cls_error');
				$("#hdn_"+ctrl_name).val('');
				$("#"+msg_id).html(response.message).show().delay(300).fadeOut('slow', function() {
					$("#"+msg_id).html("").show();
				});
			}else{
			$("#"+msg_id).removeClass('cls_success');
			$("#"+msg_id).addClass('cls_error');
			$("#"+msg_id).html(response.errors);
		}
	},'json');	

/*	};


	$.fallr('show', {
		buttons : {
			button1 : {text: 'Yes', danger: true, onclick: clicked},
			button2 : {text: 'Cancel', onclick: function(){$.fallr('hide')}}
		},
		content : msg,
		icon    : 'error'
	});
*/
}

function deleteImage(request_url,where_field_value,str_delete_image_path,msg_id,ctrl_name)
{

		var post_data = 
		{
			"where_field_value": where_field_value,
			"str_delete_image_path": str_delete_image_path,
		}
		//post_data = $(frmid).serialize();
		$.post(request_url, post_data, function(response){
			if(response.success == 1) {
				$("#"+msg_id).addClass('cls_success');
				$("#"+msg_id).removeClass('cls_error');
				$("#hdn_"+ctrl_name).val('');
				$("#"+msg_id).html(response.message).show().delay(300).fadeOut('slow', function() {
					$("#"+msg_id).html("").show();
				});
			}else{
			$("#"+msg_id).removeClass('cls_success');
			$("#"+msg_id).addClass('cls_error');
			$("#"+msg_id).html(response.errors);
		}
	},'json');	

}

function deleteGalleryStaticImage(counter_id,control_name)
{
	div_obj = document.getElementById(control_name+"_"+counter_id);
	div_obj.innerHTML = "";
	if(document.getElementsByClassName("cnt_lst_div").length != 1)
	{
		div_obj.counter_id = "";
		div_obj.className = "";
	}
}

function deleteGalleryImage(request_url,tbl_name,delete_image_name,where_field_name,where_field_value,str_delete_image_path,counter_id,control_name,msg_id)
{
/*	
	var did 	  = $("#confirm_"+where_field_value).data("id");
	var type 	  = $("#confirm_"+where_field_value).data("type");
	var msg 	  = $("#confirm_"+where_field_value).data("msg");
	var specialid = $("#confirm_"+where_field_value).data("specialid");

	var clicked = function(){
		$.fallr('hide');
*/		
		var post_data = 
		{
			"mode": "delete_gallery_image",
			"tbl_name": tbl_name,
			"delete_image_name" : delete_image_name,
			"where_field_name": where_field_name,
			"where_field_value": where_field_value,
			"str_delete_image_path": str_delete_image_path,
		}
		//post_data = $(frmid).serialize();
		$.post(request_url, post_data, function(response){
			if(response.success == 1) {
				$("#"+msg_id).addClass('cls_success');
				$("#"+msg_id).removeClass('cls_error');
				$("#"+msg_id).html(response.message).show().delay(300).fadeOut('slow', function() {
					$("#"+msg_id).html("").show();
					div_obj = document.getElementById(control_name+"_"+counter_id);
					div_obj.innerHTML = "";
					if(document.getElementsByClassName("cnt_lst_div").length != 1)
					{
						div_obj.counter_id = "";
						div_obj.className = "";
					}
				});
				hdn_dbrow_cnt = document.getElementById("hdn_dbrow_cnt_"+control_name).value ;
				document.getElementById("hdn_dbrow_cnt_"+control_name).value = parseInt(hdn_dbrow_cnt)-1;
			}else{
			$("#"+msg_id).removeClass('cls_success');
			$("#"+msg_id).addClass('cls_error');
			$("#"+msg_id).html(response.errors);
		}
	},'json');	

/*	};


	$.fallr('show', {
		buttons : {
			button1 : {text: 'Yes', danger: true, onclick: clicked},
			button2 : {text: 'Cancel', onclick: function(){$.fallr('hide')}}
		},
		content : msg,
		icon    : 'error'
	});
*/
}

function deleteRec(request_url,tbl_name,where_field_name,where_field_value,redirect_url)
{
			var did = $("#confirm_"+where_field_value).data("id");
			var type = $("#confirm_"+where_field_value).data("type");
			var msg = $("#confirm_"+where_field_value).data("msg");
			var specialid = $("#confirm_"+where_field_value).data("specialid");
		
			var clicked = function(){
				$.fallr('hide');
				
			var post_data = 
			{
				"tbl_name": tbl_name,
				"where_field_name": where_field_name,
				"where_field_value": where_field_value,
				"delete_mode": "delete"
			}
			
			//post_data = $(frmid).serialize();
			$.post(request_url, post_data, function(response){
				if(response == 1) {
					location.href=redirect_url;
				}
			});
			};
	
			$.fallr('show', {
				buttons : {
					button1 : {text: 'Yes', danger: true, onclick: clicked},
					button2 : {text: 'Cancel', onclick: function(){$.fallr('hide')}}
				},
				content : msg,
				icon    : 'error'
			});
}

function updateOrder(request_url,tbl_name,order_field,pk_field_name,frm_id,additional_where) {
		
		extra_where = "";
		if(typeof additional_where !='undefined'){
			extra_where = "&additional_where="+additional_where;
		}
		
		form_data = $("#"+frm_id).serialize();
		post_data = form_data+"&tbl_name="+tbl_name+"&order_field="+order_field+"&pk_field_name="+pk_field_name+"&order_status=1"+extra_where;
		//alert(additional_where);
		
		$.post(request_url, post_data, function(response){
			if(response.success == 1) {
				$("#msg_update_order_status_change").html(response.message).show().delay(300).fadeOut();
				$("#msg_update_order_status_change").addClass('success_msg');
			}else{
				$("#msg_update_order_status_change").html(response.errors);
				$("#msg_update_order_status_change").addClass('errr_msg');
			}
		},'json');
	}
	
function get_ajax_dropdown(request_url,response_id,tbl_name,value_field,caption_field,where_field_name,where_field_value,extra_where){
	var post_data = 
	{
		"mode": "ajax_dropdown",
		"tbl_name": tbl_name,
		"value_field": value_field,
		"caption_field": caption_field,
		"where_field_name": where_field_name,
		"where_field_value": where_field_value,
		"additional_where":   extra_where
	}
			
	$.post(request_url, post_data, function(response){
		if(response.success == 1) {
			$("#"+response_id).html(response.dropdown_options_html);
		}else{
			$("#"+response_id).addClass('errr_msg');
			$("#"+response_id).append(response.errors);
		}
	},'json');
}