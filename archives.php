<?php

if(!defined('ROOT')) define('ROOT', $_SERVER['DOCUMENT_ROOT']);
$mainTitle='archives';
include ROOT.'/inc/header.php';

?>

			<div id="archives_wrapper">
				<div id="archives_main_heading">Archives<span id="pending_req_archive"><img src="/images/loading2.gif" width="12px"></span></div>
				<div id="archives_requests_wrapper">

					<?php
						$live_req_query=mysqli_query($con, "SELECT * from usergroups where uid=".$uid_db." AND active= 0 ORDER BY ugid DESC");
						if(mysqli_num_rows($live_req_query)>0){

							while($live_req_array=mysqli_fetch_array($live_req_query)){
								$live_req_gkey=$live_req_array['gkey'];
								$group_info_archive=mysqli_query($con, "SELECT * from groups where gkey = '".$live_req_gkey."'");
								$live_req_odet=mysqli_fetch_array($group_info_archive);
								$live_req_gname=$live_req_odet['groupname'];
								$live_req_gdesc=$live_req_odet['gdesc'];
								$live_req_memnum=$live_req_odet['memnum'];
								$live_req_adid=$live_req_odet['adminid'];
								$live_req_adid_query=mysqli_query($con, "SELECT * from users where uid = ".$live_req_adid." ");
								$live_req_adid_array=mysqli_fetch_array($live_req_adid_query);
								$live_req_adname=$live_req_adid_array['fname']." ".$live_req_adid_array['lname'];
								$live_req_rjct=$live_req_array['rejected'];
								?>

								<div id="live_req_cells_wrapper">
									<div id="group_name_archive">
										<a><?php echo $live_req_gname;?></a><?php if($live_req_rjct==0) echo "<span id='group_attri'>.Pending Request</span>"; else echo "<span id='group_attri'>.Rejected Request/Discarded Group</span>";?>
										<span id="group_job_span">
											<label>
												<input value="<?php echo 'groupIdentity='.$live_req_gkey.'&groupname='.$live_req_gname;?>" id="Archives_Accept" type="hidden">
												<span>Accept</span>
											</label>
											<label>
												<input value="<?php echo 'groupIdentity='.$live_req_gkey.'&groupname='.$live_req_gname;?>" id="Archives_Delete" type="hidden">
												<span>Delete</span>
											</label>
										</span>
									</div>
									<div id="group_desc_archive">
										<b>Desription:</b> <?php if(empty($live_req_gdesc)) echo "<span class='no_desc_arch'>There is no description given for the group.</span>"; else echo $live_req_gdesc;?>
									</div>
									<div id="memnum_archives">
										<b>Number of Member(s)</b>: <?php echo $live_req_memnum." member(s)";?>
									</div>
									<div id="admin_name_archive">
										<b>Admin Name:</b> <?php if($live_req_adid==0) echo '<span class="no_desc_arch">Currently there is no admin</span>'; else echo $live_req_adname;?>
									</div>
								</div>

								<?php
							}
						} else echo '<div id="no_archives">In archives, you can see the pending group requests you are having and 
							the undeleted groups that you left or rejected. However once you delete the group from archives, there is no way you can retireve it.
							<br> Currently there are no archives to show. Go back to <a href="'.$username_db.'">root</a></div>';
					?>

				</div>
			</div>
		
		</div>
    </body>
</html>