<form action="/search" method="GET">
  <div class="form-group">
    <input name="search" type="search" class="form-control" id="InputSearch" aria-describedby="emailHelp" placeholder="Search For A video">

  </div>
  <button type="submit" class="btn btn-primary">Search</button>
</form>
<?
global $wpdb;
$videos_table=$wpdb->prefix . "yvf";
$category_table=$wpdb->prefix . "ctb";
$home_type=get_option('yvf_curr_setting');
if($home_type==0){
    $blocks=get_option('yvf_grid_blocks');
$results=$wpdb->get_results("SELECT * FROM {$videos_table} WHERE tolive<=current_timestamp ORDER BY pinned,tolive LIMIT {$blocks}  ");
}
else{
    $blocks=get_option('yvf_pinned_blocks');
$results=$wpdb->get_results("SELECT * FROM {$videos_table} WHERE tolive<=current_timestamp ORDER BY pinned,tolive LIMIT {$blocks}  ");
}
//print_r($results);
//exit();
?>
<div class="container" id="home">
<div class="row">
<h1 class="heading"><strong>Top Rated Videos</strong></h1>
</div>
<div class="row">
<?php foreach($results as $result){?>
<div class="col-lg-3 col-md-6 col-sm-12">
<div class="blocks"><a href="/watch?v=<?php echo $result->url;?>"><img src="<?php echo $result->Thumbnail;?>" 
             class="img-responsive" alt="Responsive image" 
             width="200" height="200" /></a>
              <h2><?php echo $result->title;?></h2>
             </div>
             </div>
<?php }?>
</div>
<?if($home_type==0){?>
<div class="row">
<div class="d-flex justify-content-center">
<button class="btn btn-primary" id="loadold" data-id="20000">Load New</button>
</div>
</div>
<?}?>
</div>
<?php
if($home_type==1){
    $blocks=get_option('yvf_category_blocks');
    $cats=unserialize(get_option('yvf_home_categories'));}
//$cats=$wpdb->get_results("SELECT * FROM {$category_table} ");}
?>
<?foreach($cats as $key=>$cat){
    $category=$wpdb->get_results("SELECT * FROM {$category_table} WHERE id={$cat}");
    ?>
<div class="container">
<div class="row">
<h1 class="heading"><strong><a style="font-size:22px;margin:10px;" href="/category?c=<? echo $cat;?>" class="btn btn-danger"><?echo $category[0]->title;?></strong></a></h1>
</div>
<div class="row">
<?php 
$cat_results=$wpdb->get_results("SELECT * FROM {$videos_table} WHERE tolive<current_timestamp AND category={$cat} ORDER BY tolive DESC LIMIT {$blocks}  ");
foreach($cat_results as $cat_result){?>
<div class="col-lg-3 col-md-6 col-sm-12">
<div class="blocks"><a href="/watch?v=<?php echo $cat_result->url;?>"><img src="<?php echo $cat_result->Thumbnail;?>" 
             class="img-responsive" alt="Responsive image" 
             width="200" height="200" /></a>
              <h2><?php echo $cat_result->title;?></h2>
             </div>
             </div>
<?php }?>

</div>

</div>
<?}?>
<div class="container">
<div class="row">
<div class="d-flex justify-content-end">
<a href="/category" class="btn btn-primary">Visit All Category</a>
</div>
</div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script>
$(document).ready(function(){
    $("#loadold").click(function(){
        loadmore($(this),$(this).attr("data-id"));
    
    });
    function loadmore(elem,dataid){

          $.ajax({
    type: 'POST',
    url:  "/wp-content/plugins/Youtube-Plugin-Fetcher/yvf-ajax.php",
    data: { id :dataid,home:1  },
    beforeSend: function() {
        // setting a timeout
        var spin='<div id="spin" class="text-center"><div class="spinner-border" role="status"><span class="sr-only">Loading...</span></div></div>';
        elem.append(spin);
    },
    success: function(data) {
        $("#spin").remove();
        if(data==""){

            $("#home").append("No More Video Found");
           // $(this).attr("data-id",-1);
            return;
        }
       // $("#data").remove();
      // alert(data);
            $("#home").html(data);
            //var newid=$("#data").val();
           // alert(newid);
           // $("#loadmore").attr("data-id",newid);
          $("#loadold").click(function(){
        loadmore($(this),$(this).attr("data-id"));
    
    });
            
            
        
    },
    error: function(xhr) { // if error occured
        alert(xhr.statusText + xhr.responseText);
    },
    dataType: 'html'
});
    }
})
    </script>