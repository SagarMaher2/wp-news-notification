//fcm send message form valication
jQuery('#submit').live('click', function( event ){

var title = document.getElementById('title').value;
var description = document.getElementById('msg').value;

if(title==''){
document.getElementById("title_error").innerHTML ='<br /><br />Required field';
return false;	
}else if(title!=''){
document.getElementById("title_error").innerHTML ='';	
}

if(description==''){
document.getElementById('description_error').innerHTML='<br /><br /><br /><br /><br /><br /><br /><br /><br /><br />Required field'
return false;	
}else if(description!=''){
document.getElementById('description_error').innerHTML='';	
}
 
});

//fcm settings form validation
jQuery('#save-settings').live('click', function( event ){

var access_key = document.getElementById('access_key').value;

if(access_key==''){
document.getElementById("access_key_error").innerHTML ='<br /><br />Required field';
return false;	
}else if(access_key!=''){
document.getElementById("access_key_error").innerHTML ='';	
}
 
});