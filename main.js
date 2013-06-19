function echeck(str) {

        var at="@"
        var dot="."
        var lat=str.indexOf(at)
        var lstr=str.length
        var ldot=str.indexOf(dot)
        if (str.indexOf(at)==-1){
           return false
        }

        if (str.indexOf(at)==-1 || str.indexOf(at)==0 || str.indexOf(at)==lstr){
           return false
        }

        if (str.indexOf(dot)==-1 || str.indexOf(dot)==0 || str.indexOf(dot)==lstr){
            return false
        }

         if (str.indexOf(at,(lat+1))!=-1){
            return false
         }

         if (str.substring(lat-1,lat)==dot || str.substring(lat+1,lat+2)==dot){
            return false
         }

         if (str.indexOf(dot,(lat+2))==-1){
            return false
         }
        
         if (str.indexOf(" ")!=-1){
            return false
         }

         return true                    
    }

// Show person's info when name is clicked
$(document).on('click','a.sub_name,a.regular_host',function(){
    if($(this).siblings('.personinfo').css("display") == "none"){
        $(this).siblings('.personinfo').css("display","inline-block");
        $(this).siblings('.comment').css("color","rgb(35,44,48)");
        $(this).siblings('.comment').css("position","absolute");
    }
    else{
        $(this).siblings('.personinfo').css("display","none");
        $(this).siblings('.comment').css("color","rgb(147,146,143)");
        $(this).siblings('.comment').css("position","inherit");
    }
});


//////////// index.php

// Take the slot!
$(document).on('click','.take',function(){
    clickedID = $(this).attr("id");
    $('#'+clickedID).hide();
    $('#'+clickedID+'li>.comment').replaceWith('<form class="takeform" onsubmit="return false" id="'+clickedID+'form"><input type="text" id="sub_name" value="name" onclick="if(this.value==\'name\'){this.value=\'\';}" onblur="if(this.value==\'\'){this.value=\'name\';}" /> <input type="tel" id="sub_phone" value="phone" onclick="if(this.value==\'phone\'){this.value=\'\';}" onblur="if(this.value==\'\'){this.value=\'phone\';}" /> <input type="email" id="sub_email" value="email" onclick="if(this.value==\'email\'){this.value=\'\';}" onblur="if(this.value==\'\'){this.value=\'email\';}" /> <input type="submit" class="takeconfirm" slotid="'+clickedID+'" id="'+clickedID+'confirm" value="take it!" /> <input type="button" class="takedecline" value="cancel" /></form>');
    $('#sub_name').select();
    $("#sub_phone").mask("(999) 999-9999");
    $("#sub_phone").val("phone");
});
$(document).on('click','.takeconfirm',function(){
    clickedID = $(this).attr("slotid");
    $(this).attr("disabled","yes");
    errorstring = '';
    noAJAX = false;
    try{ if($('#sub_name').val()=='' || $('#sub_name').val() == 'name') throw "you must enter your name"; }
        catch(err){ errorstring += err + '\n'; noAJAX = true; }
    try{ if($('#sub_phone').val()=='' || $('#sub_phone').val() == 'phone') throw "you must enter your phone number"; }
        catch(err){ errorstring += err + '\n'; noAJAX = true; }
    try{ if($('#sub_email').val()=='' || $('#sub_email').val() == 'email' || !echeck($('#sub_email').val())) throw "you must enter a valid email"; }
        catch(err){ errorstring += err + '\n'; noAJAX = true; }

    if(!errorstring == ''){alert(errorstring);}

    if(!noAJAX){
        $.ajax({url: 'query.php',
                data: {action: 'takesubmit',
                       id: clickedID,
                       sub_name: $("#sub_name").val(),
                       sub_phone: $("#sub_phone").val(),
                       sub_email: $("#sub_email").val()},
                type: 'post',
                success: function(output){
                    if(output == ''){
                        $('#'+clickedID+'li').addClass('strikethrough');
                        $('#'+clickedID+'li>.takeform').replaceWith('<span class="takeconfirmed">you got it! now put it in your datebook</span>');
                    }
                    else{
                        $('#'+clickedID+'li>.takeform').after('<p class="show_name">Email cbothner@umich.edu this error message!</p>'+output);
                    }
                }
        });
    } else {
        $(this).removeAttr("disabled");
    }
});
$(document).on('click','.takedecline,.deletedecline',function(){
    location.reload();
});

// Delete the slot
$(document).on('click','.delete',function(){
    clickedID = $(this).attr("id");
    $('#'+clickedID).hide();
    $('#'+clickedID+'li>.comment').replaceWith('<form class="deleteform" onsubmit="return false" id="'+clickedID+'form"><input type="text" id="removal_password" value="removal password?" onclick="if(this.value==\'removal password?\'){this.value=\'\';}" onblur="if(this.value==\'\'){this.value=\'removal password?\';}" /> <input type="submit" class="deleteconfirm" slotid="'+clickedID+'" id="'+clickedID+'delete" value="delete it!" /> <input type="button" class="deletedecline" value="cancel"/></form>');
    $('#removal_password').select();
});

$(document).on('click','.deleteconfirm',function(){
    slotID = $(this).attr("slotid");
    $.ajax({url: 'query.php',
            data: {action: 'requestdelete',
                   id: slotID,
                   removal_password: $('#removal_password').val()},
            type: 'post',
            success: function(output){
                if(output == ''){
                    $('#'+slotID+'li').addClass('strikethrough');
                    $('#'+slotID+'li>.deleteform').replaceWith('<span class="takeconfirmed">successfully deleted. you\'re responsible for your show again.</span>');
                }
                else if(output == 'passwordfail'){
                    alert("removal password incorrect!");
                }
                else{
                    $('#'+slotID+'li>.deleteform').after('<p class="show_name">Email cbothner@umich.edu this error message!</p>'+output);
                }
            }
    });
});


//////////// request.php

// Mask the form
$(document).ready(function(){
    $('#phone').mask("(999) 999-9999");
    var i = document.createElement("input");
    i.setAttribute("type", "time");
    if(i.type == "text"){
        $.mask.definitions['@']='[01]';
        $.mask.definitions['~']='[APap]';
        $.mask.definitions['%']='[012345]';
        $('#show_start,#show_end').mask("@9:%9 ~m");
        $('#show_start,#show_end').after(' (hh:mm am/pm)');
    }
});

// Make the popup calendar
$(document).ready(function(){
    var i = document.createElement("input");
    i.setAttribute("type", "date");
    if(i.type == "text"){
        $("#show_date").datepicker();
        $( "#show_date" ).datepicker( "option", "dateFormat",'yy-mm-dd');
    }
});

// Submit the sub request
$(document).on('click','#requestsubmit',function(){
    errorstring = '';
    noAJAX = false;
    try{ if($('#regular_host').val()=='') throw "you must enter your name"; }
        catch(err){ errorstring += err + '\n'; noAJAX = true; }
    try{ if($('#removal_password').val()=='') throw "you must set a removal password"; }
        catch(err){ errorstring += err + '\n'; noAJAX = true; }
    try{ if($('#email').val()=='') throw "you must enter your email"; }
        catch(err){ errorstring += err + '\n'; noAJAX = true; }
    try{ if($('#phone').val()=='') throw "you must enter your phone number"; }
        catch(err){ errorstring += err + '\n'; noAJAX = true; }
    try{ if($('#show_name').val()=='') throw "you must enter the show's name"; }
        catch(err){ errorstring += err + '\n'; noAJAX = true; }
    try{ if($('#show_date').val()=='') throw "you must enter the date of the show"; }
        catch(err){ errorstring += err + '\n'; noAJAX = true; }
    try{ if($('#show_start').val()=='') throw "you must enter the show's start time"; }
        catch(err){ errorstring += err + '\n'; noAJAX = true; }
    try{ if($('#show_end').val()=='') throw "you must enter the show's end time"; }
        catch(err){ errorstring += err + '\n'; noAJAX = true; }

    if(!errorstring == ''){alert(errorstring);}

    if(!noAJAX){
        $(this).replaceWith('<div id="loading"> <img src="ajax-loader.gif" alt="loading..." /></div>');
        $.ajax({url: 'query.php',
                data: {action: 'requestsubmit',
                       regular_host: $('#regular_host').val(),
                       removal_password: $('#removal_password').val(),
                       email: $('#email').val(),
                       phone: $('#phone').val(),
                       show_name: $('#show_name').val(),
                       show_date: $('#show_date').val(),
                       show_start: $('#show_start').val(),
                       comment: $('#comment').val(),
                       show_end: $('#show_end').val()},
                type: 'post',
                success: function(output){
                    if(output == ''){
                        $('#loading').replaceWith('<p class="requestsuccess">successfully submitted sub request</p>');
                    } else {
                        $('#loading').replaceWith('<input type="button" value="submit" id="requestsubmit" />');
                        $('#loading').after('<div style="margin-left:10.6em"><p class="show_name">Email cbothner@umich.edu this error message!</p>'+output+"</div>");
                    }
                }
        });
    }
});
