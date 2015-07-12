<!--Responsiveness starts : 1150px -->

<?php 

    if (!defined('ROOT')) define('ROOT', $_SERVER['DOCUMENT_ROOT']);

    include_once ROOT.'/inc/user_cred.php';
    include_once ROOT.'/inc/functionsFiles.php';
?>
        <body> 
            <header id="operations_bar">
                <div id="noscript_warning">
                    This site is best viewed with <b>Javascript</b> enabled. Please enable <b>Javascript</b> and refresh the page.
                    Click <a target="_blank" href="http://www.enable-javascript.com/">here</a> to learn how to enable <b>Javascript</b>.
                </div>
                <div id="warning_to_activate">
                    <span id="resendmail_span">
                        Your account is not activated, please visit your Email inbox and click on the link we sent you
                        to activate your account. Your friends will be able to contact you with your email.
                    </span>
                    <a onclick='resendmail()'><b>Didn't recieve email? Resend your Email.</b></a>
                </div>

                <h1>
                    <div class="inlHeaLI headerLogoRe">
                        <a class="headerLogo" href="/<?php echo $username_db?>">theBhaad.com</a>
                    </div>
                    <div class="srchDivWrap"  id="search_form_header">
                        <input type="text" id="search_m_inp" placeholder="Search..."/> 
                    </div>
                    <div class="inlHeaLI" id="route_header">
                        <ul>
                        <?php
                            $count_req=mysqli_fetch_array(mysqli_query($con, "SELECT count(*) as cound_req from usergroups where uid = ".$uid_db." AND active = 0 AND rejected = 0"));
                            $check_greq=mysqli_query($con, "SELECT gid, gkey from usergroups where uid = ".$uid_db." AND active = 0 AND rejected = 0 ORDER BY timegreq DESC LIMIT ".$limit_data_lmc_m."");
                            $num_req=$count_req['cound_req'];
                            $check_greq_number=mysqli_num_rows($check_greq);
                        ?>
                        <li id="forback" <?php if($check_greq_number>0) echo 'class="greq_left"';?>>
                            <span id="click_requests" title="Group Requests">
                                <img class="clickReqIm" src="/images/group_req.png" width="25px" height="25px">
                                <span id="request_number"><?php echo $num_req;?></span>
                            </span>
                            <aside id="group_request_wrapper">
                                <div id="group_req_head">Group Requests</div>
                                <div id="request_cells_wrapper">

                                    <?php
                                        if($check_greq_number>0){

                                        getGreqCell($con, $check_greq);

                                        if(mysqli_num_rows($check_greq) < $limit_data_lmc_m)
                                            echo '<input type="hidden" value="false" id="gReqEnd"/>';
                                        } else {
                                            echo '<div id="no_req_left">You have no new group requests.</div>';
                                        }
                                    ?>
                                </div>
                                <div id="read_all_requests">
                                    <a href="/archives">Archives</a> | <a id="show_my_groups">My Groups</a>
                                </div>
                            </aside>
                        </li>
                        <li id="personalFLink"><a href="/personal">Personal</a></li>
                        <li id="settings">
                            <a href="/settings">Settings</a>
                        </li>
                        <li id="logout_header"><a href="/end_session.php">Logout</a></li>
                        </ul>
                    </div>
                </h1>
            </header>
            <div id="search_m_wrapper">
                <div id="search_input_wrapper">
                    <input type="text" placeholder="search like this!!!" id="search_m_inp_m" spellcheck="false"/>
                    <div id="note_search_m" >Press <span class="start_sch_m"><b>Enter</b></span> to start the search | <span id="cancel_search_m"><b>Cancel</b></span></div>
                    <div id="search_res_m_wrapper">
                        <div class="search_gm_Wrapper search_rm_div_wrapper">
                            <h3>Groups:</h3>
                            <div id="gm_sch_content">
                                <ul class="grp_sch_content_wrapper rm_sch_content_wrapper">
                                </ul>
                            </div>
                        </div>
                        <div class="search_pm_wrapper search_rm_div_wrapper">
                            <h3>People:</h3>
                            <div id="pm_sch_content">
                                <ul class="pm_sch_content_wrapper rm_sch_content_wrapper">
                                </ul>
                            </div>
                        </div>
                        <div class="search_cm_wrapper search_rm_div_wrapper">
                            <h3>Contents:</h3>
                            <div id="pm_sch_content">
                                <ul class="content_sch_content_wrapper rm_sch_content_wrapper">
                                </ul>
                            </div>
                        </div>
                        <div class="search_tm_wrapper search_rm_div_wrapper">
                            <h3>Tags:</h3>
                            <div id="pm_sch_content">
                                <ul class="tag_sch_content_wrapper rm_sch_content_wrapper">
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="imgView"/><div class="imgViewPopUp"></div></div>
            <div id="main_bottom_wrapper">
                <div id="my_groups_wrapper">
                    <div id="my_groups_inner">
                        <div id="my_group_inner_heading"><span class="giamyglist">Groups I Administer:</span><span id="close_mygroup_list"><img src="/images/cancel.png" width="10px"></span></div> 
                        <div id="search_myq_wrapper"><input type="text" id="search_myq_input" placeholder="Search..."/></div>
                        <div id="myq_srch_wrapper" class="myg_div_wrapper"></div>
                        <div id="myg_cells_wrapper" class="myg_div_wrapper">
                            <div id="loading_myglist"><img src="/images/loading3.gif" width="20px"></div>
                        </div>
                    </div>
                </div>