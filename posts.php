<?php

if(!defined('ROOT')) define('ROOT', $_SERVER['DOCUMENT_ROOT']);
include ROOT.'/inc/form_val_functions.php';

if(isset($_GET['postkey'])){

	$mainTitle='post';
	include ROOT.'/inc/header.php';
	$postkey=mysqli_real_escape_string($con,$_GET['postkey']);
	$content=mysqli_query($con, "SELECT posts.*, DATE_FORMAT(posts.main_timepost,'%h:%i %p | %D %M \'%y') as timepost, users.fname, users.lname, users.user_name, users.image from posts INNER JOIN usergroups ON usergroups.gkey=posts.gkey INNER JOIN users ON users.user_name = posts.admin_username where usergroups.uid=".$uid_db." AND posts.postkey='".$postkey."' LIMIT 1");

	if(mysqli_num_rows($content) > 0){
		$post_details=mysqli_fetch_array($content);
		getPosts($con, $post_details, $postkey, $username_db);
	} else {
		if(mysqli_num_rows(mysqli_query($con, "SELECT * from posts where postkey='".$postkey."' ")) == 0)
			echo '<div id="error_posts_ne"><img src="/images/gdiscuss.png" width="250px"><p>No such post exists! Please check the url of the post and try again!<br>Could be possible, this post was deleted!</p></div>';
		else echo '<div id="error_posts_ne"><img src="/images/gdiscuss.png" width="250px"><p?You do not have the access to view this post. That is you are not the member of the group this post belongs to!</p></div>';
	}


} else if(isset($_GET['groupkey'])){
	
	include ROOT.'/inc/connect.php';

	$groupkey_post=trim(mysqli_real_escape_string($con, $_GET['groupkey']));
	$groupname=mysqli_fetch_array(mysqli_query($con, "SELECT groupname from groups where gkey = '".$groupkey_post."' "));

	$groupname=$groupname['groupname'];
	if(!empty($groupname)) $finalTitle='posts | '.$groupname;

	include ROOT.'/inc/header.php';

	if(mysqli_num_rows(mysqli_query($con, "SELECT * from usergroups where uid=".$uid_db." AND usergroups.active=1 AND gkey='".$groupkey_post."' LIMIT 1")) == 1){
		$select_group_posts=mysqli_query($con, "SELECT users.user_name, users.image, users.fname, users.lname, posts.*, DATE_FORMAT(posts.main_timepost, '%h:%i %p | %D %M \'%y') as timepost from posts INNER JOIN users ON posts.admin_username=users.user_name where posts.gkey='".$groupkey_post."' ORDER by posts.timepost DESC LIMIT ".$limit_data_lmc_m." ") or die(mysqli_error($con));
	?>
		<div id="pnp_groupdisc">
			<div id="pnw_inner_wrapper">
				<div id="pnw_head_gd">Paste New Post</div>
				<div id="pnw_textarea_gd">
					<textarea placeholder="Write Post Here..."></textarea>
				</div>
				<div id="sub_pnw_wrapper">
					<span id="pnw_desc_gd"><span><a href="<?php echo $groupkey_post; ?>">Go back to the group</a></span> | Paste a new post and start discussion for this group!</span>
					<input type="submit" id="in_g_nw_l" class="<?php echo $groupkey_post;?>" value="Paste"/>
				</div>
			</div>	
		</div>
		<div id="post_group_wrapper">
			<?php

			while($group_post_details=mysqli_fetch_array($select_group_posts)){
				$grouppostkey=$group_post_details['postkey'];
				getPosts($con, $group_post_details, $grouppostkey, $username_db);
			}
			if(mysqli_num_rows($select_group_posts)<$limit_data_lmc_m)
				echo '<input type="hidden" value="false" id="end_posts_full_l">';

			if(mysqli_num_rows($select_group_posts)==0)
				echo '<div class="no_postsftg" >This group currently doesn\'t have any post! Write a new post to start discussion in this group!</div>';
			?>
			<div id="lmf_posts_l"><img src="/images/loading4.gif" width="25px"></div>
		</div>
		<?php
	} else echo '<div id="posts_forbidden"><img src="/images/forbidden.png" width="350px"><div>You are forbidden to access this page! You are not a member of this group anymore or this group doesn\'t exist!</div></div>';

} else {

	$mainTitle='posts';

	include ROOT.'/inc/header.php';

	$select_first_set_posts=mysqli_query($con, "SELECT users.user_name, users.image, users.fname, users.lname, posts.*, DATE_FORMAT(posts.main_timepost, '%h:%i %p | %D %M \'%y') as timepost from posts INNER JOIN users ON posts.admin_username=users.user_name INNER JOIN usergroups ON usergroups.gkey=posts.gkey where usergroups.uid=".$uid_db." AND usergroups.active=1 ORDER by posts.timepost DESC LIMIT ".$limit_data_lmc_m." ") or die(mysqli_error($con));

	?>
	<div id="post_full_wrapper">
		<?php

		while($all_post_details=mysqli_fetch_array($select_first_set_posts)){
			$allpostkey=$all_post_details['postkey'];
			getPosts($con, $all_post_details, $allpostkey, $username_db);
		}
		if(mysqli_num_rows($select_first_set_posts)<$limit_data_lmc_m)
			echo '<input type="hidden" value="false" id="end_posts_full_m">';
		if(mysqli_num_rows($select_first_set_posts)==0)
			echo '<div id="posts_forbidden"><img src="/images/forbidden.png" width="350px"><div>Looks like you do not have any post to view! How about you start discussion on your own? <a href="'.$username_db.'">Go back to root</a></div></div>';
		?>
		<div id="lmf_posts"><img src="/images/loading4.gif" width="25px"></div>
	</div>
	<?php
}


?>