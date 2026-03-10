<?php

function create_log($conn, $admin_id, $action, $description){

    $admin_id = mysqli_real_escape_string($conn,$admin_id);
    $action = mysqli_real_escape_string($conn,$action);
    $description = mysqli_real_escape_string($conn,$description);

    $query = "INSERT INTO tbl_logs (admin_id, action, description)
              VALUES ('$admin_id','$action','$description')";

    mysqli_query($conn,$query);

}