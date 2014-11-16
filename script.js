jQuery.browser = {};
(function () {
    jQuery.browser.msie = false;
    jQuery.browser.version = 0;
    if (navigator.userAgent.match(/MSIE ([0-9]+)\./)) {
        jQuery.browser.msie = true;
        jQuery.browser.version = RegExp.$1;
    }
})();

var $_GET = {};
// getting variable $_GET
document.location.search.replace(/\??(?:([^=]+)=([^&]*)&?)/g, function () {
    function decode(s) {
        return decodeURIComponent(s.split("+").join(" "));
    }
    $_GET[decode(arguments[1])] = decode(arguments[2]);
});

 // DOCUMENT.READY   
$(document).ready(function(){

    $('#banner-fade').bjqs({
        'height' : 320,
        'width' : 620,
        'responsive' : true
    });

    $('#helpHint').each(function(){
        var a = $(this).attr('title');
        var b = a.replace('<br />','\n');
        $(this).attr('title', b);
    });

    // toggle main bar
    $('.toggle-main-bar').click(function() {
        $('#mainBar').toggleClass('narrow');
        $('#top-bar, #wrapper').toggleClass('wide');
    })
    
    $('#wrapper').on( 'keyup', 'textarea', function (){ 
        $(this).height( 50 );
        $(this).height( this.scrollHeight );
    });
    $('#wrapper').on( 'click', 'textarea', function (){ 
        $(this).height( 50 );
        $(this).height( this.scrollHeight );
    });
    //$('#container').find( 'textarea' ).keyup();
    
    //$.adaptiveBackground.run()
    $("div.NavSubhead").hide();   
    $('div#groupRequests').hide();
    $('div#editInfo').hide();
    $('div#editPhoto').hide();
    $('div#actionOk').hide();
    $('div#addReply').hide();
    $('div#replyEdit').hide();
    $('div#fileInfo').hide();
    $('div#editFileInfo').hide();
    $('div#addCategory').hide();
    $('div#delCategory').hide();
    $('div#makeReference').hide();
    $('div#mainResults').hide();
    $('div#novinkaComments').hide();
    $('div#novinkaEdit').hide();
    $('div#novinkaComments.'+$_GET['newsId']).show();
    $('div#commentEdit').hide();
    $('div#addFile').hide();   
    $('div#actionOk').slideDown(600);
    $('div#actionOk').delay(2800).slideUp(750);
    $('div#categoryTree').hide();

     // File Info
    $(function(){
            $(".showCategoryTree").click(function(){ 
                $('div#categoryTree').toggle();
            });
        });
    
    $(".fancybox").fancybox({
            'titleFormat' : function(title, currentArray, currentIndex, currentOpts) {
                return '<span id="fancybox-title-over">' + (currentIndex + 1) + ' / ' + currentArray.length + (title.length ? ' &nbsp; ' + title : '') + '</span>';
            }
    });
    
    // PHOTO
    $(function(){
            $(".editPhotoBtn").click(function(){
                $('div#editPhoto').fadeToggle();
                $('div#editPhoto').css('z-index', '100');
                $('div#editPhoto').css('position', 'absolute');
            });
        });
        
    $(function() {
        $(".exitEditPhoto").click(function(){
            $('div#editPhoto').hide();
        });
    });    
    // INFO
    $(function(){
            $(".editInfoBtn").click(function(){
                $('div#curInfo').hide();
		$('span.editInfoBtn').hide();
                $('div#editInfo').fadeToggle();
                $('div#editInfo').css('z-index', '100');
                $('div#editInfo').css('position', 'absolute');
            });
        });
        
    $(function() {
        $(".exitEditInfo").click(function(){
            $('div#editInfo').hide();
            $('div#curInfo').fadeToggle();
	    $('span.editInfoBtn').fadeToggle();
        });
    });

    // NEWS EDIT
    $(function(){
            $(".editNewsBtn").click(function(){
                var id = $(this).attr('name');
                $('div#novinkaContent.'+id).hide();
		        $(this).hide();
                $('div#novinkaEdit.'+id).fadeToggle();
            });
        });
        
    $(function() {
        $(".exitEditNews").click(function(){
            var id = $(this).attr('name');
            $('div#novinkaContent.'+id).fadeToggle();
	        $('button.editNewsBtn[name='+id+']').fadeToggle();
            $('div#novinkaEdit.'+id).hide();
        });
    });
    
    // File Info
    $(function(){
            $(".showFileInfo").click(function(){
                var id = $(this).attr('name');
                $('div#fileInfo.'+id).toggle();
            });
        });
    // FILE INFO EDIT
    $(function(){
            $(".editFileInfoBtn").click(function(){
                var id = $(this).attr('name');
                $('div#curFileInfo.'+id).hide();
                $('div#editFileInfo.'+id).toggle();
            });
        });  
        
     $(function(){
            $(".exitEditFileInfoBtn").click(function(){
                var id = $(this).attr('name');
                $('div#editFileInfo.'+id).hide();
                $('div#curFileInfo.'+id).toggle();       
            });
        });   
        
    // COMMENT EDIT
    $(function(){
            $(".editCommentBtn").click(function(){
                var id = $(this).attr('name');
                $('div#commentContent.'+id).hide();
		        $(this).hide();
                $('div#commentEdit.'+id).fadeToggle();
            });
        });
        
    $(function() {
        $(".exitEditComment").click(function(){
            var id = $(this).attr('name');
            $('div#commentContent.'+id).fadeToggle();
	        $('button.editCommentBtn[name='+id+']').fadeToggle();
            $('div#commentEdit.'+id).hide();
        });
    }); 
    
    // NEWS COMMENTS  
    $(function(){
            $(".commentsBtn").click(function(){
                var id = $(this).attr('name');
                $('div#novinkaComments.'+id).toggle();
            });
    });
    
    // addFile  
    $(function(){
            $(".addFileBtn").click(function(){
                $('div#addFile').toggle();
                $(this).hide();
            });
    });
    
    $(function(){
            $(".exitAddFileBtn").click(function(){
                $('div#addFile').hide();
                $('.addFileBtn').show();
            });
    });
    
    // FORUM - REPLY EDIT
    $(function(){
            $(".replyEditBtn").click(function(){
                var id = $(this).attr('name');
                $('div#replyContent.'+id).hide();
		        $(this).hide();
                $('div#replyEdit.'+id).fadeToggle();
            });
        });
        
    $(function() {
        $(".exitReplyEdit").click(function(){
            var id = $(this).attr('name');
            $('div#replyContent.'+id).fadeToggle();
	        $('button.replyEditBtn[name='+id+']').fadeToggle();
            $('div#replyEdit.'+id).hide();
        });
    }); 
    
    // makeReference
    $(function(){
            $(".showMakeReference").click(function(){
                $('div#makeReference').fadeToggle();
                $("input.referenceText").val(tinyMCE.activeEditor.selection.getContent({format : 'text'}));
            });
        });
     
    $('#makeReferenceBtn').click(function() {         // OSETRIT VSTUPY
        var text = "[[" + $('.referenceImg').val() + "|" + $('.referenceText').val() + "]]";
        tinymce.activeEditor.execCommand('mceInsertContent', false, text);
        $('div#makeReference').hide();
    });
    
    // Table Forum TR href
    $('table tr').click(function(){
	if ($(this).attr('href') != null)
        	window.location = $(this).attr('href');
        return false;
    });

    // addReply
    $(function(){
            $(".btnAddReply").click(function(){
                $('div#addReply').toggle();
            });
        });

    // getGroupRequests
     $(function(){
            $(".btnGetRequests").click(function(){
                $('div#groupRequests').toggle();
            });
        });
    // addCategory
    $(function() {
        $(".addCategoryBtn").click(function() {
            $('div#addCategory').toggle();
        });
    });
    // delCategory
    $(function() {
        $(".delCategoryBtn").click(function() {
            $('div#delCategory').toggle();
        });
    });
    // filter Files User
    $('#userSelectFiles').change(function(){
            var userId = $("input#userId").val();
            $.ajax({
                type: "GET",
                url: "showFiles.php",
                data: 'groupId=' + $('#userSelectFiles').val() +'&userId=' + userId,
                success: function(msg){
                    $('#allFiles').html(msg);
                    $('div#fileInfo').hide();
                    $('div#editFileInfo').hide();
                    // File Info
                    $(".showFileInfo").click(function(){
                        var id = $(this).attr('name');
                        $('div#fileInfo.'+id).toggle();
                    });
                    
                    // FILE INFO EDIT
                    $(".editFileInfoBtn").click(function(){
                        var id2 = $(this).attr('name');
                        $('div#curFileInfo.'+id2).hide();
                        $('div#editFileInfo.'+id2).toggle();
                    }); 
                
                    $(".exitEditFileInfoBtn").click(function(){
                        var id3 = $(this).attr('name');
                        $('div#editFileInfo.'+id3).hide();
                        $('div#curFileInfo.'+id3).toggle();       
                    });            
                }
        }); // Ajax Call
    }); //event handler
    
    // filter Notes user
    $('#userSelectNotes').change(function(){
            var userId = $("input#userId").val();
            var visual = $("input#visual").val();
            $.ajax({
                type: "GET",
                url: "getUserNotes.php",
                data: 'groupId=' + $('#userSelectNotes').val() +'&userId=' + userId+'&visual=' + visual,
                success: function(msg){
                    $('#allNotes').html(msg);
                }
        }); // Ajax Call
    }); //event handler
    
    // filter Forum user
    $('#userSelectForum').change(function(){
            var userId = $("input#userId").val();
            var visual = $("input#visual").val();
            $.ajax({
                type: "GET",
                url: "getForum.php",
                data: 'groupId=' + $('#userSelectForum').val() +'&userId=' + userId + '&page=home',
                success: function(msg){
                    $('#allForum').html(msg);
                }
        }); // Ajax Call
    }); //event handler
    
    // MAIN SEARCH 
    function search (msg,id) {
        $(id).html(msg);
        $('div#novinkaComments').hide();
        $('div#novinkaEdit').hide();
        $('div#novinkaComments.'+$_GET['newsId']).show();
        $('div#commentEdit').hide();
        $('div#fileInfo').hide();
        $('div#editFileInfo').hide();
        // File Info
        $(".showFileInfo").click(function(){
            var id = $(this).attr('name');
            $('div#fileInfo.'+id).toggle();
        });
        
        // FILE INFO EDIT
        $(".editFileInfoBtn").click(function(){
            var id2 = $(this).attr('name');
            $('div#curFileInfo.'+id2).hide();
            $('div#editFileInfo.'+id2).toggle();
        }); 
    
        $(".exitEditFileInfoBtn").click(function(){
            var id3 = $(this).attr('name');
            $('div#editFileInfo.'+id3).hide();
            $('div#curFileInfo.'+id3).toggle();       
        });
        // NEWS EDIT
        $(function(){
                $(".editNewsBtn").click(function(){
                    var id = $(this).attr('name');
                    $('div#novinkaContent.'+id).hide();
    		            $(this).hide();
                    $('div#novinkaEdit.'+id).fadeToggle();
                });
            });
            
        $(function() {
            $(".exitEditNews").click(function(){
                var id = $(this).attr('name');
                $('div#novinkaContent.'+id).fadeToggle();
    	        $('button.editNewsBtn[name='+id+']').fadeToggle();
                $('div#novinkaEdit.'+id).hide();
            });
        });  
        // COMMENT EDIT
        $(function(){
                $(".editCommentBtn").click(function(){
                    var id = $(this).attr('name');
                    $('div#commentContent.'+id).hide();
    		        $(this).hide();
                    $('div#commentEdit.'+id).fadeToggle();
                });
            });
            
        $(function() {
            $(".exitEditComment").click(function(){
                var id = $(this).attr('name');
                $('div#commentContent.'+id).fadeToggle();
    	        $('button.editCommentBtn[name='+id+']').fadeToggle();
                $('div#commentEdit.'+id).hide();
            });
        }); 
        
        // NEWS COMMENTS  
        $(function(){
                $(".commentsBtn").click(function(){
                    var id = $(this).attr('name');
                    $('div#novinkaComments.'+id).toggle();
                });
        });
    }           // end of MAIN SEARCH 
    
    // variables for search
    var userId = $("input#userId").val();
    var chNotes = $("input#chNotes").val();
    var chNews = $("input#chNews").val();
    var chFiles = $("input#chFiles").val();
    var chForum = $("input#chForum").val();
    var article;
    
    //search handler on click #searchBtn
    $("#searchBtn").on('click', function () {
        var pathname = $(location).attr('href') + '&q='+$('.searchBox').val();
        window.location.href = pathname;
    }); //event handler
    
    if (typeof $_GET['q'] !== 'undefined') {
        if($("#chNotes").is(':checked')) chNotes = true;
        else chNotes = false;
        if ($("#chNews").is(':checked')) chNews = true;
        else chNews = false;
        if($("#chFiles").is(':checked')) chFiles = true;
        else chFiles = false;
        if($("#chForum").is(':checked')) chForum = true;
        else chForum = false;
        article = true;
        var category = $("#selectCategories").val();
        $.ajax({
            type: "GET",
            url: "search.php",
            data: 'groupId=' + $_GET['id'] + '&userId='+ userId +'&s='+ $_GET['q']+'&article='+ article +'&show=' + $_GET['show'] + '&chNotes='+chNotes+'&chNews='+chNews+'&chFiles='+chFiles+ '&chForum='+chForum +'&category='+category,
            success: function(msg){
                search(msg, '#results');
            }
        }); // Ajax Call
    }
    
    // search handler on keyup .searchBox
    $('.searchBox').on('keyup', function(){
        if($("#chNotes").is(':checked')) chNotes = true;
        else chNotes = false;
        if ($("#chNews").is(':checked')) chNews = true;
        else chNews = false;
        if($("#chFiles").is(':checked')) chFiles = true;
        else chFiles = false;
        if($("#chForum").is(':checked')) chForum = true;
        else chForum = false;
        article = true;
        var category = $("#selectCategories").val();
        $.ajax({
            type: "GET",
            url: "search.php",
            data: 'groupId=' + $_GET['id'] + '&userId='+ userId +'&s='+ $('.searchBox').val()+'&article='+ article +'&show=' + $_GET['show'] + '&chNotes='+chNotes+'&chNews='+chNews+'&chFiles='+chFiles+ '&chForum='+chForum +'&category='+category,
            success: function(msg){
                search(msg, '#results');
            }
        }); // Ajax Call
    }); //event handler
    
    
    // search handler on change propreties for search
    $('.searchProperties').on('change', function(){
            if($("#chNotes").is(':checked')) chNotes = true;
            else chNotes = false;
            if ($("#chNews").is(':checked')) chNews = true;
            else chNews = false;
            if($("#chFiles").is(':checked')) chFiles = true;
            else chFiles = false;
            if($("#chForum").is(':checked')) chForum = true;
            else chForum = false;
            article = true;
            $.ajax({
                type: "GET",
                url: "search.php",
                data: 'groupId=' + $_GET['id'] + '&userId='+ userId +'&s='+ $('.searchBox').val() +'&article='+ article + '&show=' + $_GET['show'] + '&chNotes='+chNotes+'&chNews='+chNews+'&chFiles='+chFiles+ '&chForum='+chForum,
                success: function(msg){
                    search(msg, '#results');
                }
        }); // Ajax Call
    }); //event handler
    
    // search handler on change radioBox Folders/Articles
    $('#articleChanger').on('change', function(){
            article = true;
            $.ajax({                                    
                type: "GET",
                url: "search.php",
                data: 'groupId=' + $_GET['id'] + '&userId='+ userId +'&s='+ $('.searchBox').val() +'&article='+ article + '&show=' + $_GET['show'] + '&chNotes='+chNotes+'&chNews='+chNews+'&chFiles='+chFiles+ '&chForum='+chForum,
                success: function(msg){
                    search(msg, '#results');
                    
                }
        }); // Ajax Call
    }); //event handler
    
    // search handler on change radioBox Folders/Articles
    $('#folderChanger').on('change', function(){
            article = true;
            $.ajax({                                    
                type: "GET",
                url: "search.php",
                data: 'groupId=' + $_GET['id'] + '&userId='+ userId +'&s='+ $('.searchBox').val() +'&article='+ article + '&show=' + $_GET['show'] + '&chNotes='+chNotes+'&chNews='+chNews+'&chFiles='+chFiles+ '&chForum='+chForum,
                success: function(msg){
                    search(msg, '#results');
                }
        }); // Ajax Call
    }); //event handler

    // search handler on change Category
    $('.selectCategories').on('click', function(){
        article = true;
        var category = $(this).text(); 
        $.ajax({
            type: "GET",
            url: "search.php",
            data: 'groupId=' + $_GET['id'] + '&userId='+ userId +'&s='+ $('.searchBox').val() +'&article='+ article +'&show=' + $_GET['show'] + '&chNotes='+chNotes+'&chNews='+chNews+'&chFiles='+chFiles+ '&chForum='+chForum + '&category='+ category,
            success: function(msg){
                search(msg, '#results');
            }
        }); // Ajax Call
    }); //event handler
    
    // search handler on change Category
    $('#selectCategories').on('change', function(){
        article = true;
        var category = $("#selectCategories").val();
        $.ajax({
            type: "GET",
            url: "search.php",
            data: 'groupId=' + $_GET['id'] + '&userId='+ userId +'&s='+ $('.searchBox').val() +'&article='+ article +'&show=' + $_GET['show'] + '&chNotes='+chNotes+'&chNews='+chNews+'&chFiles='+chFiles+ '&chForum='+chForum + '&category='+ category,
            success: function(msg){
                search(msg, '#results');
            }
        }); // Ajax Call
    }); //event handler


    
    // search handler on keyup .mainSearchBox
    $('.mainSearchBox').on('keyup', function(){
        if ($('.mainSearchBox').val() == '') {
            $("#mainResults").hide();
        } else {
            $.ajax({
                type: "GET",
                url: "search.php",
                data: 'groupId=all&userId='+ userId +'&s='+ $('.mainSearchBox').val(),
                success: function(msg){
                    search(msg, '#mainResults');
                    $('div#mainResults').show();
                }
            }); // Ajax Call
        }
    }); //event handler
    
    // refreshovanie novinek v skupine
    if ($_GET['page'] == "group") {
        $('#actionsContent').load("loadActions.php?groupId="+ $_GET['id'], function() {
            window.setInterval("loadData()", 10000);
        });
    } 
    
  
    $(".regText").focusout(function() { 
        checkIsEmpty($(this).attr('id'));
    });
    $("#txtConfirmPassword").focusout(checkPasswordMatch);
    $(".regEmail").focusout(validateEmail);
    $("#txtNewPassword").focusout(checkPasswordLength);
    $("#txtNewPassword").keyup(checkPasswordLength); 

    // funkcia vrati fakulty danej univerzity do selectu
    $("#universitySelect").on('change', function(){
        $.ajax({
            type: "GET",
            url: "getFaculty.php",
            data: 'universityName=' + $('#universitySelect').val(),
            success: function(msg){
                $('#faculty').html(msg);
            }
        }); // Ajax Call        
    });
        
   
    //resizeToFit();     - problem na mozille
});

function checkIsEmpty(id) {
    var input = $("#"+id).val();
    if (input == '') {
        $("#"+id).addClass('err-input');
        $("#"+id).removeClass('ok-input');
        return false;
    } else {
        $("#"+id).addClass('ok-input');
        $("#"+id).removeClass('err-input');
        return true;
    }
}


FB.getLoginStatus(function(response) {
    if (response.status === 'connected') {
    // the user is logged in and has authenticated your
    // app, and response.authResponse supplies
    // the user's ID, a valid access token, a signed
    // request, and the time the access token 
    // and signed request each expire
    var uid = response.authResponse.userID;
    var accessToken = response.authResponse.accessToken;
    } else if (response.status === 'not_authorized') {
    // the user is logged in to Facebook, 
    // but has not authenticated your app
    } else {
    // the user isn't logged in to Facebook.
    }
 })


// funkcia na zaistovanie refreshu noviniek
function loadData(){
    $('#actionsContent').load("loadActions.php?groupId="+ $_GET['id']);
} 

// funkcia na checkovanie dlzky hesla
function checkPasswordLength() {
    var pwd = $("#txtNewPassword").val();
    var n = pwd.length;
    if (n < 6) {
        $("#checkPasswordLength").fadeIn();
        $("#txtNewPassword").removeClass('ok-input');
        $("#txtNewPassword").addClass('err-input');
        return false;
    }
    else {
        $("#checkPasswordLength").fadeOut(); 
        $("#txtNewPassword").addClass('ok-input');
        $("#txtNewPassword").removeClass('err-input');
        return true;
    }
}

// funkcia na checkovanie zhody hesiel pri zmene hesla
function checkPasswordMatch() {
    var password = $("#txtNewPassword").val();
    var confirmPassword = $("#txtConfirmPassword").val();

    if (confirmPassword == ""){
        $("#txtConfirmPassword").removeClass('ok-input');
        $("#txtConfirmPassword").addClass('err-input');
        return false;
    }
    else if (password != confirmPassword) {
        $("#txtConfirmPassword").removeClass('ok-input');
        $("#txtConfirmPassword").addClass('err-input');
        return false;
    } else {
        if(password == confirmPassword && confirmPassword.length >= 6){
            $("#txtConfirmPassword").addClass('ok-input');
            $("#txtConfirmPassword").removeClass('err-input');
            return true;
        } else {
            $("#txtConfirmPassword").removeClass('ok-input');
            $("#txtConfirmPassword").addClass('err-input');
            return false;
        }
    }
} 

// funkcia na checkovanie emailov ci su v spravnom tvare
function validateEmail() {
    var email = $(".regEmail").val(); 
    var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    if (email == ""){
        $(".checkEmail").html("");
    }
    else if (email.match(re)) {
        $("#checkEmail").addClass('ok-input');
        $("#checkEmail").removeClass('err-input');
    }
    else {
        $("#checkEmail").addClass('err-input');
        $("#checkEmail").removeClass('ok-input');
    }
    return re.test(email);
} 


// mainresults zmizne po kliknuti inde
$(function() {
    $(document).on('click', function(e) {
        if (e.target.id == 'mainResults') {
        } else {
            $('#mainResults').hide();
        }

    })
});


// zmena skupiny na hornom mainbare
$(function(){
      // bind change event to select
      $('#changeGroup').bind('change', function () {
          var url = $(this).val(); // get selected value
          if (url) { // require a URL
              window.location = url; // redirect
          }
          return false;
      });
});

  // using AJAX
function showFiles(str, groupId, userId) {
    if (str=="") {
        document.getElementById("results").innerHTML="";
        return;
    }
    if (window.XMLHttpRequest) {
        // code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp=new XMLHttpRequest();
    } else { // code for IE6, IE5
        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange=function() {
        if (xmlhttp.readyState==4 && xmlhttp.status==200) {
            document.getElementById("results").innerHTML=xmlhttp.responseText;
            $('div#fileInfo').hide();
            $('div#editFileInfo').hide();
            // File Info
            $(".showFileInfo").click(function(){
                var id = $(this).attr('name');
                $('div#fileInfo.'+id).toggle();
            });
            
            // FILE INFO EDIT
            $(".editFileInfoBtn").click(function(){
                var id2 = $(this).attr('name');
                $('div#curFileInfo.'+id2).hide();
                $('div#editFileInfo.'+id2).toggle();
            }); 
        
            $(".exitEditFileInfoBtn").click(function(){
                var id3 = $(this).attr('name');
                $('div#editFileInfo.'+id3).hide();
                $('div#curFileInfo.'+id3).toggle();       
            });  
        }
    }
    xmlhttp.open("GET","showFiles.php?groupId="+groupId+"&userId="+userId+"&q="+str,true);
    xmlhttp.send();    
}



function resizeToFit(){
    var fontsize = $('div#name h1').css('font-size');
    $('div#name h1').css('fontSize', parseFloat(fontsize) - 1);

    if($('div#name h1').height() >= $('div#name').height()){
        resizeToFit();
    }
}


function getSelectionText() {
    var text = "";
    if (window.getSelection) {
        text = window.getSelection().toString();
    } else if (document.selection && document.selection.type != "Control") {
        text = document.selection.createRange().text;
    }
    return text;
}

function addRef() {
    var input = document.createElement("input");
    var br = document.createElement('br');
    input.type = "text";
    input.setAttribute("placeholder", "referencie");
    input.setAttribute("name", "references[]");
    document.getElementById("referencesAdd").appendChild(input);
    document.getElementById("referencesAdd").appendChild(br);
}

function addKW() {
    var input = document.createElement("input");
    var br = document.createElement('br');
    input.type = "text";
    input.setAttribute("placeholder", "kľúčové slovo");
    input.setAttribute("name", "keywords[]");
    document.getElementById("keywords").appendChild(input);
    document.getElementById("keywords").appendChild(br);
}
    
function addPic() {
    var input = document.createElement("input");
    input.type = "file";
    input.setAttribute("name", "images[]");
    input.setAttribute("onchange", "addPic()");
    var li = document.createElement("li");
    document.getElementById("images").appendChild(li).appendChild(input);
}


function conf(delUrl) {
  if (confirm("Vykonať túto akciu?")) {
   document.location = delUrl;
  }
}

function goBack() {
    window.history.go(-1)
}

function checkLogin() {
    var emailOk = false;
    var passwordMatch = false;
    var passwordLength = false;
    var email = $(".regEmail").val(); 
    var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    if (email == "")
        $("#checkEmail").addClass('err-input');
    else if (email.match(re)) 
        emailOk = true;
    else {
        $("#checkEmail").addClass('err-input');
    }
    
    name = checkIsEmpty('reg-name');
    surname = checkIsEmpty('reg-surname');
    passwordMatch = checkPasswordMatch();
    passwordLength = checkPasswordLength();
    if (passwordMatch && emailOk && passwordLength && name && surname) {
        return true;
    } else {
        return false;  
    }

}

