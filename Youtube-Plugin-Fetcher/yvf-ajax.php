<?php
define( 'BLOCK_LOAD', true );
require_once( $_SERVER['DOCUMENT_ROOT'] . '/wp-config.php' );
require_once( $_SERVER['DOCUMENT_ROOT'] . '/wp-includes/wp-db.php' );
$wpdb = new wpdb( DB_USER, DB_PASSWORD, DB_NAME, DB_HOST);
$videos_table=$table_prefix . "yvf";

if(isset($_POST['home'])){
    $id=$_POST['id'];
    //print_r($id);
    $query="SELECT * FROM ".$videos_table." WHERE id<".$id." AND tolive<current_timestamp ORDER BY id DESC LIMIT 16";
    $results=$wpdb->get_results($query);
   // print_r($results);
    ?>
    <div class="row">
<h1 class="heading"><strong>More Videos</strong></h1>
</div>
<div class="row">
<?php $count=0; foreach($results as $result){$count++;?>
<div class="col-lg-3 col-md-6 col-sm-12">
<div class="blocks"><a href="/watch?v=<?php echo $result->url;?>"><img src="<?php echo $result->Thumbnail;?>" 
             class="img-responsive" alt="Responsive image" 
             width="200" height="200" /></a>
              <h2><?php echo $result->title;?></h2>
             </div>
             </div>
<?php }?>
</div>
<div class="row">
<div class="d-flex justify-content-center">
<button class="btn btn-primary" id="loadold" data-id="<?if($count) echo $results[$count-1]->id; else echo "20000" ?>">Load New</button>
</div>
</div>
</div>
    <?
    exit();
}

$query="SELECT * FROM ".$videos_table." WHERE category=".$_POST['category']." AND id<".$_POST['id']." AND tolive<current_timestamp ORDER BY tolive,id DESC LIMIT 16";
if(isset($_POST['category'])){
    $query="SELECT * FROM ".$videos_table." WHERE category=".$_POST['category']." AND id<".$_POST['id']." AND tolive<current_timestamp ORDER BY tolive,id DESC LIMIT 16";
    if(isset($_POST['choice'])){
        $choice=$_POST['choice'];
        if($choice==2){
            $query="SELECT * FROM ".$videos_table." WHERE category=".$_POST['category']." AND tolive<current_timestamp AND id>".$_POST['id']." LIMIT 16";
        }
        if($choice==1){
            $query="SELECT * FROM ".$videos_table." WHERE category=".$_POST['category']."AND tolive<current_timestamp AND id<".$_POST['id']." AND pinned=1 ORDER BY id DESC LIMIT 16";
        }
    }
    //$query="SELECT * FROM ".$videos_table." WHERE category=".$_POST['category']." AND id<".$_POST['id']." ORDER BY id DESC LIMIT 16";
    //echo $query;
$results=$wpdb->get_results($query);
 $count=0; foreach($results as $result){$count++;?>
<div class="col-lg-3 col-md-6 col-sm-12">
<div class="blocks"><a href="/watch?v=<?php echo $result->url;?>" video_id="<?php echo $result->url;?>"><img src="<?php echo $result->Thumbnail;?>" 
             class="img-responsive" alt="Responsive image" 
             width="200" height="200" /></a>
              <h2><?php echo $result->title;?></h2>
              <?if($count==1){
                  ?>
                  <input type="hidden" id="data" value="<?echo $results[count($results)-1]->id;?>">
                  <?
              }?>
             </div>
             </div>

          
<?php }
 exit();
}
if(isset($_POST['url'])&&isset($_POST['ajax'])){
   $query="SELECT * FROM ".$videos_table." WHERE url='".$_POST['url']."'";
$result=$wpdb->get_results($query);
print_r(json_encode($result[0]));
    exit();
}
//echo "hi";
//echo $_POST['id'];
//echo $_POST['act'];
if(isset($_POST['id'])&&isset($_POST['act'])){
    $id=(int)$_POST['id'];
    if($_POST['act']=='next')
 $query='SELECT * FROM '.$videos_table.' WHERE id<'.($id-4).' AND tolive<=current_timestamp ORDER BY id DESC LIMIT 8';
 else if($_POST['act']=='back')
  $query='SELECT * FROM '.$videos_table.' WHERE id>'.($id+4).' AND tolive<=current_timestamp ORDER BY id DESC LIMIT 8';
}
//echo $query;
$results=$wpdb->get_results($query);
//print_r($results);
if(empty($results)){
    $results=$wpdb->get_results("SELECT * FROM {$videos_table} ORDER BY id DESC LIMIT 8  ");
   
}
?>

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
<h1 > <button data-id="<?php echo $result->id;?>" id="next" class="btn btn-primary">Load more</button></h1>
</div>
</div>
