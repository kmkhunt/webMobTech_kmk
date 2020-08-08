<?php


	function arrayValueBlankNot($data){
		$return = false;
		foreach($data as $key=>$value)
		{
			if($value != "")
			{
				$return = true;
				break;
			}
		}
		return $return;
	}



  function varFromArray($arr_data){

		$arr_new_array = array();
		$tbl_name = key($arr_data);
		foreach($arr_data[$tbl_name] as $var_name_parent=>$var_val_parent){
			$arr_new_array[$var_name_parent] = $var_val_parent;
		}
		unset($arr_data[$tbl_name]);
		foreach($arr_data as $var_name_parent=>$var_val_parent){
			$arr_new_array[$var_name_parent] = $var_val_parent;
		}
		//echo "start<pre>"; print_r($arr_new_array); echo "end"; die;
		//echo $user_name_derror_msg;
		//extract($arr_data);
		return $arr_new_array;
	}

	function doUpload(&$data,$model_name="",$arr_files)
	{
		//echo "<pre> $model_name"; print_r($data); die;
		//echo "<pre>"; print_r($arr_files); die;

		if($model_name =="" || is_array($model_name))
		{
			$arr_extra = $model_name;
			$model_name = key($data);
		}

		if(!empty($arr_files)){
			//var_dump(arrayValueBlankNot($arr_files[$model_name]["name"])); die;
			if(arrayValueBlankNot($arr_files[$model_name]["name"]))
			{
				//echo "<pre>"; print_r($arr_files[$model_name]["name"]);
				foreach($arr_files[$model_name]["name"] as $key=>$value)
				{
					if($value!="")
					{
						//echo $key; die;
						//$arr_pathNfield = explode("-",$key);
						//$folder_name = $arr_pathNfield[0];
						//$field_name  = $arr_pathNfield[1];

						$folder_name = $data[$model_name][$key."_image_root"];
						$field_name  = $key;
						//echo $key;
						//echo $folder_name; die;
						$source_path = $arr_files[$model_name]["tmp_name"][$key];
						$org_filename   = basename($value);
						$extension 		= pathinfo($org_filename, PATHINFO_EXTENSION);
						$new_filename   = rand().'.'.$extension;
						$org_dest_path  = $folder_name.$new_filename;
						//echo $source_path."==".$org_dest_path;
						$is_upload =  move_uploaded_file($source_path,$org_dest_path);
						//var_dump($is_upload); die;
						//echo "<pre>"; print_r($data[$model_name]); die;

						if($is_upload)
						{
							if(!empty($data[$model_name]["create_thumb"]))
							{
								foreach($data[$model_name]["create_thumb"][$field_name] as $upload_folder=>$size)
								{
									$thumb_destination = $upload_folder.$new_filename;
									$arr_thumb_h_w = explode(",",$size);
									$thumb_w = $arr_thumb_h_w[0];
									$thumb_h = $arr_thumb_h_w[1];
									//echo $org_dest_path.">>".$thumb_destination.">>".$thumb_w.">>".$thumb_h; die;
									createthumb($org_dest_path,$thumb_destination,$thumb_w,$thumb_h);
								}
							}
							$data[$model_name][$field_name] = $new_filename;
							//echo $key; die;
							//echo $field_name.">>".$new_filename."<br>";
							//echo "<pre>"; print_r($data[$model_name][$field_name]); die;
							if(!empty($data[$model_name]["delete_image"]))
							{
								if(!empty($data[$model_name]["create_thumb"][$field_name]))
								{
									foreach($data[$model_name]["create_thumb"][$field_name] as $upload_folder=>$size)
									{
										//echo $upload_folder."".$data[$model_name]["delete_image"]["old_".$field_name]; die;
										if(isset($data[$model_name]["delete_image"]["old_".$field_name]) && file_exists($upload_folder.$data[$model_name]["delete_image"]["old_".$field_name])){
											unlink($upload_folder.$data[$model_name]["delete_image"]["old_".$field_name]);
										}
									}
								}
								if(isset($data[$model_name]["delete_image"]["old_".$field_name]) && file_exists($folder_name."/".$data[$model_name]["delete_image"]["old_".$field_name])){
									unlink($folder_name."/".$data[$model_name]["delete_image"]["old_".$field_name]);
								}
							}
							//unset($data[$model_name][$key]);

							unset($data[$model_name][$key."_image_root"]);
						}
					}else{
						unset($data[$model_name][$key."_image_root"]);
					}

				}
			}else{
				foreach($arr_files[$model_name]["name"] as $key=>$value){
					unset($data[$model_name][$key]);
					unset($data[$model_name][$key."_image_root"]);
				}
			}
		}

		if(!empty($data[$model_name]["delete_image"]))
		{
			unset($data[$model_name]["delete_image"]);
		}
		if(!empty($data[$model_name]["create_thumb"]))
		{
			unset($data[$model_name]["create_thumb"]);
		}
	//	echo "<pre>"; print_r($data); die;

	}

	function arrange_file_validation($arr_data,$arr_files,$tbl_name,&$rules){

		if(!empty($arr_files)){
			foreach($arr_files[$tbl_name]["name"] as $field_name=>$value)
			{
				if($value != "" || $arr_data[$tbl_name]["delete_image"]["old_".$field_name] != "")
				{
					$field_validation = $rules[$tbl_name.".".$field_name];
					if(strpos($field_validation,"required") !== false){
						$remove_required_validation = str_replace("required|","",$field_validation);
						$rules[$tbl_name.".".$field_name] = $remove_required_validation;
					}
				}
			}
		}
	}

	function createthumb($name,$filename,$new_w,$new_h)
	{
		/*$system=strtolower(end(explode(".",$name)));
		if (preg_match("/jpg|jpeg/",$system)){$src_img=imagecreatefromjpeg($name);}
		if (preg_match("/png/",$system)){$src_img=imagecreatefrompng($name);}
		if (preg_match("/gif/",$system)){$src_img=imagecreatefromgif($name);}*/

		$system=explode(".",$name);
		$count = count($system);
		if($count>0){
			$ext = strtolower($system[$count-1]);
		}else{
			$ext = "";
		}
		$src_img = "";

		if (preg_match("/jpg|jpeg/",$system[1])){
			$src_img=imagecreatefromjpeg($name);
		}else if (preg_match("/png/",$system[1])){
			$src_img=imagecreatefrompng($name);
		}else if (preg_match("/gif/",$system[1])){
			$src_img=imagecreatefromgif($name);
		}else if (preg_match("/bmp/",$system[1])){
			$src_img=imagecreatefromwbmp($name);
		}else if($ext=="jpg" || $ext=="jpeg" || $ext=="JPEG" || $ext=="JPG"){
			$src_img=imagecreatefromjpeg($name);
		}else if($ext=="gif" || $ext=="GIF"){
			$src_img=imagecreatefromgif($name);
		}else if($ext=="png" || $ext=="PNG"){
			$src_img=imagecreatefrompng($name);
		}else if($ext=="bmp" || $ext=="BMP"){
			$src_img=ImageCreateFromBMP($name);
		}else{
			$src_img=imagecreatefromjpeg($name);
		}


		$old_x=imageSX($src_img);
		$old_y=imageSY($src_img);

		if ($old_x > $old_y)
		{
			$thumb_w=$new_w;
			$thumb_h=$old_y*($new_h/$old_x);
		}
		if ($old_x < $old_y)
		{
			$thumb_w=$old_x*($new_w/$old_y);
			$thumb_h=$new_h;
		}
		if ($old_x == $old_y)
		{
			$thumb_w=$new_w;
			$thumb_h=$new_h;
		}

			//$thumb_w=$new_w;
		//$thumb_h=$new_h;

		$dst_img=ImageCreateTrueColor($thumb_w,$thumb_h);
		imagecopyresampled($dst_img,$src_img,0,0,0,0,$thumb_w,$thumb_h,$old_x,$old_y);
		if (preg_match("/png/",$system[1]))
		{
			imagepng($dst_img,$filename);
		}
		else
		{
			imagejpeg($dst_img,$filename);
		}
		imagedestroy($dst_img);
		imagedestroy($src_img);
	}

	/**
		it will be called from the createthumb function
	*/

	/*
	function ImageCreateFromBMP($filename)
	{
	 //Ouverture du fichier en mode binaire
		 if (! $f1 = fopen($filename,"rb")) return FALSE;

	 //1 : Chargement des enttes FICHIER
		 $FILE = unpack("vfile_type/Vfile_size/Vreserved/Vbitmap_offset", fread($f1,14));
		 if ($FILE['file_type'] != 19778) return FALSE;

	 //2 : Chargement des enttes BMP
		 $BMP = unpack('Vheader_size/Vwidth/Vheight/vplanes/vbits_per_pixel'.
									 '/Vcompression/Vsize_bitmap/Vhoriz_resolution'.
									 '/Vvert_resolution/Vcolors_used/Vcolors_important', fread($f1,40));
		 $BMP['colors'] = pow(2,$BMP['bits_per_pixel']);
		 if ($BMP['size_bitmap'] == 0) $BMP['size_bitmap'] = $FILE['file_size'] - $FILE['bitmap_offset'];
		 $BMP['bytes_per_pixel'] = $BMP['bits_per_pixel']/8;
		 $BMP['bytes_per_pixel2'] = ceil($BMP['bytes_per_pixel']);
		 $BMP['decal'] = ($BMP['width']*$BMP['bytes_per_pixel']/4);
		 $BMP['decal'] -= floor($BMP['width']*$BMP['bytes_per_pixel']/4);
		 $BMP['decal'] = 4-(4*$BMP['decal']);
		 if ($BMP['decal'] == 4) $BMP['decal'] = 0;

	 //3 : Chargement des couleurs de la palette
		 $PALETTE = array();
		 if ($BMP['colors'] < 16777216)
		 {
			$PALETTE = unpack('V'.$BMP['colors'], fread($f1,$BMP['colors']*4));
		 }

	 //4 : Cration de l'image
		 $IMG = fread($f1,$BMP['size_bitmap']);
		 $VIDE = chr(0);

		 $res = imagecreatetruecolor($BMP['width'],$BMP['height']);
		 $P = 0;
		 $Y = $BMP['height']-1;
		 while ($Y >= 0)
		 {
			$X=0;
			while ($X < $BMP['width'])
			{
			 if ($BMP['bits_per_pixel'] == 24)
					$COLOR = unpack("V",substr($IMG,$P,3).$VIDE);
			 elseif ($BMP['bits_per_pixel'] == 16)
			 {
					$COLOR = unpack("n",substr($IMG,$P,2));
					$COLOR[1] = $PALETTE[$COLOR[1]+1];
			 }
			 elseif ($BMP['bits_per_pixel'] == 8)
			 {
					$COLOR = unpack("n",$VIDE.substr($IMG,$P,1));
					$COLOR[1] = $PALETTE[$COLOR[1]+1];
			 }
			 elseif ($BMP['bits_per_pixel'] == 4)
			 {

					$COLOR = unpack("n",$VIDE.substr($IMG,floor($P),1));
					if (($P*2)%2 == 0) $COLOR[1] = ($COLOR[1] >> 4) ; else $COLOR[1] = ($COLOR[1] & 0x0F);
					$COLOR[1] = $PALETTE[$COLOR[1]+1];
			 }
			 elseif ($BMP['bits_per_pixel'] == 1)
			 {
					$COLOR = unpack("n",$VIDE.substr($IMG,floor($P),1));
					if     (($P*8)%8 == 0) $COLOR[1] =  $COLOR[1]        >>7;
					elseif (($P*8)%8 == 1) $COLOR[1] = ($COLOR[1] & 0x40)>>6;
					elseif (($P*8)%8 == 2) $COLOR[1] = ($COLOR[1] & 0x20)>>5;
					elseif (($P*8)%8 == 3) $COLOR[1] = ($COLOR[1] & 0x10)>>4;
					elseif (($P*8)%8 == 4) $COLOR[1] = ($COLOR[1] & 0x8)>>3;
					elseif (($P*8)%8 == 5) $COLOR[1] = ($COLOR[1] & 0x4)>>2;
					elseif (($P*8)%8 == 6) $COLOR[1] = ($COLOR[1] & 0x2)>>1;
					elseif (($P*8)%8 == 7) $COLOR[1] = ($COLOR[1] & 0x1);
					$COLOR[1] = $PALETTE[$COLOR[1]+1];
			 }
			 else
					return FALSE;
			 imagesetpixel($res,$X,$Y,$COLOR[1]);
			 $X++;
			 $P += $BMP['bytes_per_pixel'];
			}
			$Y--;
			$P+=$BMP['decal'];
		 }

	 //Fermeture du fichier
		 fclose($f1);

	 return $res;
	 }
	*/

	function get_file_button_control_info($arr_files,$field_name,$file_key){
		foreach($arr_files[$file_key] as $file_button_control_name=>$value){
			if(strpos($file_button_control_name,$field_name) != false){
				return $value;
			}
		}
	}

	

	function multipleRadioButtonStaticList($settings,$selected_checkbox='')
	{
		extract($settings);

		if(!is_array($selected_checkbox)){
			$arr_selected = explode(",",$selected_checkbox);
				if(count($arr_selected) > 0){
					$selected_checkbox = $arr_selected;
				}
		}

		$i=1;
		foreach($data as $value=>$caption)
		{
			if(is_array($selected_checkbox))
			{
				 $checked = "";
				 if(in_array($value,$selected_checkbox))
				 {
					 $checked = "checked='checked'";
				 }
			}

			if($i >= 2){
				$control_class = "";
			}

			$class_str = "chk_".$control_id;
			if($control_class!=""){
				$class_str .= " ".$control_class;
			}

			if(!isset($extra)){$extra = "";}

			if(($i/$per_line)== 0){?>
				<tr>
				<?php }
				if(!isset($extra)){ $extra = "";}
				?>
				<td>
				<input type="radio" class="<?php echo $class_str; ?>" name="<?php echo $control_name; ?>" id="<?php echo $control_id;?>" value="<?php echo $value; ?>" <?php echo $checked; ?> <?php echo $extra; ?> >

				<span class="chk_title"><?php echo $caption; ?></span>
				</td>
				<?php  if(($i%$per_line)== 0){?>
				</tr>
	<?php } ?>
	<?php $i++;
		}
	}

	function objToArray($arr_data){
		$arr_data = collect($arr_data)->map(function($x){ return (array) $x; })->toArray();
		return $arr_data;
	}

?>
