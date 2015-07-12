<?php
function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data,ENT_QUOTES, 'UTF-8');
  return $data;
}

function valOver($error){
   for($x=0;$x<count($error);$x++)
     if ($error[$x]==0)
       return 0;
   return 1;
}

function check_full_name($fname, $lname){
  $key_names=array('edit','profile', 'group', 'setting', 'settings', 'music', 'editing', 'personal', 'help', 'helping', 'about', 'recovery', 'suggestion', 'terms', 'contacts', 'archives', 'posts');

  $val=$fname.$lname;
  if(in_array(strtolower($val), $key_names))
    return false;
  return true;
}

function check_name($val){
  $key_names=array('edit','profile', 'group', 'setting', 'settings', 'music', 'editing', 'personal', 'help', 'helping', 'about', 'recovery', 'suggestion', 'terms', 'contacts', 'archives', 'posts');

  if(!preg_match("/^[a-zA-Z ]*$/",$val))
    return false;
  if(in_array(strtolower($val), $key_names))
    return false;
  return true;
}


function getPosts ($con, $post_details, $postkey, $username_db) {

  $user_fullname=$post_details['fname']." ".$post_details['lname'];
  $user_picture=$post_details['image'];
  $user_username=$post_details['user_name'];
  $post_content=$post_details['content'];

  $post_group=$post_details['gkey'];
  $admin_uname=$post_details['admin_username'];
  $ups_post=$post_details['ups'];
  $comments_post=$post_details['comments'];

  $posts_time=$post_details['timepost'];

  $group_name_q=mysqli_fetch_array(mysqli_query($con, "SELECT groupname from groups where gkey='".$post_group."' "));
  $group_name_val=$group_name_q['groupname'];

  ?>

    <div class="post_wrapper_m" id="<?php echo $postkey;?>">
      <div class="post_upper_m">
        <div class="post_maker_wrapper">
          <?php if($admin_uname==$username_db){ ?>
            <div title="Delete Post" id="delete_uposts"><img src="/images/cancel.png"></div>
          <?php } ?>
          <div class="pmd_m">
            <img src="<?php echo "__".$user_picture;?>">
            <span><?php echo "<a href='/".$user_username."'><b>".$user_fullname."</b></a> pasted in <a class='group_name_p_m' href='/".$post_group."'><b>".$group_name_val."</b></a> ... ";?></span>
            <span class="post_time_m"><?php echo $posts_time;?></span>
          </div>
        </div>
        <div class="post_content_m"><?php echo $post_content; ?></div>
        <div class="star_post_wrapper">
          <span id="<?php echo $postkey;?>" class="sn_post_m"><img src="/images/star.png"><span><?php 
          if(mysqli_num_rows(mysqli_query($con, "SELECT * from stars where user_name='".$username_db."' AND postkey='".$postkey."' LIMIT 1 ")) == 1 )
            echo 'Unstar';
          else echo 'Star';
          ?></span></span>
          <span class="starred_post_m"><?php echo "<span id='sn_".$postkey."'>".$ups_post."</span> stars | <span id='cf_".$postkey."'>".$comments_post."</span> comments"; ?></span>
        </div>
      </div>  
      <div class="comments_wrapper">
        <div class="input_post_wrapper_m">
          <textarea id="<?php echo $postkey; ?>" placeholder="Write Comment..." class="comment_post_m"></textarea>
        </div>
        <div class="user_comments_m">

          <?php 

          $select_comments=mysqli_query($con, "SELECT users.image, users.fname, users.lname, users.user_name, comments.comment, DATE_FORMAT(comments.timecomment, '%h:%i %p | %D %M \'%y') as timecomment from users INNER JOIN comments ON users.user_name = comments.user_name where comments.postkey = '".$postkey."' ORDER BY commentid DESC LIMIT 3 ") or die(mysqli_error($con));

          while($select_current_comment=mysqli_fetch_array($select_comments)){
            $comment_image=$select_current_comment['image'];
            $comment_fname=$select_current_comment['fname'];
            $comment_lname=$select_current_comment['lname'];
            $comment_username=$select_current_comment['user_name'];

            $comment_time=$select_current_comment['timecomment'];

            $comment_final=$select_current_comment['comment'];

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

          if($comments_post>3)
            echo '<div id="'.$postkey.'&n=0" class="lm_comments_m"><a>Load More</a></div>';

          ?>
        </div>
      </div>
    </div>

  <?php
}

function getPostsHome($con, $select_posts_query){
  while($fetch_postsnot=mysqli_fetch_array($select_posts_query)){

    $user_fullname=$fetch_postsnot['fname']." ".$fetch_postsnot['lname'];
    $user_picture=$fetch_postsnot['image'];
    $user_username=$fetch_postsnot['user_name'];
    $post_content=$fetch_postsnot['content'];
    $post_postkey=$fetch_postsnot['postkey'];

    $post_group=$fetch_postsnot['gkey'];
    $admin_uname=$fetch_postsnot['admin_username'];
    $ups_post=$fetch_postsnot['ups'];
    $comments_post=$fetch_postsnot['comments'];

    $posts_time=$fetch_postsnot['timepost'];

    $group_name_q=mysqli_fetch_array(mysqli_query($con, "SELECT groupname from groups where gkey='".$post_group."' ")) or die(mysqli_error($con));
    $group_name_val=$group_name_q['groupname'];

    ?>

    <div class="notes_box">
        <div class="admin_pm_name">
            <a href="<?php echo $user_username;?>"><b><?php echo $user_fullname;?></b></a> pasted in... <span>
            <a href="<?php echo $post_group; ?>"><b><?php echo $group_name_val;?></b></a></span>
        </div>
        <div class="timepost_post_k">
            <?php echo $posts_time;?>
        </div>
        <div class="summary_post_k">
            <?php echo $post_content; ?>
        </div>
        <div class="com_star">
            <?php echo $ups_post;?> Star | <?php echo $comments_post;?> Comments
        </div>
        <div class="view_more">
            <a href="/posts_<?php echo $post_postkey;?>" target="_blank">View More</a>
        </div>
    </div>
    <?php

  }

}

?>