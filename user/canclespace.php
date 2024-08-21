<?php
include('../config/function.php');
include('include/dbcon.php');

$space_id = $_POST['space_id'];
$status=$_POST['status'];

if($status==0){
    // SQL query to delete a row
    $sql = "DELETE FROM space WHERE space_id = ? and user_id=?";

    // Prepare the statement
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        // Bind the value to the parameter
        $stmt->bind_param("ii", $space_id,$_SESSION['id']);  // Change "s" to the appropriate type (i.e., "i" for integer, "d" for double, "b" for blob)

        // Execute the query
        if ($stmt->execute()) {
            echo "Space removed";
        }
        // Close the statement
        $stmt->close();
        header("location:myspace.php");
    } else {
        echo "Error: " . $conn->error;
    }
}else{
    echo"Booked Space Cannot be deleted";
}

// Close the database connection
$conn->close();
?>
