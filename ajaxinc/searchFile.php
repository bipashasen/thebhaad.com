<?php
	
	include '../inc/connect.php';
	session_start();
	include '../inc/functionsFiles.php';
	$uid_db=$_SESSION['user_uid'];
	$username_db=$_SESSION['user_username'];

	if((isset($_POST['searchNextImg']) || isset($_POST['searchPrevImg'])) && isset($_POST['groupKey']) && isset($_POST['folderKey'])){
		if(isset($_POST['searchNextImg'])) $keyImg=trim(mysqli_real_escape_string($con, $_POST['searchNextImg']));
		else $keyImg=trim(mysqli_real_escape_string($con, $_POST['searchPrevImg']));
		$groupKey=trim(mysqli_real_escape_string($con, $_POST['groupKey']));
		$folderKey=trim(mysqli_real_escape_string($con, $_POST['folderKey']));

		if(!empty($keyImg) && !empty($groupKey) && !empty($folderKey)){

			if($groupKey === 'personal')
				$groupUrl = "personaluid = '".$username_db."'";	
			else $groupUrl = "parentgid = '".$groupKey."'";

			if($groupKey === $folderKey)
				$fidUrl = "fid is NULL";
			else $fidUrl = "fid = '".$folderKey."'";

			if(isset($_POST['searchPrevImg'])){
				$operator = ">";
				$order = "ASC";
			} else {
				$operator = "<";
				$order = "DESC";
			}

			$searchNextImgQuery=mysqli_query($con, "SELECT ckey, cname from content where cid ".$operator." (SELECT cid from content where ckey = '".$keyImg."') AND (type = 'jpeg' OR type = 'jpg' OR type = 'png' OR type = 'gif') AND ".$groupUrl." AND ".$fidUrl." ORDER BY cid ".$order." LIMIT 1");
			
			if(mysqli_num_rows($searchNextImgQuery) > 0){ 
				$searchNextImgQuery=mysqli_fetch_array($searchNextImgQuery);
				$nextKey=$searchNextImgQuery['ckey'];
				$nextName = $searchNextImgQuery['cname'];
				echo json_encode(array($nextKey, $nextName));
			}
		}
	}

	if(isset($_REQUEST['searchgroups'])){
		$search_grp_query=trim(mysqli_real_escape_string($con, $_REQUEST['searchgroups']));

		if(!empty($search_grp_query)){
			$search_grp_query='%'.$search_grp_query.'%';
			$search_grp_query=str_replace(" ", "%", $search_grp_query);

			$search_grp_res=mysqli_query($con, "SELECT groups.groupname, groups.gkey from groups INNER JOIN usergroups ON groups.gkey=usergroups.gkey where usergroups.uid = ".$uid_db." AND groupname like '".$search_grp_query."' LIMIT ".$limit_data) or die(mysqli_error($con));

			if(mysqli_num_rows($search_grp_res) == 0){
				echo '<div class="no_sch_result">We couldn\'t find anything for this paricular category.</div>';
			} else {
				while ($curr_grp_res=mysqli_fetch_array($search_grp_res)){
					$gkey_sch_res=$curr_grp_res['gkey'];
					$groupname_sch_res=$curr_grp_res['groupname'];
					?>

					<li class="gm_sch_cell rm_sch_li">
                        <a href="<?php echo '/'.$gkey_sch_res; ?>"><div id="sch_fi_wrapper"><img id="sch_f_i" src="/images/folder2.png" ></div>
                        <div id="folder_name"><?php echo $groupname_sch_res; ?></div></a>
                    </li>

					<?php

				}
			}
		}
	}

	if(isset($_GET['searchpeople']) && isset($_GET['strt_psch'])){

		$search_people_query=trim(mysqli_real_escape_string($con, $_GET['searchpeople']));
		$strt_psch=trim(mysqli_real_escape_string($con, $_GET['strt_psch']));

		if(!empty($search_people_query)){
			$search_people_query='%'.$search_people_query.'%';
			$search_people_query=str_replace(" ", "%", $search_people_query);

			$strt_psch=$strt_psch*$limit_data_minor;

			$search_people_res=mysqli_query($con, "SELECT * from users where (concat(fname,'',lname) like '".$search_people_query."' OR concat(lname,'',fname) like '".$search_people_query."') AND uid <> ".$uid_db." LIMIT ".$strt_psch.", ".$limit_data_minor." ");

			if(mysqli_num_rows($search_people_res) == 0){
				if($strt_psch > 0)
					echo '<div id="endschouterpsch" class="no_sch_result">End of Search.</div>'; 
				else echo '<div class="no_sch_result">We couldn\'t find anything for this paricular category.</div>';
			} else {
				while($curr_people_res=mysqli_fetch_array($search_people_res)){
					findUserDetails($curr_people_res);
            	}
			}

		}
	}

	if(isset($_REQUEST['searchcontent'])){
		$search_content_tag=trim(mysqli_real_escape_string($con, $_REQUEST['searchcontent']));

		if(!empty($search_content_tag)){
			$search_content_tag='%'.$search_content_tag.'%';
			$search_content_tag=str_replace(" ", "%", $search_content_tag);

			$search_global_content=mysqli_query($con,"SELECT folders.fkey as fkey, content.cid as cid, groups.gkey as gkey from folders RIGHT JOIN groupcontent ON folders.fkey=groupcontent.fkey LEFT JOIN content ON groupcontent.cid = content.cid LEFT JOIN groups ON groups.groupid=groupcontent.parentgid INNER JOIN usergroups ON groupcontent.parentgid=usergroups.gid where (folders.fname like '".$search_content_tag."' OR content.cname like '".$search_content_tag."') AND usergroups.uid=".$uid_db." ORDER BY groupcontent.visitcount DESC LIMIT ".$limit_data."") or die(mysqli_error($con));
    		
    		if(mysqli_num_rows($search_global_content) == 0){
    			echo '<div class="no_sch_result">We couldn\'t find anything for this paricular category.</div>';
    		} else {
    			echo '<div id="sgconba">';
    			getInnerFolders($con, $search_global_content, "");
    			echo '</div>';
			}
		}
	}

	if(isset($_REQUEST['searchtags'])){
		$search_tag_query=trim(mysqli_real_escape_string($con, $_REQUEST['searchtags']));

		if(!empty($search_tag_query)){
			$search_usertags_query='%'.$search_tag_query.'%';
			$search_usertags_query=str_replace(" ", "%", $search_usertags_query);

			$search_username_user=mysqli_query($con, "SELECT * from users where user_name = '".$search_tag_query."' LIMIT 1");

			$search_contact_user=mysqli_query($con, "SELECT users.* from users INNER JOIN contacts ON users.uid = contacts.fellowid where contacts.uid = ".$uid_db." AND (concat(fname,'',lname) like '".$search_usertags_query."' OR concat(lname,'',fname) like '".$search_usertags_query."') LIMIT ".$limit_data." ");
		
			if(mysqli_num_rows($search_username_user) + mysqli_num_rows($search_contact_user) == 0){
				echo '<div class="no_sch_result">We couldn\'t find anything for this paricular category.</div>';
			} else {
				if(mysqli_num_rows($search_username_user) > 0){
					$curr_people_res=mysqli_fetch_array($search_username_user);
					?>
						<div class="usernameSearchResult">
							<h2>Username: <span class="uname_sch_uname"><?php echo $search_tag_query;?></span></h2>
					<?php findUserDetails($curr_people_res); ?>
						</div>
					<?php
				}

				while($curr_people_res=mysqli_fetch_array($search_contact_user)){
					findUserDetails($curr_people_res);
            	}
			}
		}
	}

	if(isset($_REQUEST['search_inner']) && isset($_REQUEST['gkey_schin'])){
		
		$search_inner_query=trim(mysqli_real_escape_string($con, $_REQUEST['search_inner']));
		$gkey_schin=trim(mysqli_real_escape_string($con, $_REQUEST['gkey_schin']));

		if(!empty($search_inner_query) && !empty($gkey_schin)){
			$search_inner_query='%'.$search_inner_query.'%';
			$search_inner_query=str_replace(' ', '%', $search_inner_query);

			$gid_db=mysqli_fetch_array(mysqli_query($con, "SELECT groupid from groups where gkey='".$gkey_schin."' "));
			$gid_db=$gid_db['groupid'];

			if($gkey_schin!='personal'){
		        $insertkey="parentgid";
		        $insertvalue=$gid_db;
		    } else {
		        $insertkey="personaluid";
		        $insertvalue=$uid_db;
		    }
		    $parent_fid="NULL";
			$operator=" is ";
		    if(isset($_REQUEST['fkey_schin'])){
				$fkey_schin=trim(mysqli_real_escape_string($con, $_REQUEST['fkey_schin']));
				if(!empty($fkey_schin)){
					$parent_fid=mysqli_fetch_array(mysqli_query($con, "SELECT fid from folders where fkey = '".$fkey_schin."' "));
					$parent_fid=$parent_fid['fid'];
	            	$operator=" = ";
				}
			}
			
			$search_content=mysqli_query($con,"SELECT folders.fkey as fkey, content.cid as cid from folders RIGHT JOIN groupcontent ON folders.fkey=groupcontent.fkey LEFT JOIN content ON groupcontent.cid = content.cid where groupcontent.$insertkey = ".$insertvalue." AND groupcontent.pfid".$operator.$parent_fid." AND (folders.fname like '".$search_inner_query."' OR content.cname like '".$search_inner_query."') ORDER BY visitcount DESC LIMIT ".$limit_data."") or die(mysqli_error($con));
    		
			$num_schinn_res=mysqli_num_rows($search_content);

			echo '<div id="scinn_con_folder">';
			getInnerFolders($con, $search_content, $gkey_schin);

			if($num_schinn_res == 0)
				echo '<div class="no_res_myg">Sorry, but there seems to be no result for this search!</div>';
			echo '</div>';			
		}
	}

	if(isset($_REQUEST['searchmyg'])){
		$srch_myq_query=trim(mysqli_real_escape_string($con, $_REQUEST['searchmyg']));

		if(!empty($srch_myq_query)){
			$srch_myq_query='%'.$srch_myq_query.'%';
			$srch_myq_query=str_replace(' ', '%', $srch_myq_query);

			$select_admin_g=mysqli_query($con, "SELECT * from groups where adminid = ".$uid_db." AND groupname like '".$srch_myq_query."' LIMIT ".$limit_data);

			showmyGroups($select_admin_g);

			if(mysqli_num_rows($select_admin_g)==0)
				echo '<div class="no_res_myg">Sorry, but there seems to be no result for this search!</div>';

		}
	}


	function findUserDetails($curr_people_res){
		$curr_people_img=$curr_people_res['image'];
		$curr_people_name=$curr_people_res['fname']." ".$curr_people_res['lname'];
		$curr_people_prof=$curr_people_res['profession'];
		$curr_people_school=$curr_people_res['school'];
		$curr_people_undergrad=$curr_people_res['undergraduate'];
		$curr_people_abt=$curr_people_res['about'];
		$curr_people_username=$curr_people_res['user_name'];
		?>
		<li class="pm_sch_li rm_sch_li">
	        <div class="ls_psch_wrapper side_sch_li_pm">
	            <a href="<?php echo $curr_people_username;?>"><div class="rm_sch_img_wrapper"><img src="<?php echo "__".$curr_people_img; ?>"></div>
	            <div class="name_sch_wrapper"><?php echo $curr_people_name ?></div></a>
	        </div>
	        <div class="rs_psch_wrapper side_sch_li_pm">
	            <div id="prof_sch_wrapper"><b>Profession: </b><?php if(!empty($curr_people_prof)) echo $curr_people_prof; else echo '<span class="no_sch_result"> No updates!</span>'; ?></div>
	            <div id="edu_sch_wrapper"><b>Education: </b> <?php if(!empty($curr_people_school) && !empty($curr_people_undergrad)) echo $curr_people_school." | ".$curr_people_undergrad; else echo '<span class="no_sch_result"> No updates!</span>'; ?></div>
	            <div id="abt_sch_wrapper"><b>About: </b><?php if(!empty($curr_people_abt)) echo $curr_people_abt; else echo '<span class="no_sch_result"> No updates!</span>'; ?></div>
	        </div>
	    </li>
	    <?php
	}
?>

