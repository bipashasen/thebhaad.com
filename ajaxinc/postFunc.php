<?php

include '../inc/connect.php';
include '../inc/form_val_functions.php';
session_start();
$username_db=$_SESSION['user_username'];
$uid_db=$_SESSION['user_uid'];

if(isset($_REQUEST['comment']) && isset($_REQUEST['postkey']) && !empty($_REQUEST['comment']) && !empty($_REQUEST['postkey'])){

	$comment=trim(mysqli_real_escape_string($con, $_REQUEST['comment']));
	$postkey=trim(mysqli_real_escape_string($con, $_REQUEST['postkey']));
	$ifpostExists=mysqli_num_rows(mysqli_query($con, "SELECT * from posts where postkey = '".$postkey."' LIMIT 1 "));

	if(!empty($comment) && !empty($postkey) && $ifpostExists>0){

		if(preg_match('$(https?://[a-z0-9_./?=&#-]+)(?![^<>]*>)$i', $comment))
            $comment=preg_replace('$(https?://[a-z0-9_./?=&#-]+)(?![^<>]*>)$i', '<a href="$1" target="_blank">$1</a>', $comment." ");
        else if(preg_match('$(www\.[a-z0-9_./?=&#-]+)(?![^<>]*>)$i', $comment))
            $comment=preg_replace('$(www\.[a-z0-9_./?=&#-]+)(?![^<>]*>)$i', '<a href="http://$1" target="_blank">$1</a>', $comment." ");

		$insert_comment=mysqli_query($con, "INSERT INTO comments (postkey, user_name, comment) VALUES ('".$postkey."', '".$username_db."', '".$comment."')");

		$comment_id=mysqli_insert_id($con);

		$update_comment_post=mysqli_query($con, "UPDATE posts SET comments=comments+1 where postkey='".$postkey."' ");

		$user_details=mysqli_fetch_array(mysqli_query($con, "SELECT fname, lname, image from users where user_name='".$username_db."' "));

		$users_image=$user_details['image'];
		$users_fname=$user_details['fname'];
		$users_lname=$user_details['lname'];

		$post_time=mysqli_query($con, "SELECT comment, DATE_FORMAT(timecomment, '%h:%i %p | %D %M \'%y' ) as timecomment from comments where commentid=".$comment_id." ") or die(mysqli_error($con) );
		$post_time=mysqli_fetch_array($post_time);
		$comment_time=$post_time['timecomment'];
		$inserted_comment=$post_time['comment'];

		if($insert_comment){
			?>

			<div id="comment_cell_p_m">
				<div class="cmd_m">
					<img src="<?php echo "__".$users_image;?>">
					<span class="name_comment_m"><?php echo "<a href='/".$username_db."'><b>".$users_fname." ".$users_lname."</b></a> pasted ... ";?></span>
					<span class="comments_time_m"><?php echo $comment_time; ?></span>
				</div>
				<div class="comment_content_p_m">
					<?php echo $inserted_comment; ?>
				</div>
			</div>

			<?php
		}
	} else echo '<div id="postUtilError">This post is not available anymore!! </div>';
}


if(isset($_REQUEST['loadmorecomment']) && isset($_REQUEST['n']) && isset($_REQUEST['nocom'])){
	$pagenum_loadmore_m=trim(mysqli_real_escape_string($con, $_REQUEST['n']));
	$postkey_loadmore_m=trim(mysqli_real_escape_string($con, $_REQUEST['loadmorecomment']));
	$nocom_m=trim(mysqli_real_escape_string($con, $_REQUEST['nocom']));

	if(!empty($postkey_loadmore_m)){

		$start_data_lmc_m=$pagenum_loadmore_m*$limit_data_lmc_m+3;

		$new_comment=mysqli_query($con, "SELECT users.image, users.fname, users.lname, users.user_name, comments.comment, DATE_FORMAT(comments.timecomment, '%h %i %p | %D %M \'%y') as timecomment from users INNER JOIN comments ON users.user_name = comments.user_name where comments.postkey = '".$postkey_loadmore_m."' ORDER BY commentid DESC LIMIT ".$start_data_lmc_m.",".$limit_data_lmc_m." ") or die(mysqli_error($con));

		while($new_comment_details=mysqli_fetch_array($new_comment)){

			$comment_image=$new_comment_details['image'];
			$comment_fname=$new_comment_details['fname'];
			$comment_lname=$new_comment_details['lname'];
			$comment_username=$new_comment_details['user_name'];

			$comment_time=$new_comment_details['timecomment'];

			$comment_final=$new_comment_details['comment'];

			?>

			<div id="comment_cell_p_m">
				<div class="cmd_m">
					<img src="<?php echo "__".$comment_image;?>">
					<span class="name_comment_m"><?php echo "<a href='/".$comment_username."'><b>".$comment_fname." ".$comment_lname."</b></a> pasted ... ";?></span>
					<span class="comments_time_m"><?php echo $comment_time;?></span>
				</div>
				<div class="comment_content_p_m">
					<?php echo $comment_final;?>
				</div>
			</div>
			<?php
		}
		$pagenum_loadmore_m+=1;

		if($nocom_m>$start_data_lmc_m+$limit_data_lmc_m)
			echo '<div id="'.$postkey_loadmore_m.'&n='.$pagenum_loadmore_m.'" class="lm_comments_m"><a>Load More</a></div>';
	}
}

if(isset($_REQUEST['star']) && isset($_REQUEST['postkey_star_m'])){
	$tolike=trim(mysqli_real_escape_string($con, $_REQUEST['star']));
	$postkey_star_m=trim(mysqli_real_escape_string($con, $_REQUEST['postkey_star_m']));
	$ifpostExists=mysqli_num_rows(mysqli_query($con, "SELECT * from posts where postkey = '".$postkey_star_m."' LIMIT 1 "));

	if(!empty($postkey_star_m) && !empty($tolike) && $ifpostExists>0){
		if($tolike=='true'){
			if(mysqli_num_rows(mysqli_query($con, "SELECT * from stars where postkey = '".$postkey_star_m."' AND user_name='".$username_db."' LIMIT 1")) == 0 ){
				mysqli_query($con, "INSERT into stars (postkey, user_name) VALUES ('".$postkey_star_m."', '".$username_db."')");
				mysqli_query($con, "UPDATE posts SET ups = ups+1 where postkey='".$postkey_star_m."'");
			}
		} else if($tolike=='false') {
			if(mysqli_num_rows(mysqli_query($con, "SELECT * from stars where postkey = '".$postkey_star_m."' AND user_name='".$username_db."' LIMIT 1 ")) == 1){
				mysqli_query($con, "DELETE from stars where postkey='".$postkey_star_m."' AND user_name='".$username_db."' ");
				mysqli_query($con, "UPDATE posts SET ups = ups-1 where postkey='".$postkey_star_m."'");
			} 
		}
	} else echo '<div id="postUtilError">This post is not available anymore!! </div>';
}


if(isset($_REQUEST['pageTotPosts']) && isset($_REQUEST['loadmoreposts_full']) && $_REQUEST['loadmoreposts_full'] == 'true'){

	$pagenumber_postf=trim(mysqli_real_escape_string($con, $_REQUEST['pageTotPosts']));
	$start_data_postf=$pagenumber_postf*$limit_data_lmc_m;

	$select_first_set_posts=mysqli_query($con, "SELECT users.user_name, users.image, users.fname, users.lname, posts.*, DATE_FORMAT(posts.main_timepost, '%h:%i %p | %D %M \'%y') as timepost from posts INNER JOIN users ON posts.admin_username=users.user_name INNER JOIN usergroups ON usergroups.gkey=posts.gkey where usergroups.uid=".$uid_db." AND usergroups.active=1 ORDER by posts.timepost DESC LIMIT ".$start_data_postf.",".$limit_data_lmc_m." ") or die(mysqli_error($con));

	while($all_post_details=mysqli_fetch_array($select_first_set_posts)){
		$allpostkey=$all_post_details['postkey'];
		getPosts($con, $all_post_details, $allpostkey, $username_db);
	} 

	if(mysqli_num_rows($select_first_set_posts)<$limit_data_lmc_m)
		echo '<input type="hidden" value="false" id="end_posts_full_m">';
}

if(isset($_REQUEST['add_post_l']) && isset($_REQUEST['gkey_nw_l'])){
	$new_post_l=trim(mysqli_real_escape_string($con, $_REQUEST['add_post_l']));
	$gkey_post_l=trim(mysqli_real_escape_string($con, $_REQUEST['gkey_nw_l']));

	if(!empty($new_post_l) && !empty($gkey_post_l)){
		if(preg_match('$(https?://[a-z0-9_./?=&#-]+)(?![^<>]*>)$i', $new_post_l))
            $new_post_l=preg_replace('$(https?://[a-z0-9_./?=&#-]+)(?![^<>]*>)$i', '<a href="$1" target="_blank">$1</a>', $new_post_l." ");
        else if(preg_match('$(www\.[a-z0-9_./?=&#-]+)(?![^<>]*>)$i', $new_post_l))
            $new_post_l=preg_replace('$(www\.[a-z0-9_./?=&#-]+)(?![^<>]*>)$i', '<a href="http://$1" target="_blank">$1</a>', $new_post_l." ");

        $insert_post=mysqli_query($con, "INSERT into posts (content, gkey, admin_username) VALUES ('".$new_post_l."', '".$gkey_post_l."', '".$username_db."')");
        $post_id=mysqli_insert_id($con);
        $post_key=md5(uniqid().$post_id);

        $update_post=mysqli_query($con, "UPDATE posts SET postkey='".$post_key."' where pid=".$post_id." ");

        if($update_post){

        	$content=mysqli_query($con, "SELECT posts.*, DATE_FORMAT(posts.main_timepost,'%h:%i %p | %D %M \'%y') as timepost, users.fname, users.lname, users.user_name, users.image from posts INNER JOIN users ON users.user_name = posts.admin_username where posts.postkey='".$post_key."' LIMIT 1");

        	$post_details=mysqli_fetch_array($content);

            getPosts($con, $post_details, $post_key, $username_db);
        }
	}
}

if(isset($_REQUEST['loadmoreposts_l']) &&  isset($_REQUEST['pageLPosts'])){
	$pagenumber_postL=trim(mysqli_real_escape_string($con, $_REQUEST['pageLPosts']));
	$start_data_postL=$pagenumber_postL*$limit_data_lmc_m;
	$lmp_l=trim(mysqli_real_escape_string($con, $_REQUEST['loadmoreposts_l']));

	if(!empty($lmp_l)){

		$select_first_set_posts=mysqli_query($con, "SELECT users.user_name, users.image, users.fname, users.lname, posts.*, DATE_FORMAT(posts.main_timepost, '%h:%i %p | %D %M \'%y') as timepost from posts INNER JOIN users ON posts.admin_username=users.user_name where posts.gkey='".$lmp_l."' ORDER by posts.timepost DESC LIMIT ".$start_data_postL.",".$limit_data_lmc_m." ") or die(mysqli_error($con));

		while($all_post_details=mysqli_fetch_array($select_first_set_posts)){
			$allpostkey=$all_post_details['postkey'];
			getPosts($con, $all_post_details, $allpostkey, $username_db);
		} 

		if(mysqli_num_rows($select_first_set_posts)<$limit_data_lmc_m)
			echo '<input type="hidden" value="false" id="end_posts_full_l">';
	}

}

if(isset($_REQUEST['loadmoreposts_home']) && $_REQUEST['loadmoreposts_home']=='true' && isset($_REQUEST['pageposthomeN'])){
	$pagenumber_postH=trim(mysqli_real_escape_string($con, $_REQUEST['pageposthomeN']));
	$start_data_postH=$pagenumber_postH*$limit_data_minor;
    $select_posts_query=mysqli_query($con, "SELECT users.user_name, users.image, users.fname, users.lname, posts.*, DATE_FORMAT(posts.main_timepost, '%h:%i %p | %D %M \'%y') as timepost from posts INNER JOIN users ON posts.admin_username=users.user_name INNER JOIN usergroups ON usergroups.gkey=posts.gkey where usergroups.uid=".$uid_db." AND usergroups.active=1 ORDER by posts.main_timepost DESC LIMIT ".$start_data_postH.",".$limit_data_minor." ") or die(mysqli_error($con));

    getPostsHome($con, $select_posts_query);

    if(mysqli_num_rows($select_posts_query) < $limit_data_minor)
		echo '<div id="endOfPostH">End of Posts!</div>';
}

if(isset($_REQUEST['clickedPosts']) && $_REQUEST['clickedPosts']=='true'){
	$currentT=mysqli_fetch_array(mysqli_query($con, "SELECT current_timestamp as ct"));
	$_SESSION['last_timestamp']=$currentT['ct'];
}

if(isset($_REQUEST['addPstsSec']) && $_REQUEST['addPstsSec']=='true'){
    $select_posts_query=mysqli_query($con, "SELECT users.user_name, users.image, users.fname, users.lname, posts.*, DATE_FORMAT(posts.main_timepost, '%h:%i %p | %D %M \'%y') as timepost from posts INNER JOIN users ON posts.admin_username=users.user_name INNER JOIN usergroups ON usergroups.gkey=posts.gkey where usergroups.uid=".$uid_db." AND usergroups.active=1 ORDER by posts.main_timepost DESC LIMIT ".$limit_data_minor." ");

    getPostsHome($con, $select_posts_query);

    if(mysqli_num_rows($select_posts_query) == 0)
      echo '<div id="noUserph"><img src="/images/forbidden2.png" width="120px"><div>Looks like you do not have any post to view! How about you start discussion on your own?</div></div>';

}

if(isset($_REQUEST['numMainPost']) && $_REQUEST['numMainPost'] == 'true'){
	$select_posts_query=mysqli_fetch_array(mysqli_query($con, "SELECT count(*) as cst from posts INNER JOIN usergroups ON usergroups.gkey=posts.gkey where usergroups.uid=".$uid_db." AND usergroups.active=1 AND posts.timepost>'".$_SESSION['start_timestamp']."' "));

	echo $select_posts_query['cst'];
}

if(isset($_REQUEST['delete_uposts'])){
	$delete_uposts=trim(mysqli_real_escape_string($con, $_REQUEST['delete_uposts']));

	mysqli_query($con, "DELETE from comments where postkey='".$delete_uposts."' ");
	mysqli_query($con, "DELETE from stars where postkey='".$delete_uposts."' ");
	mysqli_query($con, "DELETE from posts where postkey= '".$delete_uposts."' ");
}

?>