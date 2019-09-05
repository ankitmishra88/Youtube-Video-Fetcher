<?php 
$p=0;
global $wpdb;
if(isset($_POST['delete'])){
   echo "HI";
   if(isset($_POST['id'])){
        $p=1;
        $wpdb->delete($fetcher->aParam['videos_table'],array('id'=>$_POST['id']),array( '%d' ));
    }
   if(isset($_POST['category'])){
        $cat=$_POST['category'];
   $query="SELECT * FROM  {$fetcher->aParam['videos_table']} WHERE category={$cat}";
   $results1 = $wpdb->get_results($query,ARRAY_A);
   $p=1;
}
}
if(isset($_POST['category_sub'])){
    if(isset($_POST['id'])&&isset($_POST['category_upd'])&&isset($_POST['pinned'])&&isset($_POST['tolive'])){
        $tolive=date('Y-m-d',strtotime($_POST['tolive']));
        $p=1;
        $wpdb->update($fetcher->aParam['videos_table'],array('category'=>$_POST['category_upd'],'pinned'=>$_POST['pinned'],'tolive'=>$tolive),array('id'=>$_POST['id']),array( '%s','%d','%s' ), array( '%d' ));
    }
    if(isset($_POST['category'])){
        $cat=$_POST['category'];
   $query="SELECT * FROM  {$fetcher->aParam['videos_table']} WHERE category={$cat}";
   
        $p=1;
        $results1 = $wpdb->get_results($query,ARRAY_A);
        //print_r($results1);
    }
}?>
<form method="post" action="">
<p><label>Select Category</label> <select name="category">
        <?
        
        $query="SELECT * FROM ".$fetcher->aParam['category_table'];
        
        $results = $wpdb->get_results($query,ARRAY_A);
        $count=0;
        foreach($results as $key=>$value){
            $count++;
            echo "<option value='".$value['id']."'>".$value['title']."</option>";
        }
        ?>
        
        </select> </p>
        <button name='category_sub' class="btn btn-primary"> See All Videos</button>
</form>
<?php
if($p){?>
    <div class="container">
    <div class="row"><?
    foreach($results1 as $key=>$value){
            ?>
<div class="col col-sm-12 col-md-6 col-lg-3">
<img src="<?php echo $value['Thumbnail'];?>" class="img-fluid" style="max-height:250px; max-width:250px;"/>
<p><? echo $value['title'];?></p>
<p>Uploaded On: <?php echo $value['date'];?></p>
<form method="post">
<input type="hidden" name="id" value="<?php echo $value['id'];?>">
<input type="hidden" name="category" value="<?php echo $_POST['category'];?>">
<p><label>Select Category</label> <select name="category_upd">
        <?
        
        $query="SELECT * FROM ".$fetcher->aParam['category_table'];
        
        $results = $wpdb->get_results($query,ARRAY_A);
        $count=0;
        $selected="";
        
        foreach($results as $key1=>$value1){
            if($value1['id']==$_POST['category'])
        $selected="selected";
            $count++;
            echo "<option value='".$value1['id']."' ".$selected.">".$value1['title']."</option>";
            $selected="";
        }
        ?>
        
        </select>
        <label for="pinned">Select Priority</label>
        <select name="pinned" class="btn btn-primary" id="pinned">
        <option value="0" <?if($value['pinned']=='0') echo 'selected'?>>Latest</option>
         <option value="1" <?if($value['pinned']=='1') echo 'selected'?>>Handpicked</option>
        </select>
        <?
        $date=$value['tolive'];
        $tolive_date = date("Y-m-d", strtotime($date));
        ?>
        <label for="date">Publish on:</label><input id="date" type="date" name="tolive" value="<? echo $tolive_date?>">
         <button type="submit" name="category_sub" class="btn btn-primary">Update</button></p>
</form>
<form method="post">
<input type="hidden" name="id" value="<?php echo $value['id'];?>">
<input type="hidden" name="category" value="<?php echo $_POST['category'];?>">
<p><button type="submit" name="delete" class="btn btn-primary">Delete</button></p>
</form>
</div>
            <?
        }?>
        </div>
        </div><?
}?>
<!--<div class="container">
<div class="row">
<div class="col-sm">
<img src="https://images-na.ssl-images-amazon.com/images/I/71etj1mmV9L._SL1500_.jpg" class="img-fluid" max-width=""/>
<p>Name 1</p>
</div>
<div class="col-sm">
<img src="https://images-na.ssl-images-amazon.com/images/I/71etj1mmV9L._SL1500_.jpg" class="img-fluid" />
<p>Name 1</p>
</div>
<div class="col-sm">
<img src="https://images-na.ssl-images-amazon.com/images/I/71etj1mmV9L._SL1500_.jpg" class="img-fluid"/>
<p>Name 1</p>
</div>
</div>
</div>-->