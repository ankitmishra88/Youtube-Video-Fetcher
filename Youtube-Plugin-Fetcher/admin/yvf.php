<?php
$video_table=$fetcher->aParam['videos_table'];
if(isset($_POST['final_submit'])){
    global $wpdb;
echo $title=$_POST['title'];
echo '</br>';
echo $des=$_POST['des'];
echo '</br>';
echo $thumb=$_POST['thumb'];
echo '</br>';
echo $published=$_POST['published'];
echo '</br>';
echo $views=$_POST['views'];
echo '</br>';
echo $likes=$_POST['likes'];
echo '</br>';
echo $dislike=$_POST['dislike'];
echo '</br>';
echo $tags=$_POST['tags'];
echo '</br>';
echo $vid_id=$_POST['url'];
echo '</br>';
echo $category=$_POST['category'];
echo '</br>';
echo $date=date("F j, Y, g:i a");
echo '</br>';
echo $tolive=$_POST['tolive'];
echo $tolive_date = date("Y-m-d", strtotime($tolive));
echo "These are the details going to be Added...Here";
echo $video_table;
echo '</br>Adding...';
$query="SELECT * FROM  {$video_table} WHERE url='{$vid_id}'";
echo $query;
$rowcount = $wpdb->get_var("SELECT * FROM  {$video_table} WHERE url='{$vid_id}'");
if($rowcount>0){
    echo $rowcount;
    echo 'Video Already Found';
   
}
$chk=$wpdb->insert($video_table, array(
   "title" => $title,
   "published" => $published,
   "description" => $des,
   "date" => $date,
   "views" => $views,
   "likes" => $likes,
   "dislikes" => $dislike ,
   "url" => $vid_id,
   "tags" => $tags,
   "thumbnail" => $thumb ,
   "category" => $category ,
   "tolive"=>$tolive_date,
));
if($chk)
echo "Added Successfully";
else
echo "Some error Occured!Try Again";

    exit();
}
if(isset($_POST['yvf_submit_si'])){
    $url="https://www.googleapis.com/youtube/v3/videos?part=snippet,statistics&id=".$_POST['video-id']."&key=".get_option('yvf_api_key');
    $data = file_get_contents($url);
    echo $url;
    $json_data=json_decode($data);
    
?>
<script>
//console.log(<?php echo json_encode($json_data);?>)
</script>
<?
$video_url='https://www.youtube.com/watch?v='.$json_data->items[0]->id;
?>
<form method="post" action="">
<h3>These Are the Data We Got</h3>
   <h3>Title :<input name ="title" value="<?php echo $json_data->items[0]->snippet->title;?>"required></h3>
  <h3>Description :<input name ="des" value="<?php echo $json_data->items[0]->snippet->description;?>"></h3>
  <h3>Thumbnail :<input name ="thumb" value="<?php echo $json_data->items[0]->snippet->thumbnails->default->url;?>"required></h3>
  <h3>Published At :<input name ="published" value="<?php  echo $json_data->items[0]->snippet->publishedAt;?>" read-only></h3>
   <h3>Total Views :<input name ="views" value="<?php echo $json_data->items[0]->statistics->viewCount;?>"></h3>
   <h3>Likes :<input name ="likes" value="<?php echo $json_data->items[0]->statistics->likeCount;?>"></h3>
   <h3>Dislikes :<input name ="dislike" value="<?php echo $json_data->items[0]->statistics->dislikeCount;?>"></h3>
   <h3>Tags :<input name ="tags" value="<?php echo implode($json_data->items[0]->snippet->tags);?>"read-only></h3>
   <h3>Video Id :<input name ="url" value="<?php echo $json_data->items[0]->id;?>"></h3>
   <h3>Select Publishing Date :<input name ="tolive" type="date"></h3>
    <h2>Select Category<select name="category">
        <?
        
        $query="SELECT * FROM ".$fetcher->aParam['category_table'];
        
        $results = $wpdb->get_results($query,ARRAY_A);
        $count=0;
        foreach($results as $key=>$value){
            $count++;
            echo "<option value='".$value['id']."'>".$value['title']."</option>";
        }
        ?>
        
        </select></h2>
        <button type="submit" name="final_submit">Add The Video</button>
        </form>
<?    
    exit();
}
if(isset($_POST['yvf_submit_pl'])){
   echo $tolive=$_POST['tolive'];
    echo $tolive_date = date("Y-m-d", strtotime($tolive));
    echo '</br>';
    $url="https://www.googleapis.com/youtube/v3/playlistItems?part=contentDetails&maxResults=50&playlistId=".$_POST['playlist-id']."&key=".get_option('yvf_api_key');
    $data = file_get_contents($url);
    echo $url;
    $json_data=json_decode($data);
    //print_r($json_data);
    //exit();
    $p=1;
    $count=0;
    while($p){
        echo "<h2>{$json_data->nextPageToken}</h2>";
    //print_r($json_data->items);
    foreach($json_data->items as $item){
        $count++;echo "<h2>{$count}</h2>";
        if(!isset($item->contentDetails->videoPublishedAt))
        continue;
        echo "<p>{$item->contentDetails->videoId}</p>";
        $url1="https://www.googleapis.com/youtube/v3/videos?part=snippet,statistics&id=".$item->contentDetails->videoId."&key=".get_option('yvf_api_key');
    $data1 = file_get_contents($url1);
    echo $url1;
    $json_data1=json_decode($data1);
    echo '</br>';
    echo $title=$json_data1->items[0]->snippet->title;
    echo '</br>';
   echo $des=$json_data1->items[0]->snippet->description;
   echo '</br>';
  echo $thumb=$json_data1->items[0]->snippet->thumbnails->default->url;
  echo '</br>';
  echo $published=$json_data1->items[0]->snippet->publishedAt;
  echo '</br>';
   echo $views=$json_data1->items[0]->statistics->viewCount;
   echo '</br>';
 echo $likes=$json_data1->items[0]->statistics->likeCount;
 echo '</br>';
   echo $dislike=$json_data1->items[0]->statistics->dislikeCount;
   echo '</br>';
   echo $tags=implode($json_data1->items[0]->snippet->tags);
   echo '</br>';
  echo $vid_id=$json_data1->items[0]->id;
  echo '</br>';
  echo $date=date("F j, Y, g:i a");
  echo '</br>';
  echo $category=$_POST['category'];
  echo '</br>';
//Checking And Adding New Video
$query="SELECT * FROM  {$video_table} WHERE url='{$vid_id}'";
echo $query;
$rowcount = $wpdb->get_var("SELECT * FROM  {$video_table} WHERE url='{$vid_id}'");

if($rowcount>0){
    echo $rowcount;
    echo 'Video Already Found';
    continue;
}
$chk=$wpdb->insert($video_table, array(
   "title" => $title,
   "published" => $published,
   "description" => $des,
   "date" => $date,
   "views" => $views,
   "likes" => $likes,
   "dislikes" => $dislike ,
   "url" => $vid_id,
   "tags" => $tags,
   "thumbnail" => $thumb ,
   "category" => $category ,
   "tolive" => $tolive_date,
));
if($chk)
echo "Added Successfully";
else
echo "Some error Occured!Try Again";

    }
    echo "Hi";
    echo $json_data->nextPageToken;
    if(isset($json_data->nextPageToken))
      $url="https://www.googleapis.com/youtube/v3/playlistItems?part=contentDetails&maxResults=50&playlistId=".$_POST['playlist-id']."&key=".get_option('yvf_api_key')."&pageToken=".$json_data->nextPageToken;
      else
      exit();
    $data = file_get_contents($url);
    echo $url;
    $json_data=json_decode($data);
    }
    exit();
}
if(isset($_POST['choice']))
{
    if($_POST['choice']=='full'){
        ?>
        <form method="post">
        <p>Enter Playlist Id: <input type="textbox" name="playlist-id" required> </p>
        
         <p>Select Category<select name="category" required>
        <?
        
        $query="SELECT * FROM ".$fetcher->aParam['category_table'];
        
        $results = $wpdb->get_results($query,ARRAY_A);
        $count=0;
        foreach($results as $key=>$value){
            $count++;
            echo "<option value='".$value['id']."'>".$value['title']."</option>";
        }
        ?>
        
        </select></p>
         <p>Select Publishing Date :<input name ="tolive" type="date" required></p>
       <p> <button type="submit" name="yvf_submit_pl">Extract Info</button></p>
        </form>
        <?
    }
    else if($_POST['choice']=='single'){
        ?>
        <form method="post">
        <h2>Enter Video Id: <input type="textbox" name="video-id" required></h2>
        
         <button type="submit" name="yvf_submit_si">Extract Info</button></h2>
        </form>
        <?
    }
    else{
        ?>
        <h2><strong>Invalid Choice</strong></h2>
        <?
    }
}
else{
?>
<form method="post" action="">
<div>
<h2>What do you want to add</h2>
</div>
<p><input type="radio" name="choice" value="single">A Single Video</p>
<p><input type="radio" name="choice" value="full">A Full Playlist</p>
<button type="submit">Submit</button>
</form>

<?}?>