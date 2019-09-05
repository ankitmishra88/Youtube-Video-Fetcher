<?php
$choice=0;
global $wpdb;
$videos_table=$wpdb->prefix . "yvf";
$cat_table=$wpdb->prefix . "ctb";
$categories=$wpdb->get_results("SELECT * FROM {$cat_table}");
if(!empty($categories))
$cat_id=$categories[0]->id;
$title=$categories[0]->title;
if(isset($_GET['c'])){
    $cat_id=$_GET['c'];
}
$query="SELECT * FROM {$videos_table} where category={$cat_id} AND tolive<current_timestamp ORDER BY tolive,id DESC LIMIT 16  ";
if(isset($_GET['choice'])){
    $choice=$_GET['choice'];
    if($_GET['choice']==0)
    $query="SELECT * FROM {$videos_table} where category={$cat_id} AND tolive<current_timestamp ORDER BY tolive,id DESC LIMIT 16  ";
    if($_GET['choice']==1)
    $query="SELECT * FROM {$videos_table} where category={$cat_id} AND tolive<current_timestamp AND pinned=1 ORDER BY tolive,id DESC LIMIT 16  ";
    if($_GET['choice']==2)
    $query="SELECT * FROM {$videos_table} where category={$cat_id} AND tolive<current_timestamp ORDER BY id  LIMIT 16  ";
    //echo $query;
}

//print_r($categories);
?>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <a class="navbar-brand" href="#">View By Category</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
    <?foreach($categories as $cat){?>
      <li class="nav-item <?php if($cat_id==$cat->id) {$title=$cat->title; echo 'active';} ?>">
        <a class="nav-link btn btn-primary" style="font-size:20px;margin:10px;" href="/category?c=<?php echo $cat->id?>"><? echo $cat->title?> <span class="sr-only">(current)</span></a>
      </li>
      <?}?>
    </ul>
     <form class="form-inline my-2 my-lg-0">
    <select id="choice" class=" btn btn-primary mr-sm-2">
      <option value='0' <? if($choice==0) echo 'selected';?>>Latest</option>
      <option value='1' <? if($choice==1) echo 'selected';?>>Handpicked</option>
       <option value='2' <? if($choice==2) echo 'selected';?>>Random</option>
    </select>
    </form>
  </div>
</nav>
<?

$results=$wpdb->get_results($query);
//print_($results);
?>
<div class="container">
<div class="row">
<h1 class="heading"><strong>Latest Videos From <?php echo $title;?></strong></h1>
</div>
<div class="row" id="latest">
<?php $count=0; foreach($results as $result){$count++;?>
<div class="col-lg-3 col-md-6 col-sm-12">
<div class="blocks"><a href="/watch?v=<?php echo $result->url;?>" video_id="<?php echo $result->url;?>"><img src="<?php echo $result->Thumbnail;?>" 
             class="img-responsive" alt="Responsive image" 
             width="200" height="200" /></a>
              <h2><?php echo $result->title;echo $result->id; ?></h2>
             </div>
             </div>
          
<?php }?>


</div>
<div id="load" class="row">
<div class="col-12 d-flex justify-content-center">
<?
$c=$cat_id;
    if(isset($_GET['c']))
    $c=$_GET['c'];
if(!isset($_GET['choice'])||$_GET['choice']==0){
    ?>
<button><a href="?c=<?echo $c?>&choice=2">View All</a></button>
<?}else{?>
<h1 > <button category="<? echo $cat_id;?>" choice="<?php echo $choice;?>" data-id="<?php echo ((int)$results[$count-1]->id)?>" action="latest" id="loadmore" class="btn btn-primary">Load more </button></h1><?}?>
</div>
</div>

</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script>
$(document).ready(function(){
    $("#loadmore").click(function(){
        loadmore($(this).attr("category"),$(this).attr("data-id"),$(this).attr("choice"));
    
    });
    $("#choice").change(function(){
        var cat=<?echo $c;?>;
        var choice=$(this).val();
        var url="/category?c="+cat+"&choice="+choice;
        window.location.href=url;
    });
});
function loadmore(cat,dataid,ch){
    //alert("Hi");
  data_id=parseInt(dataid);

  if(data_id<0){
      alert("No More Video Found");
     return false;
  }
  

  $.ajax({
    type: 'POST',
    url:  "/wp-content/plugins/Youtube-Plugin-Fetcher/yvf-ajax.php",
    data: { id :dataid,category:cat,choice:ch,ajax:1  },
    beforeSend: function() {
        // setting a timeout
        var spin='<div id="spin" class="text-center"><div class="spinner-border" role="status"><span class="sr-only">Loading...</span></div></div>';
        $("#load").append(spin);
    },
    success: function(data) {
        $("#spin").remove();
        if(data==""){

            $("#latest").append("No More Video Found");
            $(this).attr("data-id",-1);
            return;
        }
        $("#data").remove();
            $("#latest").append(data);
            var newid=$("#data").val();
           // alert(newid);
            $("#loadmore").attr("data-id",newid);

            
            
        
    },
    error: function(xhr) { // if error occured
        alert(xhr.statusText + xhr.responseText);
    },
    dataType: 'html'
});

    }
</script>
