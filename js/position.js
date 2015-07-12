
// the target size
var TARGET_W = 300;
var TARGET_H = 300;

// close_popup : close the popup
function close_popup(id) {
  // hide the popup
  $('#'+id).hide();
}

function loading_gif() {
  $('.loading').css('display','inherit');
}
function hide_gif(){
  $('.loading').css('display','none');
}

// show_popup_crop : show the crop popup
function showcrop(temp_url, url, name ,fileType) {
  //console.log(url);
  // change the photo source
  url=url+'?'+$.now();
  $('#cropbox').html('');
  $('#cropbox').html('<img/>');
  $('#cropbox img').attr('src', url);
  // destroy the Jcrop object to create a new one
  try {
    jcrop_api.destroy();
  } catch (e) {
    // object not defined
  }
  var winH=$(window).height();
  var winW=$(window).width();

  var maxWCrop = 0.8 * winW;
  var maxHCrop = 0.65 * winH; 
  // Initialize the Jcrop using the TARGET_W and TARGET_H that initialized before
    $('#cropbox img').Jcrop({
      aspectRatio: 1,
      boxWidth: maxWCrop,   //Maximum width you want for your bigger images
      boxHeight: maxHCrop,
      setSelect:   [ 100, 100, TARGET_W, TARGET_H ],
      onSelect: updateCoords
    },function(){
        jcrop_api = this;
    });

    // store the current uploaded photo url in a hidden input to use it later
  $('#photo_url').val(temp_url);
  $('#username').val(name);
  $('#imageFileType').val(fileType);
  // hide and reset the upload popup
  $('#display_pic_input_first').val('');

  // show the crop popup
  $('#popup_crop').show();
}

// crop_photo : 
function crop_photo() {
  var x_ = $('#x').val();
  var y_ = $('#y').val();
  var w_ = $('#w').val();
  var h_ = $('#h').val();
  var photo_url_ = $('#photo_url').val();
  var username_ = $('#username').val();
  var fileTypeImage_ = $('#imageFileType').val();

  // hide thecrop  popup
  $('#popup_crop').hide();

  // display the loading texte
  // crop photo with a php file using ajax call
  $.ajax({
    url: 'ce9ec99654_1af209662f1c_b5fce3d33d_cP',
    type: 'POST',
    data: {x:x_, y:y_, w:w_, h:h_, photo_url:photo_url_, username:username_, fileTypeImage:fileTypeImage_, targ_w:TARGET_W, targ_h:TARGET_H},
    success:function(data){
      // display the croped photo
      $('#profile_picture_image').html(data);
      if($('.remove_display').length === 0){
        $('#add_infomation_form').prepend('<label class="remove_display"><input type="submit" name="removie_display_input" onclick="javascript:this.form.submit();"><p>Remove Picture</p></label>');
      }
    }
  });
}

// updateCoords : updates hidden input values after every crop selection
function updateCoords(c) {
  $('#x').val(c.x);
  $('#y').val(c.y);
  $('#w').val(c.w);
  $('#h').val(c.h);
}

function removeContact(username_contact){
  $.ajax({
    url:"3a42f1387a_1f512969124_a5216dbb94d_aC",
    type:"POST",
    data: "toremovecontact="+username_contact,
    success: function(response){
      var ele=$('#addtocontactsanchor_'+username_contact);
      ele.html("Add to Contacts");
      ele.attr('onclick','addtocontacts("'+username_contact+'")');
    }
  });
}

function addtocontacts(username_url){
  $.ajax({
    url:"3a42f1387a_1f512969124_a5216dbb94d_aC",
    type:"POST",
    data: "toaddcontact="+username_url,
    success: function(response){
      var element_try = '#addtocontactsanchor_'+username_url;
      var ele=$('#addtocontactsanchor_'+username_url);
      ele.html("Remove Contact");
      ele.attr('onclick','removeContact("'+username_url+'")');
    }
  });
}

function showAddContacts(){
  if($('#add_more_contact_input').is(':visible')){
    $('#table_wrapper_add_contacts').slideUp();
    $('#add_more_contact a').css({"color":"", "border-color":"", "background":""})
  } else {
    $('#table_wrapper_add_contacts').slideDown();
    $('#add_more_contact a').css({"color":"#f1f1f1", "border-color":"#f1f1f1", "background":"rgb(212, 112, 98)"})
  }  
}

function resendmail(){
  $.ajax({
    url: "8ec9343ae61_92e15b3665_ec838216a98_sNm",
    type: "POST",
    data: "resendmail=true",
    success: function(response){
      $('#resendmail_span').html("We have sent you the mail again. please visit your Email inbox and click on the link we sent you to activate your account. ");
    }
  });
}

function align() { 
  var winH=$(window).height();
  var winW=$(window).width();
  try{
    var x=document.getElementById('operations_bar').clientHeight;
    document.getElementById('main_bottom_wrapper').style.paddingTop=x+'px';
    var y=document.getElementById('icons').clientWidth;
    document.getElementById('utilities').style.width=y+'px';
  } catch(e) {}
  $('#search_m_wrapper').css('margin-top', x+'px');
  $('#main_bottom_wrapper').css('height',winH-x);
  var effH=x;
  try{
    var gdescH=$('#content_desc_wrapper').outerHeight();
    var noteHeadH=$('#notes_heading_rs').outerHeight();
    var viewAH=$('.view_all_notes_rs').outerHeight();
    effH=effH+gdescH+noteHeadH+viewAH;
  } catch(e) {}
  $('#notes_content_wrap').css('height',winH-effH);
  $('#notification_posts_wrapper').css('height', winH-x-viewAH-$('#slide_back_noti').outerHeight(true)-10);

  var addUtH=$('#stuffs_additional_editing').height();
  $('#in_folders').css('top', addUtH+18+'px');
  $('#sch_res_inner_wrapper').css('top', addUtH+18+'px');
}

function notificationHover() {
    $('#noti_numbers').hover(function(){
      if (!$('#notification_notes_wrapper').data('shown')){
        $(this).css({"padding":"10px","margin":"-10px 10px"});
        $('#notifications').css("opacity","1");
        $('#notifications img').css({"width":"110px","height":"110px", "margin":"-10px"});
      }
    },function(){
      if ($('#noti_numbers').is(':visible') && !$('#notification_notes_wrapper').data('shown')) {
        $(this).css({"padding":"", "margin":""});
        $('#notifications').css("opacity","");
        $('#notifications img').css({"width":"","height":"", "margin":""});
      }
    });
}

function hideNoti() {
  $('#notifications').css("opacity","");
  $('#notifications').find('img').css({"width":"","height":"", "margin":""});
  $('#notifications').siblings('#noti_numbers').html('<a>0</a>').hide();
  $('#noti_numbers').css({"padding":"","margin":""});
  $.ajax({
    url:'f27927d5a_918b252e7fa_e186402e17b4_pF',
    type: 'post',
    data: 'clickedPosts=true'
  });
  $('#notes_posts_outer_wrap').animate({right:'-285px'});  
  $('#notification_notes_wrapper').data('shown',false);
}
function showNoti() {
  $('#notifications').css("opacity","1");
  $('#notifications').find('img').css({"width":"110px","height":"110px", "margin":"-10px"});
  $('#noti_numbers').css({"padding":"10px","margin":"-10px 10px"});
  $('#notifications').siblings('#noti_numbers').html('<a>0</a>').hide();
  $.ajax({
    url:'f27927d5a_918b252e7fa_e186402e17b4_pF',
    type: 'post',
    data: 'clickedPosts=true'
  });
  $('#notes_posts_outer_wrap').animate({right:'0'});
  $('#notification_notes_wrapper').data('shown',true);
}
function toggleNotification(){
  if ($('#notification_notes_wrapper').data('shown'))
    hideNoti();
  else
    showNoti();
}
function notificationClick() {
  $('#noti_numbers').click(function(){
    toggleNotification();
  });
  $('#slide_back_noti img').click(function(){
      hideNoti();
  });
  $('#notifications').click(function(){
    toggleNotification();
  });
}

function requestsShow(){
  $('#click_requests').click(function(){
    if($('#group_request_wrapper').is(':visible')){
      $('#group_request_wrapper').fadeOut(100);
      $('#forback').css("opacity","");
    } else {
      $('#group_request_wrapper').fadeIn(100);
      $('#forback').css("opacity","1");
    }
  });

  var pageGreq=0;
  var readyReqCellWrap = true;
  $('#request_cells_wrapper').scroll(function(){
    if($(this)[0].scrollHeight - $(this).scrollTop() <= $(this).outerHeight() && $('#gReqEnd').val() == undefined && readyReqCellWrap){
      pageGreq+=1;
      readyReqCellWrap = false;
      $(this).append('<div id="loading_greq"><img src="/images/loading.gif"></div>')
      $.ajax({
        url: '05a0e3c00_2326d16ca70_3b444087ed45_inf',
        type: 'POST',
        cache: false,
        data: 'greqMore=true & pageGreq='+pageGreq,
        success: $.proxy(function(response){
          readyReqCellWrap = true;
          $(this).find('#loading_greq').remove();
          $(this).append(response);
        }, this)
      });
    }
  });
}

function groupReqToHide(e , outer, inner){
  var container = $(outer);
  if (!container.is(e.target) // if the target of the click isn't the container...
        && container.has(e.target).length === 0) // ... nor a descendant of the container
    {
        container.fadeOut(100);
        $(inner).css("opacity","");
    }
}

function infiniteContacts(){
  var pagenum=0;
  var readyConLoad = true;
  $('#contact_loader').data('shown',true);
  $(window).scroll(function() {
    try{
      if($('#contact_loader').data('shown')){
      var inpvalueContacts = $('.contact_loadmore_input').val();
        if(inpvalueContacts=='true'){
         if($(window).scrollTop() + $(window).height() == $(document).height() && readyConLoad) {
          readyConLoad = false;
          $('#contact_loader').show();
          pagenum=pagenum+1;
           $.ajax({
            url: "267fc9f66d_3f6425b138_c1b2c73da1a7_cI",
            type: "POST",
            data: "infinitecontacts=true&page="+pagenum,
            cache: false,
            success: function(response){
              readyConLoad = true;
              $('#contact_loader').hide();
              var remres=$('.contact_loadmore_input').val();
              if(remres=='false')
                $('#contact_loader').data('shown',false);
              $('#contact_cell_wrapper').append(response);
            }
           });
          }
        }
      }
    }
    catch (e){}
  });

  var readypwam=true;
  var pwam_page=0;
  $('#other_contacts_cell_wrapper').scroll(function(){
    if(($(this)[0].scrollHeight - $(this).scrollTop() <= $(this).outerHeight()+1) && readypwam){
      readypwam=false;
      if($('#pwam_input').val()==undefined){
        pwam_page+=1;
        $(this).append('<div class="loading_pwam"><img src="/images/loading.gif"></div>');
        $.ajax({
          url: '05a0e3c00_2326d16ca70_3b444087ed45_inf',
          type: 'POST',
          data: 'pwam_load_more=true & pwam_page='+pwam_page,
          cache: false,
          success: function(response){
            $('.loading_pwam').remove();
            $('#pwamac_ul').append(response);
            if($('#pwam_input').val()==undefined)
              readypwam=true;
          }
        });
      }
    }
  }); 
}

function showmorenotes(){
  var page=0, end=0;
  var readyNotes=true;
  $('#load_old_notes').show();
  $.ajax({
    url: 'fc9689f50d35_0ee36956d2_5c5719c077_lO',
    data: 'showOldNote='+page,
    cache: false,
    success: function(response){
      $('#load_old_notes').hide();
      $('#notes_content_wrap').append(response);
    }
  });

  $('#notes_content_wrap').scroll(function(){
    if($('#no_old_notification').length===0){
      if(($(this)[0].scrollHeight - $(this).scrollTop() <= $(this).outerHeight()+1) && end==0 && readyNotes){
        readyNotes=false;
        $('#load_old_notes').show();
        $('#notes_content_wrap').append($('#load_old_notes'));
        page=page+1;
        $.ajax({
          url: 'fc9689f50d35_0ee36956d2_5c5719c077_lO',
          data: 'showOldNote='+page,
          cache: false,
          success: function(response){
            $('#load_old_notes').hide();
            $('#notes_content_wrap').append(response);
            readyNotes=true;
          }
        });
      }
    }
  });
}

function infiniteNotes(){
  $('#von').click(function(){
    $(this).remove();
    showmorenotes();
  });

  $('#no_new_notification span').click(function(){
    $('#no_new_notification').remove();
    showmorenotes();
  });
}

function addContacts(){
  try{
    $('#addcontacts_submit').click(function(){
      var datatoadd=$('#textarea_add_contact').val();
      if(datatoadd!=''){
        $('#load_contacts').show();
        try{
          $('.no_exist').remove();
        } catch(e){}
        $.ajax({
          url: "3a42f1387a_1f512969124_a5216dbb94d_aC",
          type: "POST",
          data: "addcontacts="+datatoadd,
          cache: false,
          success: function(response){
            $('#contact_cell_wrapper').prepend(response);
            try{
              $('#no_contact').hide();
            } catch(e) {}
            $('#load_contacts').hide();
          }
        });
      }
    });
  }catch (e){}

  $('#pwamac').click(function(){
    if($('#other_contacts_cell_wrapper').is(':visible')){
      $(this).css('color','');
      $('#other_contacts_cell_wrapper').slideUp();
    } else{
      $('#other_contacts_cell_wrapper').slideDown();
      $(this).css('color','#444');
    }
  });

  $(document).on('click', '#showpwamList_rd', function(){
    window.location.href="/contacts#show-other-contacts";
  }); 

  var hashpwam=window.location.hash;
  if(hashpwam && $('#contacts_main_wrapper').length){
    
    if(hashpwam == '#show-other-contacts'){
      setTimeout(function(){
        $('#other_contacts_cell_wrapper').slideDown();
        $('#pwamac').css('color', 'rgb(68, 68, 68)');
      }, 500);
    }
  }
}

function infiniteScrollMain(x){
  var page=0;
  var readyEndRes = true;
  $('#end_results').data('shown', true);
  $(x).scroll(function(){
    if($('#end_results').data('shown')){
      if($(this)[0].scrollHeight - $(this).scrollTop() <= $(this).outerHeight()+1 && readyEndRes)
      { 
          readyEndRes = false;
          page=page+1;
          if(x=='#in_folders'){
            var beforeand=$(this).find('#beforeand').val();
            var afterand=$(this).find('#aftereand').val();
            var dataToSend="beforeand="+beforeand+"&afterand="+afterand+"&pagenumber="+page;
          } 
          else var dataToSend='grouppagenumber='+page;
          $(this).find('#loading_result').show();
          $.ajax({
            url: "05a0e3c00_2326d16ca70_3b444087ed45_inf",
            type: "POST",
            data: dataToSend,
            cache: false,
            success: $.proxy(function(response){
              readyEndRes = true;
              $(this).find('#loading_result').hide();
              var remaining_results=$(this).find(".remaining_results").val();
              $(this).find('#folder_content_wrapper').append(response);
              if(remaining_results=='false'){
                $(this).find('#end_results').show();
                $('#end_results').data('shown', false);
              }
            },this)
          });
      }
    }
  });
}

function plusShow() {
  $('#plus').hover(function(){
    plusTimeOut = setTimeout($.proxy(function(){
        $(this).prev().fadeIn();
        $(this).css({'opacity':'1','margin-left': '-20px', 'margin-right': '20px'});
        $(this).find('img').css({'width':'110px','height':'110px'});
        $(this).find('img').data('shown',true);
    },this), 2000);
  },function(){
    clearTimeout(plusTimeOut);
    /*if ($('.utilities_hover_function').is(':hidden')) {
        $(this).css({'opacity':'','margin-left': '', 'margin-right': ''});
        $(this).find('img').css({'width':'','height':''});
        $(this).find('img').data('shown',false);
    }*/
  });
  $('#plus').click(function(){
    if ($(this).find('img').data('shown')) {
        $(this).prev().hide();
        $(this).find('img').data('shown',false);
        $(this).css({'opacity':'','margin-left': '', 'margin-right': ''});
        $(this).find('img').css({'width':'','height':''});
    }else{
        $(this).prev().fadeIn();
        $(this).css({'opacity':'1','margin-left': '-20px', 'margin-right': '20px'});
        $(this).find('img').css({'width':'110px','height':'110px'});
        $(this).find('img').data('shown',true);
    }
  });
  $('#close_div').click(function(){
        $('#plus').prev().hide();
        $('#plus img').data('shown',false);
        $('#plus').css({'opacity':'','margin-left': '', 'margin-right': ''});
        $('#plus img').css({'width':'','height':''});
  });
}

function uploadShow() {
  $('#upload').hover(function(){
    uploadTimeout=setTimeout($.proxy(function(){
        $(this).prev().fadeIn();
        $(this).css({'opacity':'1','margin':'-5px -5px 0 5px'});
        $(this).find('img').css({'width':'110px','height':'110px'});
        $(this).find('img').data('shown',true);
    },this),2000);
  }, function(){
    clearTimeout(uploadTimeout);   
  });
  $('#upload').click(function(){
    if ($(this).find('img').data('shown')) {
        $(this).prev().hide();
        $(this).find('img').data('shown',false);
        $(this).css({'opacity':'','margin-left': '', 'margin-right': ''});
        $(this).find('img').css({'width':'','height':''});
    }else{
        $(this).prev().fadeIn();
        $(this).css({'opacity':'1','margin':'-5px -5px 0 5px'});
        $(this).find('img').css({'width':'110px','height':'110px'});
        $(this).find('img').data('shown',true);
    }
  });
  $('#close_div_upload').click(function(){
        $('#upload').prev().hide();
        $('#upload img').data('shown',false);
        $('#upload').css({'opacity':'','margin-left': '', 'margin-right': ''});
        $('#upload img').css({'width':'','height':''});
  });

}

function onscrollInviteList(pageInv, readyInvite){
  $(".grpListInvite").scroll(function() {
    if(($(this)[0].scrollHeight - $(this).scrollTop() <= $(this).outerHeight()) && $(this).find('#endInviteList').val() == undefined && readyInvite){
      pageInv+=1;
      readyInvite=false;
      $(this).append('<div class="loading_inv"><img src="/images/loading3.gif"></div>');
      $.ajax({
        url: '05a0e3c00_2326d16ca70_3b444087ed45_inf',
        type: 'POST',
        data: 'inviteUsers='+pageInv,
        cache: false,
        success: $.proxy(function(response){
         $(this).find('.loading_inv').remove();
          $(this).append(response);
          readyInvite=true;
        },this)
      });
    }
  });
}

function loadprofile(username_ou){
  $('#NMdetailsHoverWrapper').fadeIn(200);
  $.ajax({
    url: '05a0e3c00_2326d16ca70_3b444087ed45_inf',
    type: 'POST',
    data: 'username='+username_ou,
    cache: false,
    success: function(response){
      $('#NMdetailsHoverWrapper').html(response);
      var pageInv=0;
      var readyInvite=true;
      onscrollInviteList(pageInv, readyInvite);
    }
  });
}

function NMnameClick() {
  $(document).on('click', '.notes_box > a:first-child', function(e){
    e.preventDefault();
    var username_ou=$(this).attr('href');
    if(username_ou!=undefined)
      loadprofile(username_ou);
  });
  $(document).on('click', '.admin_pm_name > a:first-child', function(e){
    e.preventDefault();
    var username_ou=$(this).attr('href');
    if(username_ou!=undefined)
      loadprofile(username_ou);
  });
  $(document).on('click', '#memname_load_rs > div', function(e){
    e.preventDefault();
    var username_ou=$(this).attr('id');
    if(username_ou!=undefined)
      loadprofile(username_ou);
  });
  $(document).on('click', '#close_div_NMdetails', function(){
    $('#NMdetailsHoverWrapper').fadeOut(200);
  });
}

function show_addUsers(){
  try{
    $('#add_users_img').click(function(){
      if($('#add_users_main').is(':visible')){
        $('#add_users_main').fadeOut(100);
        $(this).css('opacity','');
      } else{
        $('#add_users_main').fadeIn(100);
        $(this).css('opacity','1');
      }
    });
  } catch(e){}

  $('#paste_post_img').click(function(){
    if($('#paste_post_wrapper').is(':visible')){
      $('#paste_post_wrapper').fadeOut(100);
      $(this).css('opacity','');
    } else {
      $('#paste_post_wrapper').fadeIn(100);
      $(this).css('opacity','1');
    }
  });

  $('#leave_group_img').click(function(){
    if($('#leave_group_main').is(':visible')){
      $('#leave_group_main').fadeOut(100);
      $(this).css('opacity','');
    } else{
      $('#leave_group_main').fadeIn(100);
      $(this).css('opacity','1');
    }
  });

  $('#leave_group_input_wrappe input').click(function(){
    if($(this).val() == 'No'){
      $('#leave_group_main').fadeOut(100);
      $('#leave_group_img').css('opacity','');
    } 
  });

  $('#add_users_input').click(function(){
    var add_users_value=$('#put_username textarea').val();
    var add_users_gname=$('#add_users_beforeand').val();
    $('#your_conts_add_users_heading span').show();
    $('input[name="add_user_input[]"]:checked').each(function(i){
      add_users_value=add_users_value.concat(", ",$(this).val());
    });
    try{
        $('.success_addusers').remove();
        $('.fail_addusers').remove();
        $('.fail_adduser_error').remove();
      } catch(e){ }
    if(add_users_value!=''){
      $.ajax({
        url:'8c6fc81_b5ac22bdcbe18_1222da68496b_grR',
        type:'POST',
        data: 'toaddUsersValue='+add_users_value+'&gname='+add_users_gname,
        cache: false,
        success:function(response){
          $('#your_conts_add_users_heading span').hide();
          $('#contacts_list_add_users').prepend(response);
        }
      });
    }
  });

  var pageNumaddUser=0;
  var readyPageNumUser = true;
  $('#contacts_list_add_users').scroll(function(){
    if(($(this)[0].scrollHeight - $(this).scrollTop() <= $(this).outerHeight()) && $('#addUserEnd').val() == undefined && readyPageNumUser){
      pageNumaddUser+=1;
      readyPageNumUser = false;
      $(this).append('<div id="adduser_loading"><img src="/images/loading4.gif"></div>')
      $.ajax({
        url: '05a0e3c00_2326d16ca70_3b444087ed45_inf',
        type: 'POST',
        data: 'addUsermore=true&pageNumaddUser='+pageNumaddUser,
        cache: false,
        success: $.proxy(function(response){
          $(this).append(response);
          readyPageNumUser = true;
          $(this).find('#adduser_loading').remove();
        }, this)
      });
    }
  });
}

function groupReq(){      
  $(document).on('click', '#select_request label', function(){
    if($(this).find('input').length){
      var job=$(this).find('input').attr('id');
      var gkey_req_do=$(this).find('input').val();
      $('#load_group_req_span').show();
      var toshow_reqnum=$('#request_number').html() - 1;

      $.ajax({
        url: '509c601801_230238fea54a_ee838ddf62_gTD',
        type: 'POST',
        cache: false,
        data: gkey_req_do+'&job='+job,
        success: $.proxy(function(response){
          $("#folder_content_wrapper .folder_content:first-child").after(response);
          if(job=='Accept')
            $(this).next().html('<span>Accepted</span>').find('span').css({'color':'#f1f1f1', 'border-color':'rgb(212, 112, 98)', 'background':'rgb(212, 112, 98)'});
          else
            $(this).prev().html('<span>Rejected</span>').find('span').css({'color':'#f1f1f1', 'border-color':'rgb(153, 153, 153)', 'background':'rgb(153, 153, 153)'});
          $(this).remove();
          $('#load_group_req_span').hide();
          if(toshow_reqnum<1)
            $("#forback").removeClass("greq_left");
          $('#request_number').html(toshow_reqnum);
        }, this)
      });
    }
  });

  $('#group_job_span label').click(function(){
    if($(this).find('input').length){
      var job=$(this).find('input').attr('id');
      var gkey_req_do=$(this).find('input').val();
      $('#pending_req_archive').show();
      $.ajax({
        url: '509c601801_230238fea54a_ee838ddf62_gTD',
        type: 'POST',
        data: gkey_req_do+'&job='+job,
        cache: false,
        success: $.proxy(function(){
          $('#pending_req_archive').hide();
          $(this).css({'background':'rgb(212, 112, 98)', 'color':'#f1f1f1', 'border-color':'rgb(212, 112, 98)'});
          if(job=='Archives_Accept'){
            $(this).html('Accepted');
            $(this).next().remove();
          } else{
            $(this).html('Deleted');
            $(this).prev().remove();
          }
        }, this)
      });
    }
  });
}

function editDescContent(){
  var prevH='';
  var contentName='';
  var descContent='';
  var totContent='';
  var totDesc='';
  var gUrl='';

  $(document).on('click','.edit_name_rs', function(){
    gUrl=$('.edit_name_rs').attr('id');
    prevH=$('#content_desc_wrapper').height();
    $('#edit_error_rs').remove();
    contentName=$('#current_name_rs').html();
    totContent=$('#content_name_rs').html();
    descContent=$('#current_desc_rs').html();
    totDesc=$('#content_desc_main').html();
    if(descContent==undefined) descContent='';
    $(this).val('Save').attr('class','save_name_edit_rs');
    $('#content_edit_main').append('<input type="submit" id="cancel_edit_rs" value="Cancel"/>');
    if(gUrl!='personal') $('#content_name_rs').html('<input id="edit_cname_rs_text_input" type="text" value="'+contentName+'" placeholder="Group Name"/>').css('font-size','14px');
    $('#content_desc_main').html('<textarea id="edit_desc_textarea_rs" placeholder="Group Description...">'+descContent+'</textarea>');
    $('#content_desc_wrapper').css('height',prevH);
  });

  $(document).on('click', '.save_name_edit_rs', function(){
    var nameE = $('#edit_cname_rs_text_input').val();
    try{
      nameE=nameE.trim();
    } catch(e) {}
    var descE = $('#edit_desc_textarea_rs').val();
    if((nameE!=contentName && nameE!='') || descE!=descContent){
      if(nameE==contentName) nameE='';
      var type=$('#content_edit_main').attr('class');
      if(type=='folder_info_edit'){
        var toAddFolder_rs='&nbsp;|';
        var type_rs='&folder=';
      } else {
        var type_rs='&group=';
        var toAddFolder_rs='';
      }
      type_rs+=gUrl;
      $.ajax({
        url: 'b3c58b921_1f04dec05f39_72dd646ab40_edG',
        type: 'POST',
        data: "newname_rs="+nameE+"&newdesc_rs="+descE+type_rs,
        cache: false,
        success: $.proxy(function(response){
          $(this).val('Edit').attr('class','edit_name_rs');
          $('#cancel_edit_rs').remove();
          $('#content_name_rs').html(totContent).css('font-size','');
          if(nameE=='' || nameE==undefined){
            $('#content_name_rs').html(totContent).css('font-size','');
          } else {
            $('#current_name_rs').html(nameE).css('font-size','');
            $('.current_folder').html(nameE+toAddFolder_rs);
          }
          if(descE==''){
            $('#content_desc_main').html('<div class="no_desc_right_sidebar"><b>Description:</b> No Description available!</div>');
          } else $('#content_desc_main').html('<b>Description:</b> <div id="current_desc_rs">'+descE+'</div>');
        },this)
      });
    } else {
      $(this).val('Edit').attr('class','edit_name_rs');
      $('#cancel_edit_rs').remove();
      $('#content_name_rs').html(totContent).css('font-size','');
      $('#content_desc_main').html(totDesc);
      $('#content_desc_wrapper').append('<div id="edit_error_rs">Well, there was nothing to save.</div>')
    }
  });

  $(document).on('click','#cancel_edit_rs', function(){
    $(this).remove();
    $('#content_name_rs').html(totContent).css('font-size','');
    $('#content_desc_main').html(totDesc);
    $('.save_name_edit_rs').val('Edit').attr('class','edit_name_rs');
  });
}

function postsFunctions(){
  $(document).on('keypress', '.comment_post_m', function(e){
    if(e.which==13 && !e.shiftKey){
      if($(this).parent().next().find('#postUtilError').length==0){
        var postkey=$(this).attr('id');
        var comment=$(this).val();
        var commmentvalue=+($('#cf_'+postkey).html());
        
        if(comment.trim()!=''){
          $.ajax({
            url: 'f27927d5a_918b252e7fa_e186402e17b4_pF',
            type: 'post',
            cache: false,
            data: 'comment='+comment+'&postkey='+postkey,
            success: $.proxy(function(response){
              $(this).parent().next().prepend(response);
              $('#cf_'+postkey).html(commmentvalue+1);
              $(this).val('');
            },this)
          });
        }
      } else $(this).val('');
    }
  });

  $(document).on('click','.lm_comments_m a',function(){
    var postkey_loadmore=$(this).parent().attr('id');
    var ele = $(this).parent();
    var nocom=+(ele.parents('.post_wrapper_m').find('.starred_post_m span:nth-child(2)').html());
    ele.html('<img src="/images/loading3.gif" width="20px">')
    $.ajax({
      url: 'f27927d5a_918b252e7fa_e186402e17b4_pF',
      type: 'post',
      cache: false,
      data: 'loadmorecomment='+postkey_loadmore+"&nocom="+nocom,
      success:function(response){
        ele.parent().append(response);
        ele.remove();
      }
    });
  });

  var pageTotPosts=0;
  var readyPageToPost = true;
  $(window).scroll(function(){
    if($('#post_full_wrapper').length){
      if($(window).scrollTop() + $(window).height() == $(document).height() && readyPageToPost){
       pageTotPosts=pageTotPosts+1;
       readyPageToPost = false;
        var isEnd=$('#end_posts_full_m').val();
        if(isEnd!='false'){
          $('#lmf_posts').show();
          $('#post_full_wrapper').append($('#lmf_posts'));
          $.ajax({
            url: 'f27927d5a_918b252e7fa_e186402e17b4_pF',
            type: 'post',
            cache: false,
            data: 'loadmoreposts_full=true&pageTotPosts='+pageTotPosts,
            success: function(response){
              $('#lmf_posts').hide();
              readyPageToPost = true;
              $('#post_full_wrapper').append(response);
            }
          });
        }
      }
    }
  });

  var pageLPosts=0;
  var readyPageLP = true;
  $(window).scroll(function(){
    if($('#post_group_wrapper').length){
      if($(window).scrollTop() + $(window).height() == $(document).height() && readyPageLP){
       pageLPosts=pageLPosts+1;
       readyPageLP = false;
        var isEnd=$('#end_posts_full_l').val();
        if(isEnd!='false'){
          var gkey_lmp_l=$('#sub_pnw_wrapper input').attr('class');
          $('#lmf_posts_l').show();
          $('#post_group_wrapper').append($('#lmf_posts_l'));
          $.ajax({
            url: 'f27927d5a_918b252e7fa_e186402e17b4_pF',
            type: 'post',
            cache: false,
            data: 'loadmoreposts_l='+gkey_lmp_l+'&pageLPosts='+pageLPosts,
            success: function(response){
              readyPageLP = true;
              $('#lmf_posts_l').hide();
              $('#post_group_wrapper').append(response);
            }
          });
        }
      }
    }
  });

  $('.sn_post_m').click(function(){
    var postkey_star=$(this).attr('id');
    var todo_star=$(this).find('span').html();
    var star_num=+($('#sn_'+postkey_star).html());

    if(todo_star == 'Star')
      var data_star='true';
    else var data_star='false';

    $.ajax({
      url: 'f27927d5a_918b252e7fa_e186402e17b4_pF',
      type: 'post',
      data: 'star='+data_star+'&postkey_star_m='+postkey_star,
      cache: false,
      success:$.proxy(function(response){
        if($(this).parents('.post_upper_m').next().find('#postUtilError').length==0){
          if(!response){          
            if(todo_star == 'Star'){
              $('#sn_'+postkey_star).html(star_num+1);
              $(this).find('span').html('Unstar');
            } else {
              $('#sn_'+postkey_star).html(star_num-1);
              $(this).find('span').html('Star');
            }
          } else $(this).parents('.post_upper_m').next().find('.user_comments_m').prepend(response);
        } 
      },this)
    });

  });

  $('#in_g_nw_l').click(function(){
    var newp_l=$('#pnw_textarea_gd textarea').val().trim();
    if(newp_l!=''){
      var gkey_nw_l=$(this).attr('class');
      $('#sub_pnw_wrapper').prepend('<img id="sub_pnw_wrapper_img" src="/images/loading3.gif" width="18px">');
      $.ajax({
        url:'f27927d5a_918b252e7fa_e186402e17b4_pF',
        type: 'Post',
        cache: false,
        data: 'add_post_l='+newp_l+'&gkey_nw_l='+gkey_nw_l,
        success:function(response){
          if($('.no_postsftg').length)
            $('.no_postsftg').remove();
          $('#post_group_wrapper').prepend(response);
          $('#pnw_textarea_gd textarea').val('');
          $('#sub_pnw_wrapper_img').remove();
        }
      });

    }
  });

  var pagePostsHome=0;
  var ready=true;
  $('#notification_posts_wrapper').scroll(function(){
    var ele=$('#endOfPostH').length;
    if(ele==0){
      if(($(this)[0].scrollHeight - $(this).scrollTop() == $(this).outerHeight()) && ready){
        ready=false;
        $(this).append('<div id="postH_load"><img src="/images/loading4.gif" width="30px"></div>');
        pagePostsHome+=1;
        $.ajax({
          url: 'f27927d5a_918b252e7fa_e186402e17b4_pF',
          type: 'post',
          cache: false,
          data: 'loadmoreposts_home=true&pageposthomeN='+pagePostsHome,
          success:function(response){
            ele=$('#endOfPostH').length;
            $('#notification_posts_wrapper').append(response);
            $('#postH_load').remove();
            ready=true;
          }
        }); 
      }
    }
  });

  $(document).on('click', '#delete_uposts', function(){
    var postkey_delete=$(this).parents('.post_wrapper_m').attr('id');
    $(this).html('<img src="/images/loading3.gif">');
    $.ajax({
      url: 'f27927d5a_918b252e7fa_e186402e17b4_pF',
      type: 'POST',
      cache: false,
      data: 'delete_uposts='+postkey_delete,
      success: $.proxy(function(response){
        $(this).parents('.post_wrapper_m').append(response);
        $(this).parents('.post_wrapper_m').remove();
      }, this)
    });
  });
}

function  myGrpList(){
  var end=0;
  var readyMgrp=false;
  $('#show_my_groups').click(function(){
    var page=0;
    $('#my_groups_wrapper').fadeIn(100);
    $('#group_request_wrapper').fadeOut(100);
    $('#forback').css("opacity","");
    $('#main_bottom_wrapper').css('overflow','hidden');
    if(end==0 && !readyMgrp){
      $('#loading_myglist').show();
      $.ajax({
        url: 'b0f60b9657_126ddfb1004_7939aa2abdb_mGr',
        type:'POST',
        cache: false,
        data: "pagenum="+page,
        success: function(response){
          readyMgrp=true;
          $('#loading_myglist').hide();
          $('#myg_cells_wrapper').append(response);
        }
      });
    }
    var readyMyGrp = true;
    $('#myg_cells_wrapper').scroll(function(){
      var endcheck=$('#end_input_inf').val();
      if(endcheck=='false')
        end=1;
      if(($(this)[0].scrollHeight - $(this).scrollTop() <= $(this).outerHeight()+1) && end==0 && readyMyGrp){
        readyMyGrp=false;
        $('#loading_myglist').show();
        $('#myg_cells_wrapper').append($('#loading_myglist'));
        page=page+1;
        $.ajax({
          url: 'b0f60b9657_126ddfb1004_7939aa2abdb_mGr',
          type:'POST',
          cache: false,
          data: "pagenum="+page,
          success: function(response){
            readyMyGrp=true;
            $('#loading_myglist').hide();
            $('#myg_cells_wrapper').append(response);
            endcheck=$('#end_input_inf').val();
            if(endcheck=='false')
              end=1;
          }
        });
      }
    });

  });
  $('#close_mygroup_list').click(function(){
    $('#main_bottom_wrapper').css('overflow','');
    $('#my_groups_wrapper').fadeOut(100);
  });

  
}

function popupHideMyG(e){
  var container = $('#my_groups_inner');
  if (!container.is(e.target) // if the target of the click isn't the container...
        && container.has(e.target).length === 0) // ... nor a descendant of the container
    {
        $('#my_groups_wrapper').fadeOut(100);
        $('#main_bottom_wrapper').css('overflow','');
    }
}

function ViewerHide(e, x){
  if($(x).length){ 
    var container = $(x);
    var secondContainer = $('.viewerTWrap');
    var thirdContainer = $('.nextImgV');
    var fourthContainer = $('.prevImgV');

    if (!container.is(e.target) && container.has(e.target).length === 0 &&
      !secondContainer.is(e.target) && secondContainer.has(e.target).length === 0 &&
      !thirdContainer.is(e.target) && thirdContainer.has(e.target).length === 0 &&
      !fourthContainer.is(e.target) && fourthContainer.has(e.target).length === 0) 
        $('.imgView').fadeOut(100);
  }

  $('.closeViewer').click(function(){
    $(this).parents('.imgView').fadeOut(100);
  })
}

function memberHide(e){
  var container = $('#content_desc_wrapper');
  if (!container.is(e.target) // if the target of the click isn't the container...
        && container.has(e.target).length === 0) // ... nor a descendant of the container
    {
        $('#member_of_group_wrapper_rs').fadeOut(100);
        $('.memnum_rs').css('color','');
    }
}

function showMembers(){
  var pageMember=0;
  var readyGM=false;
  var urlMemnum_rs=$('.memnum_rs').attr('id');
  $('.memnum_rs').click(function(){
    if($('#member_of_group_wrapper_rs').is(':visible')){
      $('#member_of_group_wrapper_rs').fadeOut(100);
      $('.memnum_rs').css('color','');
    } else{
      $('#member_of_group_wrapper_rs').fadeIn(100);
      $('.memnum_rs').css('color','#444');
      if(($('#endGM').val() == undefined) && !readyGM){
        $('#memname_load_rs img').show();
        $.ajax({
          url: 'a0858f1319_033b86bb3b_51d87ac64762_sGM',
          post: 'POST',
          cache: false,
          data: 'pageMem='+pageMember+'&gurl='+urlMemnum_rs,
          success:function(response){
            readyGM=true;
            $('#memname_load_rs img').hide();
            $('#memname_load_rs').append(response);
          }
        });
      }
    }
    var readyMemgrp = true;
    $('#member_of_group_wrapper_rs').scroll(function(){
      if($(this)[0].scrollHeight - $(this).scrollTop() <= $(this).outerHeight() + 1  && readyMemgrp){
        if($('#endGM').val() == undefined){
          pageMember+=1;
          readyMemgrp = false;
          $('#memname_load_rs img').show();
          $('#memname_load_rs').append($('#memname_load_rs img'));
          $.ajax({
            url: 'a0858f1319_033b86bb3b_51d87ac64762_sGM',
            post: 'POST',
            cache: false,
            data: 'pageMem='+pageMember+'&gurl='+urlMemnum_rs,
            success:function(response){
              readyMemgrp = true;
              $('#memname_load_rs img').hide();
              $('#memname_load_rs').append(response);
            }
          });
        }
      }
    })
  });
}

function inviteUsers(){
  $(document).on('click','.inviteToGrp',function(e){
    e.preventDefault();
    $(this).next().slideDown();
  });
  $(document).on('click', '#submit_invite input:nth-child(2)', function(){
    $(this).parents('#newInviteList').slideUp();
  }); 
  var pageInvPrior=0;
  var readyInvitePrior=true;

  onscrollInviteList(pageInvPrior, readyInvitePrior);

  $(document).on('click', '#submit_invite input:first-child', function(){
    var invUsersoptions='';
    var eleInvite=$(this);
    var usernameToInvite=eleInvite.parents('#newInviteList').prev().attr('href');
    eleInvite.parent().prev().find('.invitationResWrapper').remove();
    $('input[name="inviteToGroups[]"]:checked').each(function(i){
      invUsersoptions=invUsersoptions.concat(", ",$(this).val());
    });
    if(invUsersoptions!=''){
      $('#submit_invite').prepend('<div class="loadInv"><img src="/images/loading3.gif"></div>');
      $.ajax({
        url: '8c6fc81_b5ac22bdcbe18_1222da68496b_grR',
        type: 'POST',
        data: 'inviteToGroupsOptions='+invUsersoptions+"&usernameToInvite="+usernameToInvite,
        cache: false,
        success: function(response){
          eleInvite.parent().siblings('.grpListInvite').prepend(response);
          $('#submit_invite').find('.loadInv').remove();
        }
      }); 
    }
  });
}

function LoadInPosts(){
  $.ajax({
    url: 'f27927d5a_918b252e7fa_e186402e17b4_pF',
    type: 'POST',
    data: 'addPstsSec=true',
    cache: false,
    success : function(response){
      $('#notification_posts_wrapper').html(response);
    }
  });
  showNumNewActivity();
}

function showNumNewActivity(){
  $.ajax({
    url: 'f27927d5a_918b252e7fa_e186402e17b4_pF?'+$.now(),
    type: 'POST',
    data: 'numMainPost=true',
    cache: false,
    success: function(response){
      $('#new_notH').html(response);
    }
  });
}

function showUserPosts(){
  $.ajax({
    url: '01f2fa0f7_7ba165d50be6_94351bebdd1_rfN?'+$.now(),
    type: 'POST',
    data: 'postsHome=true',
    cache: false,
    success: function(response){
      $('#notification_posts_wrapper').prepend(response);
      if(response && $('#noUserph').length)
        $('#noUserph').remove();
    }
  });
  $.ajax({
    url: '01f2fa0f7_7ba165d50be6_94351bebdd1_rfN',
    type: 'POST',
    cache: false,
    data: 'countPH=true',
    dataType: 'json',
    timeout: 7000,
    success: function(response){
      var ele = parseInt(response[0], 10);
      if(ele > 0){
        var numP=+($('#noti_numbers a').html());
        $('#noti_numbers a').html(ele+numP);
        $('#noti_numbers').show();
      }

    }
  });
}

function showPosts(){
  $.ajax({
    url: '01f2fa0f7_7ba165d50be6_94351bebdd1_rfN?'+$.now(),
    type: 'POST',
    cache: false,
    data: 'notifications=true',
    timeout: 7000,
    success:function(response){
      $('#notes_content_wrap').prepend(response);
      if(response && $('#no_new_notification').length)
        $('#no_new_notification').remove();
    }
  });

}

function changeTimeStamp(){
  $.ajax({
    url: 'fbf6479f36_4e58609ce2_2fc443ea2764_cT?'+$.now(),
    type: 'POST',
    dataType: 'json',
    cache: false,
    timeout: 7000,
    success: function(response){
      if(response=='Logout'){
        window.location.href="/end_session.php";
      }
    }
  });
}

function showNewReq(){
  $.ajax({
    url: '01f2fa0f7_7ba165d50be6_94351bebdd1_rfN?'+$.now(),
    type: 'POST',
    data: 'newReq=true',
    cache: false,
    success: function(response){
      $('#request_cells_wrapper').prepend(response);
    }
  });
  $.ajax({
    url: '01f2fa0f7_7ba165d50be6_94351bebdd1_rfN',
    type: 'POST',
    data: 'countReq=true',
    cache: false,
    dataType: 'json',
    timeout: 7000,
    success: function(response){
      try{
        var ele = parseInt(response[0], 10);
        if(ele > 0){
          var numP=+($('#request_number').html());
          $('#request_number').html(ele+numP);
          $('#forback').addClass('greq_left');
          if($('#no_req_left').length)
            $('#no_req_left').remove();
        }
      } catch(e) {}
    }
  });
}

var readySCHP = true;

function searchContentsFunction(){
  var sch_query=$('#search_m_inp_m').val().trim(); 
  if(sch_query!=''){
    if($('.search_gm_Wrapper h3 .searchLoad').length === 0) $('.search_gm_Wrapper h3').append('<img class="searchLoad" src="/images/loadsearch.gif">');
    if($('.search_pm_wrapper h3 .searchLoad').length === 0) $('.search_pm_wrapper h3').append('<img class="searchLoad" src="/images/loadsearch.gif">');
    if($('.search_cm_wrapper h3 .searchLoad').length === 0) $('.search_cm_wrapper h3').append('<img class="searchLoad" src="/images/loadsearch.gif">');
    if($('.search_tm_wrapper h3 .searchLoad').length === 0) $('.search_tm_wrapper h3').append('<img class="searchLoad" src="/images/loadsearch.gif">');
    $.ajax({
      url: '9712d9c1_2be4d8c9318e69_1b526557b8_sF',
      data: 'searchgroups='+sch_query,
      type: 'GET',
      success: function(response){
        $('.search_gm_Wrapper h3').find('img').remove();
        $('.grp_sch_content_wrapper').html(response);
      }
    });
    var start_psch=0;
    $.ajax({
      url: '9712d9c1_2be4d8c9318e69_1b526557b8_sF',
      data: {searchpeople:sch_query, strt_psch:start_psch},
      type: 'GET',
      success: function(response){
        $('.search_pm_wrapper h3').find('img').remove();
        $('.pm_sch_content_wrapper').html(response);
        $('.pm_sch_content_wrapper').scroll(function(){
           if(readySCHP && $(this)[0].scrollHeight - $(this).scrollTop() == $(this).outerHeight() && $('#endschouterpsch').length === 0){
              readySCHP=false;
              $('.search_pm_wrapper h3').append('<img class="searchLoad" src="/images/loadsearch.gif">');
              start_psch+=1;
              $('.pm_sch_content_wrapper').append('<div class="schouterpschload"><img src="/images/loading3.gif" width="20px"></div>');
              $.ajax({
                url: '9712d9c1_2be4d8c9318e69_1b526557b8_sF',
                data: {searchpeople:sch_query, strt_psch:start_psch},
                type: 'GET',
                success: function(response){
                  $('.search_pm_wrapper h3').find('img').remove();
                  $('.schouterpschload').remove();
                  $('.pm_sch_content_wrapper').append(response);
                  readySCHP=true;
                }
              })
           }
        })
      }
    });
    $.ajax({
      url: '9712d9c1_2be4d8c9318e69_1b526557b8_sF',
      data: 'searchcontent='+sch_query,
      type: 'GET',
      success: function(response){
        $('.search_cm_wrapper h3').find('img').remove();
        $('.content_sch_content_wrapper').html(response);
      }
    });
    $.ajax({
      url: '9712d9c1_2be4d8c9318e69_1b526557b8_sF',
      data: 'searchtags='+sch_query,
      type: 'GET',
      success: function(response){
        $('.search_tm_wrapper h3').find('img').remove();
        $('.tag_sch_content_wrapper').html(response);
      }
    });
  }
}

function searchContents(){

    $('#search_m_inp').keyup(function(e){
      $('#search_m_wrapper').fadeIn(100);
      var cursearchval=$(this).val();
      $(this).val('');
      $('#search_m_inp_m').val(cursearchval).focus();
      $('body').css('overflow-y', 'hidden');
    });

    $('#cancel_search_m').click(function(){
      $('#search_m_wrapper').fadeOut(100);
      $('body').css('overflow-y', '');
    });

    $('#search_m_inp_m').keypress(function(e){
      if(e.which==13){
        $('#search_res_m_wrapper').show();
        searchContentsFunction();
      }
    });
    $('.start_sch_m').click(function(){
      $('#search_res_m_wrapper').show();
      searchContentsFunction();
    });

    $('#search_inner').keyup(function(){
      var search_inner_query=$(this).val();
      if(search_inner_query != ''){
        if($('.searchLoadI').length === 0) $('.search_inner_SPFO').append('<img class="searchLoadI" src="/images/loadsearch.gif">');
        var groupkey_schinner=$(this).parent().attr('id');
        var folderkey_schinner=$(this).next('.fkey_sch_inner').attr('id');
        if(folderkey_schinner==undefined)
          var grpkey_schin=groupkey_schinner;
        else var grpkey_schin=groupkey_schinner+'&fkey_schin='+folderkey_schinner;
        $('#sch_res_inner_wrapper').show();
        $('#in_folders').css('visibility', 'hidden');
        $.ajax({
          url: '9712d9c1_2be4d8c9318e69_1b526557b8_sF',
          type: 'GET',
          data: 'search_inner='+search_inner_query+'&gkey_schin='+grpkey_schin,
          success:function(response){
            $('#sch_res_inner_wrapper').html(response);
            $('.search_inner_SPFO').find('.searchLoadI').remove();
          }
        });
      } else {
        $('#in_folders').css('visibility', '');
        $('#sch_res_inner_wrapper').hide();
      }
    });

    $('#search_myq_input').keyup(function(){
      var myg_sch_query=$(this).val().trim();
      if(myg_sch_query!=''){
        $('#myg_cells_wrapper').hide();
        $('#myq_srch_wrapper').show();
        if($('.searchLoadM').length === 0) $('.giamyglist').append('<img class="searchLoadM" src="/images/loadsearch.gif">');
        $.ajax({
          url: '9712d9c1_2be4d8c9318e69_1b526557b8_sF',
          type: 'GET',
          data: 'searchmyg='+myg_sch_query,
          success: function(response){
            $('#myq_srch_wrapper').html(response);
            $('.giamyglist').find('.searchLoadM').remove();
          }
        });

      } else {
        $('#myg_cells_wrapper').show();
        $('#myq_srch_wrapper').hide();
      }
    });
}

function uploadContents(){

  setTimeout(function(){
    $('.eu_fum_fadeo').fadeOut(500);
    setTimeout(function(){
      $('.eu_fum_fadeo').remove();
    }, 500)
  }, 8000)

  var group_url=$('#ucba').val();
  $('#ucba').remove();
  var folder_url=$('#ucaa').val();
  $('#ucaa').remove();

  var optMakeFolder='<div id="make_folder_suwrapper">';
  optMakeFolder+='<input type="text" name="new_folder_cupload" id="makefold_inp_upl" placeholder="Name your folder.."/>';
  optMakeFolder+='<div class="makefolder_notes">';
  optMakeFolder+='Name the folder and upload the contents to the new folder...or you could <span class="soupl_du switch_upl_opt">upload contents directly</span> to the present directory.';
  optMakeFolder+='</div>';
  optMakeFolder+='<input type="file" id="upload_inp" class="upload_content_inp" multiple="multiple" name="upload_contents[]"/>';
  optMakeFolder+='<label class="upload_content_label" for="upload_inp">Upload Contents! <img src="/images/uploadIcon.png"></label>';
  optMakeFolder+='</div>';

  var optDirectUpload='<div id="direct_upload_suwrapper">';
  optDirectUpload+='<input type="file" id="upload_inp" class="upload_content_inp" multiple="multiple" name="upload_contents[]"/>';
  optDirectUpload+='<label class="ducl_label upload_content_label" for="upload_inp">Upload Contents! <img src="/images/uploadIcon.png"></label>';
  optDirectUpload+='<div class="ducl_mf_notes makefolder_notes">';
  optDirectUpload+='Select multiple files and upload to this directory...or you could <span class="soupl_mf switch_upl_opt">make a new folder</span> and then upload it over there.';
  optDirectUpload+='</div>';
  optDirectUpload+='</div>';

  $('#display_pic_input_first').on('change',function(){
    var newPic=this.files;
    var newPicSize=newPic[0].size;

    var allowedFormats = ['jpg', 'png', 'jpeg', "gif", "GIF", "JPG", "JPEG", "PNG"];
    var maxPicSize=1024 * 1034 * 2;
    if(newPicSize<=maxPicSize){
      var newPicName=newPic[0].name;
      var typeFile=newPicName.split('.').pop();
      if(jQuery.inArray( typeFile, allowedFormats ) === -1){
        $('#error_upload').html('Sorry, only JPG, JPEG, PNG & GIF files are allowed.');
      } else {
        $('#error_upload').html('');
        loading_gif();
        this.form.submit();
      }
    } else {
      $('#error_upload').html('Sorry, your file is too large. Please upload a file less than 2MB.');
    }
  })

  $('#make_folder_wrapper').click(function(){
    $(this).parents('#upload_content_ui_form_wrapper').html(optMakeFolder).parents('#upload_content_ui').animate({'top':'-105px', 'right':'-225px'}).find('.con_upload').attr('id', 'mupload');
  });

  $('#direct_upload_wrapper').click(function(){
    $(this).parents('#upload_content_ui_form_wrapper').html(optDirectUpload).parents('#upload_content_ui').animate({'top':'-105px', 'right':'-225px'}).find('.con_upload').attr('id', 'dupload');
  }); 

  $(document).on('click', '.soupl_mf', function(){
    $(this).parents('#upload_content_ui_form_wrapper').html(optMakeFolder);
  });

  $(document).on('click', '.soupl_du', function(){
    $(this).parents('#upload_content_ui_form_wrapper').html(optDirectUpload);
  });

  $('#create_group_submit').click(function(e){
    if($(this).siblings('#group_name').val().trim()=='')
      return false;
  });

  $(document).on('change', '#upload_inp', function(e){
    var inp = this;
    var sizeFile = 0;
    var maxSize=100 * 1024 * 1024;

    for (var i = 0; i < inp.files.length; ++i)
      sizeFile += inp.files.item(i).size;
    if(sizeFile>maxSize) $(this).siblings('.makefolder_notes').append('<div id="uc_error">No more than 100MB at a time!</div>');
    else {
      $('#uc_error').remove();
      $(this).parents('#upload_content_ui_form_wrapper').css('padding-bottom', '0').parents('form').submit();
      $(this).next('label').remove();
      $(this).remove();
      $('.progressBarWrapper').show();
    }
  }); 

  $(document).on('keypress', '#makefold_inp_upl', function(e){
    if(e.which == 13){
      return false;
    }
  })

  var bar = $('.progressBar');
  var pDone = $('#pDonecent');
  
  /* submit form with ajax request using jQuery.form plugin */
  $('.con_upload').ajaxForm({

    /* reset before submitting */
    beforeSend: function() {
      bar.width('0%');
      pDone.html('0%');
    },

    /* progress bar call back*/
    uploadProgress: function(event, position, total, percentComplete) {
      var pVel = percentComplete+"%";
      bar.width(pVel);
      pDone.html(pVel); 
    },

    /* complete call back */
    complete: function() {
      location.reload();
    }
  });
}

function downloadPopUp(showAlertDownloadFolder, timeOutPopUpD){
  if($('#download_started').length){
    clearTimeout(iniSetTimeDownAlert);
    $('#download_started').html(showAlertDownloadFolder);
  } else $('#main_bottom_wrapper').prepend('<div id="download_started">'+showAlertDownloadFolder+'</div>');
  iniSetTimeDownAlert=setTimeout(function(){
    $('#download_started').fadeOut(500);
    setTimeout(function(){
      $('#download_started').remove();
    }, 500)
  }, timeOutPopUpD)
}

var changeImgViewerN = 0;
var changeImgViewerP = 0;

var orderN = 0;
var orderP = 0;

var currElementImgV = ''

function downloadContent () {
  var showInp=true;
  file_name='';
  $(document).on('click', '.file_contents', function(e){
    e.preventDefault();
  })
  $(document).on('dblclick', '.file_contents', function(){
    if(showInp){
      showInp=false;
      var fileElement = $(this).find('.files_info');
      file_name=fileElement.html().trim();
      fileElement.html('<input class="renameFile" type="text" value="'+file_name+'"/>');
      fileElement.find('input').focus();
    }
  })
  $(document).on('blur','.renameFile', function(){
    showInp=true;
    var newName=$(this).val().trim();
    if(newName!=file_name && newName!=''){
      var fileKey=$(this).parents('a').attr('href');
      $(this).parent().html(newName);
      $.ajax({
        url: 'b3c58b921_1f04dec05f39_72dd646ab40_edG',
        type: 'POST',
        data: 'fileKey='+fileKey+'&changeFileName='+newName
      });
    } else {
      $(this).parent().html(file_name);
    }
  })
  $(document).on('click', '.download_icon', function(e){
    e.preventDefault();
  })
  $(document).on('click', '.files_icon .download_icon', function(){
    var file_key=$(this).parents('a').attr('href');
    location='file_'+file_key;
  })
  $(document).on('click', '.folder_icon .download_icon', function(){
    var folder_key=$(this).parents('a').attr('href');
    var indexOfAnd=folder_key.lastIndexOf('&');
    if(indexOfAnd!== -1) folder_key=folder_key.substring(indexOfAnd+1);

    var showAlertDownloadFolder='We are wrapping up the folder into a zip, the download will start as soon as we are done wrapping it up! It may take some time, please be patient.';
    var emptyFolder='Cannot download empty folders!';

    downloadPopUp(showAlertDownloadFolder, 5000);

    $.ajax({
      url: '19ea9a346c_0da02aefa6_2f4648622880_dwN',
      type: 'POST',
      dataType: 'json',
      data: 'folder_key='+folder_key,
      success: function(response){
        if(response == 0){
          downloadPopUp(emptyFolder, 3000);
        } else location = 'folder_'+folder_key;
      }
    });
  }) 

  var mxViewerH = $(window).height() *0.8;
  var mxViewerW = $(window).width() * 0.8;

  $(document).on('click', '.pdfViewer', function(e){
    var downloadLink=$(this).find('.download_icon');
    if (!downloadLink.is(e.target) && downloadLink.has(e.target).length === 0){
      var pdfUrl='_'+$(this).parent().attr('href');
      var pdfName=$(this).next().html();
      var popUpContent='<div class="viewerTWrap"><h2>'+pdfName+'</h2><div class="dLinkPV"><a href="file'+pdfUrl+'"><img class="viewerIconIm" src="/images/download.png"></a><a class="closeViewer"><img class="viewerIconIm" src="/images/canWhite.png"></a></div></div><div class="ImgWrapPV"><object class="pdfViewerOBJ" data="'+pdfUrl+'" type="application/pdf" width="950px" height="530px"><p>Looks like your browser doesn\'t support/have any pdf viewer. Sadly, dowloading seems to be the only option OR we would recommend you to get a pdf viewer for your browser!.</p></object></div>';
      $('.imgView').fadeIn(200).find('.imgViewPopUp').html(popUpContent);

      $('.ImgWrapPV object').css({'width':mxViewerW+'px', 'height':mxViewerH+'px'});
    }
  })

  $(document).on('click', '.pUImgViw', function(e){
    var downloadLink=$(this).siblings('.download_icon');
    if (!downloadLink.is(e.target) && downloadLink.has(e.target).length === 0){
      var imgSrc=$(this).attr('src');
      imgSrc = imgSrc.substring(3);
      var imgName=$(this).parent().next().html();
      var popUpContent='<div class="viewerTWrap"><h2>'+imgName+'</h2><div class="dLinkPV"><a href="file'+imgSrc+'"><img class="viewerIconIm" src="/images/download.png"></a><a class="closeViewer"><img class="viewerIconIm" src="/images/canWhite.png"></a></div></div><div class="ImgWrapPV"><img src="'+imgSrc+'"/></div>';
      $('.imgView').fadeIn(200).find('.imgViewPopUp').html(popUpContent);
      
      $('.ImgWrapPV img').css({'max-width':mxViewerW+'px', 'max-height':mxViewerH+'px'});
      var Imgkey=imgSrc.replace('_', '');
      currElementImgV = Imgkey;
      nextImg(Imgkey);
      prevImg(Imgkey);
    }
  })

  $(document).on('click', '.nextImgV', function(){
    var thisnextImgV = $(this);
    nextImgVSlide(thisnextImgV);
  })

  $(document).on('click', '.prevImgV', function(){
    var thisprevImgV = $(this);
    prevImgVSlide(thisprevImgV);
  })

  $(document).keydown(function(e){
    if($('.imgView').length && $('.prevImgV').length && e.which == 37){
      var thisprevImgV = $('.prevImgV');
      prevImgVSlide(thisprevImgV);
    }
    if($('.imgView').length && $('.nextImgV').length && e.which == 39){
      var thisnextImgV = $('.nextImgV');
      nextImgVSlide(thisnextImgV);
    }
  })
}

function prevImgVSlide(thisprevImgV){
  var newImN=thisprevImgV.find('.prevImgArr').siblings('.prevImN').val();
  var newImK=thisprevImgV.find('.prevImgArr').siblings('.prevImK').val();
  if(newImK !== currElementImgV){
    $('.ImgWrapPV img').attr('src', '');
    switchViewerInnerSwitch(newImK, newImN, thisprevImgV);
    currElementImgV = newImK;
  }
}

function nextImgVSlide(thisnextImgV){
  var newImN=thisnextImgV.find('.nextImgArr').siblings('.nextImN').val();
  var newImK=thisnextImgV.find('.nextImgArr').siblings('.nextImK').val();
  if(newImK !== currElementImgV){
    $('.ImgWrapPV img').attr('src', '');
    switchViewerInnerSwitch(newImK, newImN, thisnextImgV);
    currElementImgV = newImK;
 }
}

function switchViewerInnerSwitch(newImK, newImN, thisNext){
  $('.ImgWrapPV img').attr('src', '_'+newImK);
  $('.viewerTWrap h2').html(newImN);
  thisNext.siblings('.imgViewPopUp').find('.dLinkPV a:first-child').attr('href', 'file_'+newImK);
  nextImg(newImK);
  prevImg(newImK);
}

function nextImg(Imgkey){
  changeImgViewerN = 0;
  var currUrl = window.location.href;
  var baseUrl = currUrl.split(/[\\/]/).pop();
  var splitUrl = baseUrl.split('&');
  var folderKey = splitUrl.pop();
  var groupKey = splitUrl[0];
  if(groupKey == undefined) groupKey = folderKey;
  $.ajax({
    url:'9712d9c1_2be4d8c9318e69_1b526557b8_sF',
    type: 'POST',
    dataType: 'json',
    data:{searchNextImg:Imgkey, groupKey:groupKey, folderKey:folderKey},
    success: function(response){
     // $('#in_folders').prepend(response);
      if(response){
        changeImgViewerN = 1;
        if(orderN == 0){
          orderN = 1;
          $('.imgView').prepend('<div class="nextImgV"><img class="nextImgArr" src="/images/nextImg.png"><input type="hidden" class="nextImK" value="'+response[0]+'"/><input type="hidden" class="nextImN" value="'+response[1]+'"/></div>');
        } else {
          var division = $('.nextImgV');
          division.find('.nextImK').val(response[0]);
          division.find('.nextImN').val(response[1]);
        }
      } 
    }
  })
  .always(function() {
    if(changeImgViewerN == 0){
      $('.nextImgV').remove();
      orderN = 0;
    }
  });
}

function prevImg(Imgkey){
  changeImgViewerP = 0;
  var currUrl = window.location.href;
  var baseUrl = currUrl.split(/[\\/]/).pop();
  var splitUrl = baseUrl.split('&');
  var folderKey = splitUrl.pop();
  var groupKey = splitUrl[0];
  if(groupKey == undefined) groupKey = folderKey;
  $.ajax({
    url:'9712d9c1_2be4d8c9318e69_1b526557b8_sF',
    type: 'POST',
    dataType: 'json',
    data:{searchPrevImg:Imgkey, groupKey:groupKey, folderKey:folderKey},
    success: function(response){
      if(response){
        changeImgViewerP = 1;
        if(orderP == 0){
          orderP = 1;
          $('.imgView').prepend('<div class="prevImgV"><img class="prevImgArr" src="/images/prevImg.png"><input type="hidden" class="prevImK" value="'+response[0]+'"/><input type="hidden" class="prevImN" value="'+response[1]+'"/></div>');
        } else {
          var division = $('.prevImgV');
          division.find('.prevImK').val(response[0]);
          division.find('.prevImN').val(response[1]);
        }
      }
    }
  })
  .always(function() {
    if(changeImgViewerP == 0){
      $('.prevImgV').remove();
      orderP = 0;
    }
  });
}

function deleteContent(){
  $(document).on('click', '.delete_content_icon', function(e){
    e.preventDefault();
  })
  $(document).on('click', '.file_contents .delete_content_icon', function(e){
    
    var file_url=$(this).parents('a').attr('href');
    var presenturl=window.location.href;
    var group_url=presenturl.split(/[\\/]/).pop();
    group_url=group_url.split(/[\\/&]/)[0];

    $(this).css({'background':'url(/images/loading5.gif)', 'background-size':'cover'});

    $.ajax({
      url: '19ea9a346c_0da02aefa6_2f4648622880_dwN',
      type: 'post',
      data: 'fileDelUrl='+file_url+'&groupDelUrl='+group_url,
      success: $.proxy(function(response){
        $(this).parents('.file_contents').remove();
      }, this)
    });
  })

  $(document).on('click', '.folder_content .delete_content_icon', function(e){
    downloadPopUp("This folder will be deleted, this might take some time, please be patient. Once deleted, you won't see the folder anymore!", 3800);
    $(this).css({'background':'url(/images/loading5.gif)', 'background-size':'cover'});
    var folder_key=$(this).parents('a').attr('href').split('&').pop();

    $.ajax({
      url: '19ea9a346c_0da02aefa6_2f4648622880_dwN',
      type: 'POST',
      data: 'folderDelUrl='+folder_key,
      success: $.proxy(function(){
        $(this).parents('.folder_content').remove();
      }, this)
    });
  })
}

function autoRefresh(){
  setInterval(function(){
    showPosts();
    showNewReq();
    showNumNewActivity();
    showUserPosts();
    changeTimeStamp();
  }, 5000);
}

$(window).resize(function(){
  align();
})

function defaultDrag(){
 // alert('here!');
  $(document).on('dragenter', function (e) 
  {
      e.stopPropagation();
      e.preventDefault();
  });
  $(document).on('dragover', function (e) 
  {
    e.stopPropagation();
    e.preventDefault();
  });
  $(document).on('drop', function (e) 
  {
      e.stopPropagation();
      e.preventDefault();
      $('#main_content').css({'background':''});
  });
}

$(window).load(function(){
	align();
})

$(document).ready(function(){
 // $.ajaxSetup({cache: false});
  $(document).mouseup(function (e)
  {
      groupReqToHide(e, "#group_request_wrapper", "#forback");
      groupReqToHide(e, "#add_users_main", "#add_users_img");
      groupReqToHide(e, "#leave_group_main", "#leave_group_img");
      groupReqToHide(e, "#paste_post_wrapper", "#paste_post_img");
      ViewerHide(e, '.ImgWrapPV object');
      ViewerHide(e, '.ImgWrapPV img');
      popupHideMyG(e);
      memberHide(e);
  });
  searchContents();
  defaultDrag();
  uploadContents();
  downloadContent();
  deleteContent();
  inviteUsers();
  LoadInPosts();
  infiniteNotes();
  autoRefresh();
  showMembers();
  postsFunctions();
  editDescContent();
  requestsShow();
  myGrpList();
  infiniteContacts();
  addContacts();
  align();
  groupReq();
  show_addUsers();
  infiniteScrollMain('#in_folders');
  infiniteScrollMain('#folders');
  notificationHover();
  notificationClick();
  plusShow();
  uploadShow();
  NMnameClick();
});