<?php
global $wpdb;
$table_name = $fetcher->aParam['category_table'];
?>
<h4>Add New Category</h4>
<form method="post" action="">
<p>Name Of Category : <input type="text" name="category" placeholder="Enter Name of Category" required></p>
<p>Enter Thumbnail Url:<input type="text" name="thumb" placeholder="Enter Custom Url Of Category Thumbnail" required></p>
<button type="submit" class="btn btn-primary" name="add">Add</button>
</form><?php
if(isset($_POST['add'])){
    $title=$_POST['category'];
    $thumb=$_POST['thumb'];
    $chk=$wpdb->insert( $table_name, array("title" => $title, "Thumbnail" => $thumb ));
                 if($chk){
                     echo '<div><p>New Category Added Successfully</p></div>';
                 }
}
$query="SELECT * FROM ".$fetcher->aParam['category_table'];
        $results = $wpdb->get_results($query,ARRAY_A);
        //print_r($results);
        echo '<table border="solid">';
        foreach($results as $key=>$value){
            echo "<tr><td>{$value['title']}</td><td>{$value['Thumbnail']}</td></tr>";
        }
        echo '</table>';
?>