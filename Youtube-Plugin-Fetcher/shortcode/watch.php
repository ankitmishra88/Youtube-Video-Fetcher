<?php

global $wpdb;
$videos_table=$wpdb->prefix . "yvf";
$chk=1;
if(!(isset($_REQUEST['v']))){
$url='https://www.youtube.com/embed/zpOULjyy-n8?rel=0';
$id=get_option('watch_video');
$result=$wpdb->get_results("SELECT * FROM {$videos_table} WHERE id='{$id}'  ");
$url='https://www.youtube.com/embed/'.$result[0]->url.'?rel=0';
}
else{
$url='https://www.youtube.com/embed/'.$_REQUEST['v'].'?rel=0';
$chk=0;
$result=$wpdb->get_results("SELECT * FROM {$videos_table} WHERE url='{$_REQUEST['v']}' AND tolive<=current_timestamp ");
//echo "SELECT * FROM {$videos_table} WHERE url='{$_REQUEST['v']}'";
//print_r($result);
$sz=count($result);
if($sz)
$chk=1;
if(!$chk)
$url='https://www.youtube.com/embed/0?rel=0';}
?>
<div id="embed_div" class="embed-responsive embed-responsive-<? echo get_option('watch_size');?>">
  <iframe class="embed-responsive-item" id="embed_video" src="<?php echo $url;?>" allowfullscreen></iframe>
</div>
<div id="vid_details" class="container">
<div class="row">
<div class="col-12">
<h1 id="title" style="text-align:center;"><?php if($chk) echo $result[0]->title; else echo "No Such video Found" ?></h1>
</div>
</div>
<div class="row" style="font-size:1.2em;">
<div class="col-6 d-flex justify-content-start">
<p>Date Published: <span><? if($chk) echo date('d-M-Y',$result[0]->tolive)?></span></p>
</div>
<div class="col-6 d-flex justify-content-end">
<p>Views: <span><? if($chk) echo $result[0]->views;?></span></p>
</div>
</div>
<div class="row">
<div class="col-12">
<h1 style="text-align:center;">Description</h1>
</div>
</div>
<div class="row">
<div class="col-12">
<h3 id="description" style="text-align:center;"><?php if($chk) echo $result[0]->description; else echo "No Description" ?></h3>
</div>
</div>
</div>
<?php
$videos_table=$wpdb->prefix . "yvf";
$category_table=$wpdb->prefix . "ctb";
if($chk)
$r_query="SELECT * FROM {$videos_table} WHERE id<{$result[0]->id} AND tolive<=current_timestamp ORDER BY id DESC LIMIT 8  ";
$results=$wpdb->get_results($r_query);
if(count($results)<8){
    $r_query="SELECT * FROM {$videos_table} WHERE id>{$result[0]->id} AND tolive<=current_timestamp ORDER BY id DESC LIMIT 8  ";
$results=$wpdb->get_results($r_query);
}
if(count($results)<8){
    $r_query="SELECT * FROM {$videos_table} WHERE ORDER BY id DESC LIMIT 8  ";
$results=$wpdb->get_results($r_query);
}
?>
<div class="container" id="latest">
<div class="row">
<h1 class="heading"><strong>Recommended</strong></h1>
</div>
<div class="row">
<?php $count=0; foreach($results as $result){$count++;?>
<div class="col-lg-3 col-md-6 col-sm-12">
<div class="blocks"><a href="/watch?v=<?php echo $result->url;?>" video_id="<?php echo $result->url;?>"><img src="<?php echo $result->Thumbnail;?>" 
             class="img-responsive" alt="Responsive image" 
             width="200" height="200" /></a>
              <h2><?php echo $result->title;?></h2>
             </div>
             </div>
             <?if($count==4){?>
             </div>
             <div class="row">
             <div class="col-6 d-lg-flex flex-row bd-highlight d-md-none d-sm-none">
            <h1 style="margin-left:-50px;"> <button data-id="<?php echo $result->id;?>" id="back" class="btn btn-primary">&lt;&lt;</button></h1>
             </div>
             <div class="col-6 d-lg-flex flex-row-reverse bd-highlight d-md-none d-sm-none">
            <h1 style="right:0;"> <button data-id="<?php echo $result->id;?>" id="next" class="btn btn-primary disable">&gt;&gt;</button></h1>
             </div>
             </div>
             <div class="row">
              <?}?>
<?php }?>


</div>
<div class="row">
<div class="col-12 d-md-flex d-lg-none d-sm-flex justify-content-center">
<h1 > <button data-id="8" id="load" class="btn btn-primary">Load more</button></h1>
</div>
</div>

</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script>
$(document).ready(function(){
    $("#watch_size").change(function(){
    cls="embed-responsive embed-responsive-"+$(this).val();
    $("#embed_div").attr("class",cls);
        $("#embed_video").attr("class",cls);
    });
  $("button").click(function(event){
      //alert("HI");
      event.preventDefault();
  load($(this).attr("data-id"),$(this).attr("id"));
  });
  /*$("#latest a").click(function(event){
event.preventDefault();
load_vid($(this).attr("video_id"));
  });*/
});
function load_vid(dataid){
  var request = $.ajax({
  url: "/wp-content/plugins/Youtube-Plugin-Fetcher/yvf-ajax.php",
  method: "POST",
  data: { url :dataid,ajax:1  },
  dataType: "json",
   beforeSend : function() {
       //alert("Ready!");
   var load_data='<div class="d-flex justify-content-center"><div class="spinner-border" role="status"><span class="sr-only">Loading...</span></div></div>';
 $("#vid_details").html(load_data);
}
});

request.done(function( msg ) {
    var embed_url="https://www.youtube.com/embed/"+msg.url+"?rel=0";
    //alert(embed_url);
 $("#embed_video").attr("src",embed_url);
 //$("#title").text(msg.title);
 //alert(msg.title);
 //$("#description").text(msg.description);
 //alert(msg.description);

var load_data1='<div id="vid_details" class="container"><div class="row"><div class="col-2"></div><div class="col-8"><h1 id="title" style="text-align:center;">'
+msg.title+'</h1></div><div class="col-2"></div></div><div class="row"><div class="col-4"></div><div class="col-4"><h1 style="text-align:center;">Description</h1></div><div class="col-4"></div></div><div class="row"><div class="col-2"></div><div class="col-8"><h3 id="description" style="text-align:center;">'+msg.description+'</h3></div><div class="col-2"></div></div></div>';
$("#vid_details").html(load_data1);

 $("#latest a").click(function(event){
event.preventDefault();
load_vid($(this).attr("video_id"));
  });
});
 
request.fail(function( jqXHR, textStatus ) {
  alert( "Request failed: " + textStatus );
});

//$("#embed_video").attr("src",url);
}
function load(dataid,step){
    //alert(dataid);
    var request = $.ajax({
  url: "/wp-content/plugins/Youtube-Plugin-Fetcher/yvf-ajax.php",
  method: "POST",
  data: { id :dataid,act:step,ajax:1  },
  dataType: "html",
  beforeSend : function() {
   var load_data='<div class="spinner-grow text-primary" role="status"><span class="sr-only">Loading...</span></div><div class="spinner-grow text-secondary" role="status"><span class="sr-only">Loading...</span></div><div class="spinner-grow text-success" role="status"><span class="sr-only">Loading...</span</div><div class="spinner-grow text-danger" role="status"><span class="sr-only">Loading...</span></div><div class="spinner-grow text-warning" role="status"><span class="sr-only">Loading...</span></div><div class="spinner-grow text-info" role="status"> <span class="sr-only">Loading...</span></div><div class="spinner-grow text-light" role="status"><span class="sr-only">Loading...</span></div><div class="spinner-grow text-dark" role="status"> <span class="sr-only">Loading...</span></div>';
   var load_data1='<div class="d-flex justify-content-center"><div class="spinner-border" role="status"><span class="sr-only">Loading...</span></div></div>';
 $("#latest").html(load_data1);
}
});


request.done(function( msg ) {
   // alert(msg);
 $("#latest").html(msg);
  $("button").click(function(event){
      //alert("HI");
      event.preventDefault();
  load($(this).attr("data-id"),$(this).attr("id"));
  });
  /*$("#latest a").click(function(event){
event.preventDefault();
load_vid($(this).attr("video_id"));
  });*/
});
 
request.fail(function( jqXHR, textStatus ) {
  alert( "Request failed: " + textStatus );
});

}
</script>
<script>"use strict"; document.addEventListener('DOMContentLoaded', function(){if (window.hideYTActivated) return; let onYouTubeIframeAPIReadyCallbacks=[]; for (let playerWrap of document.querySelectorAll(".hytPlayerWrap")){let playerFrame=playerWrap.querySelector("iframe"); let tag=document.createElement('script'); tag.src="https://www.youtube.com/iframe_api"; let firstScriptTag=document.getElementsByTagName('script')[0]; firstScriptTag.parentNode.insertBefore(tag, firstScriptTag); let onPlayerStateChange=function(event){if (event.data==YT.PlayerState.ENDED){playerWrap.classList.add("ended");}else if (event.data==YT.PlayerState.PAUSED){playerWrap.classList.add("paused");}else if (event.data==YT.PlayerState.PLAYING){playerWrap.classList.remove("ended"); playerWrap.classList.remove("paused");}}; let player; onYouTubeIframeAPIReadyCallbacks.push(function(){player=new YT.Player(playerFrame,{events:{'onStateChange': onPlayerStateChange}});}); playerWrap.addEventListener("click", function(){let playerState=player.getPlayerState(); if (playerState==YT.PlayerState.ENDED){player.seekTo(0);}else if (playerState==YT.PlayerState.PAUSED){player.playVideo();}});}window.onYouTubeIframeAPIReady=function(){for (let callback of onYouTubeIframeAPIReadyCallbacks){callback();}}; window.hideYTActivated=true;});</script>