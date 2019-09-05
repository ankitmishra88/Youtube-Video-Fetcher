<?php
if(isset($_POST['update'])){
    update_option('yvf_api_key',$_POST['api_key']);
    update_option('watch_video',$_POST['watch_video']);
    update_option('watch_size',$_POST['watch_size']);
}

?>
<form method="post" action="">
<label for="api_key">
Enter Api key
</label>
<input type="text" value="<?php echo get_option('yvf_api_key')?>" name="api_key" id="key" required>
<label for="default_video">
Default Video
</label>
<select name="watch_video" required>
<?
global $wpdb;
$videos_table=$wpdb->prefix . "yvf";
$results=$wpdb->get_results("SELECT * FROM {$videos_table} ORDER BY id DESC LIMIT 50  ");
foreach($results as $result){
    $selected="";
    if(get_option('watch_video')==$result->id){
        $selected="selected";
    }
    echo "<option value='{$result->id}' {$selected}>{$result->title}</option>";
}
?>
</select>
<select name="watch_size" required>
<option value="21by9">21:9</option>
<option value="16by9">16:9</option>
<option value="4by3">4:3</option>
</select>
<button type="submit" name='update' class="btn btn-primary">submit</button>
</form>
<?php
echo "Your current Api key:".get_option('yvf_api_key');
?>