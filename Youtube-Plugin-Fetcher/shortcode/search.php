<form action="/search" method="GET">
  <div class="form-group">
    <input name="search" type="search" class="form-control" id="InputSearch" aria-describedby="emailHelp" placeholder="Enter Search Query">

  </div>
  <button type="submit" class="btn btn-primary">Search</button>
</form>
<?php
if(isset($_GET['search'])){
   // echo 'Hi';
     global $wpdb;
    $videos_table=$wpdb->prefix . "yvf";
    //echo "SELECT * FROM {$videos_table} ORDER BY id DESC";
    $q=$_GET['search'];
    $results=$wpdb->get_results("SELECT * FROM {$videos_table} WHERE tolive<current_timestamp ORDER BY tolive DESC");
    //print_r($results);
    $search=array('No Result Found');
    $count=0;
    foreach($results as $result){
        $pos1 = strpos($result->title, $q);
        if($pos1!==false){
            $count++;
            array_push($search,$result);
        }
        else{
            //echo $result->tags;
            $tags=explode(" ",$result->tags);
            //print_r($tags);
            foreach($tags as $key=>$value){
                $pos2 = strpos($q, $value);
        if($pos2!==false){
            $count++;
            array_push($search,$result);
            break;
        }
            }
        }
    }
    //print_r($search);
   
    $length=(int)count($search);
    if($length==1)
    echo "<h3>NO Search Result Found</h3>";
    else{
        ?>
        <div class="container" id="latest">
<div class="row">
<h1 class="heading"><strong>Search Result for For <?php echo $q;?></strong></h1>
</div>
<div class="row">
<?php $count=0; foreach($search as $vid){$count++;?>
<div class="col-lg-3 col-md-6 col-sm-12">
<div class="blocks"><a href="/watch?v=<?php echo $vid->url;?>" video_id="<?php echo $vid->url;?>"><img src="<?php echo $vid->Thumbnail;?>" 
             class="img-responsive" alt="Responsive image" 
             width="200" height="200" /></a>
              <h2><?php echo $vid->title;?></h2>
             </div>
             </div>
          
<?php }?>


</div>
<div class="row">
<div class="col-12 d-flex justify-content-center">
<h1 > <button data-id="<? echo $cat_id;?>" action="latest" id="loadmore" class="btn btn-primary">Load more</button></h1>
</div>
</div>

</div>
        <?
        
//echo "Many Searches Found";

    }
  // 
    
   
}
else{
    echo '<h4>Please Search a query</h4>';
}

/*

 global $wpdb;
    $videos_table=$wpdb->prefix . "yvf";
    echo "SELECT * FROM {$videos_table} ORDER BY id DESC";
    $q=$_GET['search']);
    $results=$wpdb->get_results("SELECT * FROM {$videos_table} ORDER BY id DESC");
    print_r($results);
    $search=array('No Result Found');
    $count=0;
    foreach($results as $result){
        $pos1 = strpos($result->title, $q);
        if($pos1!==false){
            $count++;
            array_push($search,$result);
        }
        else{
            $tags=explode($result->tags);
            echo "Hello tags";
            print_r($tags);
            foreach($tags as $tag){
                $pos2 = strpos($q, $tag);
        if($pos2!==false){
            $count++;
            array_push($search,$result);
            break;
        }
            }
        }
    }
    print_r($search);

*/
?>