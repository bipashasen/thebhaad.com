<?php

if(!defined('ROOT')) define('ROOT', $_SERVER['DOCUMENT_ROOT']);
$mainTitle='help';
include ROOT.'/inc/guideHeader.php';
$scrollClass = "scrollTNDfeatFPINOffset";
if(isset($_SESSION['user_login'])) $scrollClass = "scrollTNDfeatFPINFixed";

?>

<div class="ehelp_wrapper" id="help_wrapper">
	<div id="gettingStartedHelp">
		<h2>Getting Started.</h2>
		<div class="ehelpContainer"><b>theBhaad.com</b> is a file sharing site which gives you more flexibility and customizability for sharing files. It allows you to 
		 arrange your files in the form of folders within groups and other folders getting you the feel of a folder just as your Mac/Personal Computer.
		 Following are the features you get in <b>theBhaad.com :</b>

		 <ul>
		 	<li><a class="<?php echo $scrollClass; ?>" href="#gapjfy">Get a personal folder just for you!</a></li>
		 	<li><a class="<?php echo $scrollClass; ?>" href="#gtcgaap">Get to create groups and add people</a></li>
		 	<li><a class="<?php echo $scrollClass; ?>" href="#fcwg">Create folders within the groups/other folders and upload contents</a></li>	
		 	<li><a class="<?php echo $scrollClass; ?>" href="#edofc">Edit the description/name of the folders and the name of the contents</a></li>
		 	<li><a class="<?php echo $scrollClass; ?>" href="#delfc">Delete the folders/files once they are no more needed</a></li>
		 	<li><a class="<?php echo $scrollClass; ?>" href="#nritof">No restrictions in the type of file you upload!</a></li>
		 	<li><a class="<?php echo $scrollClass; ?>" href="#aptyc">Add people to your contact list</a></li>
		 </ul>

		 Page Descriptions:

		 <ul>
		 	<li><a class="<?php echo $scrollClass; ?>" href="#rootpage">Root & Groups</a></li>
		 	<li><a class="<?php echo $scrollClass; ?>" href="#contactpge">Contacts & Settings</a></li>
		 	<li><a class="<?php echo $scrollClass; ?>" href="#edtpage">Edit & Posts</a></li>
		 	<li><a class="<?php echo $scrollClass; ?>" href="#archivespage">Archives</a></li>
		 </ul>

		 Other than the features, <b>theBhaad.com</b> provides the following settings & informations:

		 <ul>
		 	<li><a class="<?php echo $scrollClass; ?>" href="#naphelp">Notifications & Posts</a></li>
		 	<li><a class="<?php echo $scrollClass; ?>" href="#searchhelp">Searching people, contents, groups!</a></li>
		 	<li><a class="<?php echo $scrollClass; ?>" href="#genruleshelp">General Rules for uploads & downloads.</a></li>
		 </ul>
		</div>
	</div>
	<div class="helpDet">
		<div id="rootpage" class="defhelp">
			<h2>Root & Groups</h2>
			<div class="ehelpContainer">
				<h3>Root</h3>
				This is the page you see when you log in. The root is the page where you see all your groups that you belong to. To create a new <b>group</b>, you need 
				to be in the root. This is the base directory of all your folders and contents. However, you cannot upload contents directly to the root, you will need to
				get inside some group to upload contents.<br><br>
				The first group you see is <b>Personal</b>, on sign up, each user is assigned his/her personal folder. To know more about the personal group, click 
				<a class="scrollTNDfeatFPIN" href="#gapjfy">here</a>. Rest are the groups that are created by you or others, thats is groups you are a part of. To go to your root, go to <a href="/">www.thebhaad.com</a> or click <a href="/">here</a>. If you are not logged in, you will have to log in first.
				To know about how to send group requests, click <a class="scrollTNDfeatFPIN" href="#gtcgaap">here</a>.
				<br><br>
				<b>Recieving and accepting group request:</b>
				<ul>
					<li>When you have a group request pending, you will see the number of group request is greater than zero. The number indicated the number of 
						pending requests.</li>
					<li>To accept group requests, click on the group icon on the navigation bar.</li>
					<li>You will see a drop down, it will have the list of pending group requests.</li>
					<li>Click on <b>Accept</b> to accept the request and <b>Not interested</b> to reject the request.</li>
					<li>However, if you hit "Not interested", you will still be able to get the request in your archives. To permanently delete a request,
						you must delete the request from the archives. To read more about this or to know how to accept requests from archives, click <a class="scrollTNDfeatFPIN" href="#archivespage">here</a>.
					<div class="helpimg adduserimg"><img src="/helpscrsht/grpreq.png" width="100%"></div>
					<div class="helpimg adduserimg"><img src="/helpscrsht/grpreq2.png" width="100%"></div>
				</ul>
				<b>List of groups you administer:</b>
				<ul>
					<li>To see the list of the groups you administer, click on the groups icons.</li>
					<li>At the bottom of the dropdown, you will see <b>"My groups"</b> at the bottom.</li>
					<li>Click on that to see the list of group you administer.</li>
				</ul>
				<h3>Groups</h3>
				To get inside a group, simply click on the group folder in your root page. Once inside a group, you will be able to <a class="scrollTNDfeatFPIN" href="#fcwg">upload contents</a>, <a class="scrollTNDfeatFPIN" href="#fcwg">make folders</a> within
				the group, <a class="scrollTNDfeatFPIN" href="#gtcgaap">add people</a>, create posts and have group discussions, <a class="scrollTNDfeatFPIN" href="#edofc">edit groupname/group description, edit foldername/folderdescription</a>, <a class="scrollTNDfeatFPIN" href="#delfc">delete contents</a> and
				leave the group.
				<br><br>
				<b>Pasting new post:</b>
				<ul>
					<li>Get inside the group.</li>
					<li>Click on the <b>"Paste New Post"</b> icon, (First Icon)</li>
					<li>Write your new post.</li>
					<li>Click on <b>Paste</b>.</li>
					<li>Bam! your all new post is created.</li>
				</ul>
				<b>Group Discussions:</b>
				<ul>
					<li>Click on the second icon (Group posts) to go to the group discussion page of that group.</li>
					<li>Once in the page, you will see all the posts belonging to the group</li>
					<li>Now comment on, star any post you like.</li>
					<div class="helpimg adduserimg"><img src="/helpscrsht/createpost.png" width="100%"></div>
					<div class="helpimg adduserimg"><img src="/helpscrsht/grpdiss.png" width="100%"></div>
				</ul>
				<b>Leaving a group:</b>
				<ul>
					<li>Click on the last icon (Leave group).</li>
					<li>Click on Yes to the warning.</li>
					<li>Bam! Its done!</li>
					<li>However, you will still be able to view the group in your archives. To know more about it, go to <a class="scrollTNDfeatFPIN" href="#archivespage"></a>
				</ul>
			</div>
		</div>
		<div id="contactpge" class="defhelp">
			<h2>Contacts & Settings</h2>
			<div class="ehelpContainer">
				<h3>Contacts</h3>
				These page shows you your contact list and the people who added you as their contact and lets you add more people to your contact list. To go to your
				contact page, simply click on <b>"View all contacts"</b> at the bottom of your profile or click <a href="/contacts">here</a> or go to <a href="/contacts">www.thebhaad.com/contacts</a>. If you are not logged
				in, you will have to log in first.
				<br><br>
				To view who added you to their contacts, just click on <b>People who added me</b>. To view how to add people to your contact list, click <a class="scrollTNDfeatFPIN" href="#aptyc">here</a>.
				<br><br><h3>Settings</h3>
				To change the settings of your account, go to <a href="/settings">www.thebhaad.com/settings</a> or click <a href="/settings">here</a> or click on 
				. thebhaad.com allows you to change your name and password as many times as you wish. To change your name, simply put your new First name and Last
				name, and to change password, you must enter your old password, put your new password and then confirm. Click save to make the changes permanent.
				<br><br>
				<b>Rules for changing the password:</b>
				<ul>
					<li>You must put the correct current password (Your password)</li>
					<li>Put the new password such that its has at least 8 characters, one upper and one lower case letter and one digit. (New password)</li>
					<li>Confirm the password by typing the same password (Confirm New Password)</li>
					<li>The old and the new password cannot be the same.</li>
					<li>Hit the save button to save the new password</li>
					<li>The next time you log in, you must use the new password</li>
				</ul>
				<b>Rules for changing the name:</b>
				<ul>
					<li>You must fill both the new first name (First Name) and new last name (Last Name) for changing your name.</li>
					<li>Enter a valid name.</li>
					<li>Hit on the save button to save your new name</li>
					<li>Though you will have a new name, your username will remain the same</li>
				</ul>
			</div>
		</div>
		<div id="edtpage" class="defhelp">
			<h2>Edit & Posts</h2>
			<div class="ehelpContainer">
				<h3>Edit</h3>
				In this page, you will be able to edit the public informaton, that is your profile picture, School, College, About and bragging rights. All these 
				informations are public, and will be visible to anybody and everybody. To go to this page go to <a href="/edit">www.thebhaad.com/edit</a> or click 
				<a href="/edit">here</a>. There might be hundreds of other people with the same name as yours, the information you update makes it easier for your
				friends to find you.
				<br><br>
				The following informations can be updated from the <b>Edit</b> page:
				<ul>
					<li><b>Profile picture:</b> To update your profile picture, click on "Upload a new picture!", browse the image and then select the image you want.
						Once image is selected, you will get the option to crop the picture. Select the image proportions and then click on "Crop photo". Once you
						click on that, you picture will be updated and saved.</li>
					<li><b>Other informations:</b> Other informations such as, School, College, About and bragging rights can be updated. Click on "Save" to save
						the changes.</li>
				</ul>
				<h3>Posts</h3>
				This page has all the posts that belongs to the group you are part of. To visit this page go to <a href="/posts">www.thebhaad.com/posts</a> or click <a href="/posts">here</a>.
			</div>
		</div>
		<div id="archivespage" class="defhelp">
			<h2>Archives</h2>
			<div class="ehelpContainer">
				In archives, you can see the pending group requests you are having and the undeleted groups that you left or rejected. The following groups are found in 
				Archives:
				<ul>
					<li><b>Pending Group Requests (Pending Request):</b> These are group requests which are neither Accpeted/Rejected. These requests can either be Deleted/Accepted 
						from the Archives Page. If you delete the request, the request is permanently deleted.</li>
					<li><b>Rejected Group Requests (Rejected Request/Discarded Group):</b> These are the group requests which were rejected. Rejected group requests can be found in Archives, which can be
						either Accepted or permanently deleted from this page.</li>
					<li><b>Groups Left (Rejected Request/Discarded Group):</b> These are the groups which were left. Once you leave a group, the group can be found in Archives page. To permanently delet
						a group, you must delete the groups from the Archives page.</li>
				</ul>
				If an admin leaves a group, then the admin role is assigned to the next person who
				joined the group. If a group has currently no members (Everybody left the group), then the person who first Accepts the group from his/her Archives
				becomes the new admin. The group is permanently deleted only if the group doesnt have any member and the group is not present in anyone's archives.
			</div>
		</div>
		<div id="gapjfy" class="defhelp">
			<h2>Get a personal folder just for you</h2>
			<div class="ehelpContainer">A simple cloud storage just for you, which can't be shared or seen by anyone else but you. As soon as someone signs up for 
			<b>theBhaad.com</b>, he or she is assigned a personal folder which is accessible just by that person. Features of the personal folder is same as any
			other group or folder. That is, the upload limit for the group will be 500 MB, max file size allowed is 100 MB. You cannot upload more than 100 MB at once.
			Unlike any other group or folder, the name of the personal folder is not editable. The personal folder is always the first folder you will see in your root.<br>
			<br>
			The personal folder can be accessed by either clicking on the personal folder icon in your root, or by pressing the personal tab on the navigation bar.</div>
			<div class="helpimg gapjfyimg"><img src="/helpscrsht/personalgrp.png" width="100%"></div>
		</div>
		<div id="gtcgaap" class="defhelp">
			<h2>Get to create groups and add people</h2>
			<div class="ehelpContainer">Groups can be created in no time. To create groups, click on the <b>plus</b> icon at the bottom of the page, enter the name of 
				the group and press enter, or click on <b>"Create group"</b> and bam! your all new group is ready. Now the next step would be adding people/content to the 
				group. 
				<div class="helpimg crtgrpimg"><img src="/helpscrsht/crtgrp.png" width="100%"></div>
				<br><br>To add people to the group, an invitation is first sent to the people you may wanna add. To add people to the group, you can either add
				people from the group or go to their profile and invite them.<br><br>
				<b>To add people from the group:</b>
				<ul><li>Get inside the group.</li><li>Click on the add people icon</li><li>Enter the username/Email-id of the people you would wanna add to the group. 
				To invite more than one person, seperate the usernames and email by a comma. eg: jenniferlawrence, hagrid@hogwarts.com, katniss@hungergames.com</li>
				<li>Select the contacts from the list of the contacts.</li><li>Once done, hit the <b>"Add users"</b> button, and bam the invitation is sent!<br>
				<div class="helpimg adduserimg"><img src="/helpscrsht/add_userIcon.png" width="100%"></div>
				<div class="helpimg adduserimg"><img src="/helpscrsht/add_userIcon2.png" width="100%"></div>
				<div class="image_caption">The green ones dedicate, successful invitations and the red ones are the unsuccessful ones, with the reason for unsuccessful invites given below them.
				Reasons for unsuccessful invites include, repeat invitation for the same group, such username or such email doesn't exist.</div></li>
				<li>In case you have no contacts, there will be another option, <b>"Add contacts"</b>. To add contacts, you can click it!</li>
			</ul>
			<b>To invite people from their profile:</b>
			<ul><li>Go to their profile.</li>
				<li>Click on <b>"Invite"</b></li>
				<li>Select the groups</li>
				<li>Click On Invite</li>
				<li>You are done!<br>
				<div class="helpimg invitefprofimg"><img src="/helpscrsht/invitefprof.png" width="100%"></div>
				<div class="helpimg invitefprofimg"><img src="/helpscrsht/invitefprof2.png" width="100%"></div></li>
			</ul>
			</div>
		</div>
		<div id="fcwg" class="defhelp">
			<h2>Create folders within the groups/other folders and upload contents</h2>
			<div class="ehelpContainer">
				<b>To create new folder inside a group:</b>
				<ul>
					<li>Get inside the group.</li>
					<li>Click on the plus at the bottom of the page.</li>
					<li>Enter the name of the group</li>
					<li>Press Enter or click on <b>"Create Folder"</b></li>
					<li>You are done!</li>
				</ul>
				<b>To upload content:</b>
				<ul>
					<li>Get inside the group/folder</li>
					<li>Click on the up arrow at the bottom of the page</li>
					<li>Now you get two options:
						<ol>
							<li><b>Make Folder</b>, You will be able to put all the contents you upload in the form of a new folder.
								<ul><li>Click on Make Folder</li>
									<li>Type the name of the folder you wanna upload the contents to.</li>
								</ul>
							</li>
							<li><b>Direct Upload</b>, On selecting this option, you will directly upload the content to the current folder/directory.
								<ul><li>Click on Direct Upload</li></ul>
							</li>
						</ol>
					</li>
					<li>Click on <b>"Upload contents!"</b></li>
					<li>Select multiple contents/single contents such that the total size is less than 100MB.</li>
					<li>Once selected, the upload will start</li>
				</ul>
			</div>
		</div>
		<div id="edofc" class="defhelp">
			<h2>Edit the description/name of the folders and the name of the contents</h2>
			<div class="ehelpContainer">
				One of the features of <b>theBhaad.com</b> is customizability. Renaming and editing the description of folders and group is a simple task.
				<ul><li>Get inside the folder/group.</li>
					<li>Look at the right hand side, you will find the description and name of the current folder/group you are in.</li>
					<li>Click on <b>Edit</b></li>
					<li>Make the desired changes to the name and the description.</li>
					<li>Click <b>Save</b> to save the changes. Click <b>Cancel</b> to discard the changes. If you hit <b>Cancel</b> then all the changes will be lost</ul>
					<div class="helpimg gapjfyimg"><img src="/helpscrsht/editgrp.png" width="100%"></div>
					<div class="helpimg gapjfyimg"><img src="/helpscrsht/editgrp2.png" width="100%"></div>
					<div class="helpimg gapjfyimg"><img src="/helpscrsht/editgrp3.png" width="100%"></div>
			</div>
		</div>
		<div id="delfc" class="defhelp">
			<h2>Delete the folders/files once they are no more needed</h2>
			<div class="ehelpContainer">
				Deleting the folder/files which are no more needed, will not only save memory but also make everything look cleaner and tidier. 
				<ul>
					<li>Hover on the files/folders you wanna delete</li>
					<li>Click on the cancel icon next to the folder/file icon</li>
					<li>Bam! The content is gone!</li>
					<div class="helpimg delimg"><img src="/helpscrsht/del2.png" width="100%"></div>
					<div class="helpimg delimg"><img src="/helpscrsht/del1.png" width="100%"></div>
				</ul>
			</div>
		</div>
		<div id="nritof" class="defhelp">
			<h2>No restrictions in the type of file you upload!</h2>
			<div class="ehelpContainer">
				<b>theBhaad.com</b> allows the upload of files of any and every file format, however, everybody is expected to upload the type of files which are not
				harmaful to the people you sharing the file with. For more infomation about the <a href="/terms">Terms</a>, click <a href="/terms">here.</a>
			</div>
		</div>
		<div id="aptyc" class="defhelp">
			<h2>Add people to your contact list</h2>
			<div class="ehelpContainer">
				Adding people to your contact list can be done in two ways, either add people from your contact or go to their profile page and add them to your 
				contact.<br><br>
				<b>To add people from your contact page:</b>
				<ul>
					<li>Go to your <a href="/contacts">contact</a> page. If you are not logged in, you will first have to log in. To go to your 
						contact page, you just need to click on <b>"View all contacts"</b> at the bottom of your profile.</li>
					<li>Click on <b>Add more contacts+</b></li>
					<li>Enter the Username or Email-id of those people you wanna add in your contact. Seperate the Usernames/Email-id with a comma ( , ) for more than one contact.Ex. 
					 katnisseverdeen, harrypotter, galehawthrone@gmail.com.</li>
					<li>Hit on <b>Submit</b></li>
					<li>Bam! you are done!</li>
				</ul>
				<b>To add people from their profile:</b>
				<ul>
					<li>In case you don't have their Username or Email-id, you may instead search for them and then add them to the list. Click <a>here</a> to see 
						how to search for people.</li>
					<li>Once you are in someone's profile, you will see, at the bottom of their profile, <b>"Add to Contacts"</b>. In case that somebody is already in your contact
						list, you will see <b>"Remove Contact"</b>.</li>
					<li>Click on <b>"Add to contacts"</b> to add the person to your contact list.</li>
					<li>Bam! you are done!</li>
					<div class="helpimg addconimg"><img src="/helpscrsht/addcon2.png" width="100%"></div>
					<div class="helpimg addconimg"><img src="/helpscrsht/addcon1.png" width="100%"></div>
				</ul>
				<b>To remove people from your contact list:</b>
				<ul>
					<li>You can either remove a person from your contact page or from their profile.</li>
					<li>Go to your <a href="/contacts">contact</a> page. If you are not logged in, you will first have to log in. To go to your 
						contact page, you just need to click on <b>"View all contacts"</b> at the bottom of your profile. Find the person you wanna remove,
						click on <b>"Remove contact"</b>. Bam! you are done.</li>
					<li>or you could go to that person's profile. If that person is in your contact list, you will see <b>"Remove Contact"</b>. Click <b>Remove Contact</b>
						and bam! you are done!</li>
				</ul>
			</div>
		</div>
		<div id="naphelp" class="defhelp">
			<h2>Notifications & Posts</h2>
			<div class="ehelpContainer">
				<h3>Notifications</h3>
				You can see the notification on the right sidebar. When you log in, you are notified the updates since the last time you logged in. You will be notified for the following reasons:
				<ul>
					<li>When someones joins a group that you are part of.</li>
					<li>When someone adds you to their contact list.</li>
					<li>When someone edits the name/description of a group that you are part of.</li>
					<li>When someone edits the name/description of a folder of a group that you are part of.</li>
					<li>When someone adds a folder to a group that you are part of</li>
				</ul>
				<h3>Posts</h3>
				Posts section on the right sidebar gives the summary of the trending posts. To view the posts summary, click on the post icon at the bottom of the
				page. To go to the post page, go to <a href="/posts">www.thebhaad.com/posts</a>.
				<div class="helpimg gapjfyimg"><img src="/helpscrsht/viewpost.png" width="100%"></div>
			</div>
		</div>
		<!--<div id="searchhelp" class="defhelp">
			<h2>Searching people, contents, groups!</h2>
			<div class="ehelpContainer">
			</div>
		</div>
		<div id="genruleshelp" class="defhelp">
			<h2>General Rules for uploads & downloads.</h2>
			<div class="ehelpContainer">
			</div>
		</div>-->
		<div class="ehelpMore">
			For more information about <a href="/">theBhaad.com</a>, read our <a href="/about">About</a> and <a href="/terms">Terms</a> section. Write to us at our 
			<a href="/suggestion">Suggestion</a> page.
		</div>
	</div>
</div>

</div>
</body>
</html>
