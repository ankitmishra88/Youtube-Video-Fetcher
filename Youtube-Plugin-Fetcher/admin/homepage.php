<?php
if(isset($_POST[settings]))
{
    global $wpdb;
    update_option('yvf_grid_blocks',$_POST['grid_blocks']);
    update_option('yvf_category_blocks',$_POST['category_blocks']);
    update_option('yvf_pinned_blocks',$_POST['pinned_blocks']);
    update_option('yvf_curr_setting',$_POST['curr_setting']);
    update_option('yvf_home_categories',serialize($_POST['categories']));
echo '<h3 class="alert success">Homepage Updated</h3>';


}

?>
<h3 class="d-flex justify-content-center">HomePage Setting</h3>
<form method="POST" action="">
<div class="section">
<h4 class="title">
Grid Type Setting
</h4>
<label for="num">No of Blocks</label>
<input type="number" name="grid_blocks" id="num" value="<? echo get_option('yvf_grid_blocks');?>">
</div>
<div class="section">
<h4 class="title">
Category Wise Setting
</h4>
<label for="pinned_blocks" >No of Pinned Blocks</label>
<input type="number" name="pinned_blocks" id="pinned_blocks" value="<? echo get_option('yvf_pinned_blocks');?>">
<label for="pinned_blocks">No of videos in each category</label>
<input type="number" name="category_blocks" id="category_blocks" value="<? echo get_option('yvf_category_blocks');?>">
<h5>Select Categories to fetch</h5>
<?global $wpdb;
$videos_table=$wpdb->prefix . "yvf";
$category_table=$wpdb->prefix . "ctb";
$checked_cat=unserialize(get_option('yvf_home_categories'));
$cats=$wpdb->get_results("SELECT * FROM {$category_table}");
//$results=$wpdb->get_results("SELECT * FROM {$videos_table} WHERE tolive<=current_timestamp ORDER BY pinned,tolive LIMIT 16  ");
foreach($cats as $cat){
    ?>
    <input type="checkbox" name="categories[]" value="<? echo $cat->id?>" <?if(array_search($cat->id,$checked_cat)||array_search($cat->id,$checked_cat)===0) echo 'checked';?>><label><? echo $cat->title;?></label>
    <?
}
?>
</div>
<p>Current Homepage</p>
<input type="radio" name="curr_setting" value="0" <?if(get_option('yvf_curr_setting')==0) echo checked;?>><label>Grid Type</label>
<input type="radio" name="curr_setting" value="1" <?if(get_option('yvf_curr_setting')==1) echo checked;?>><label>Category Wise</label>
<input type="submit" name="settings" class="btn btn-primary">
</form>