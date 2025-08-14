<?php
// Start session only if none started yet
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/config.php';  // Adjust path if needed
require_once 'dbcon.php';

function validate($inputData){
    global $conn;
    $validateData = mysqli_real_escape_string($conn, $inputData);
    return trim($validateData);
}

function redirect($url, $status, $type = 'success'){
    $_SESSION['status'] = $status;
    $_SESSION['status_type'] = $type;  // 'success' or 'error'

    // Prepend BASE_URL if $url is not absolute
    if (!preg_match('/^https?:\/\//', $url)) {
        $url = BASE_URL . $url;
    }

    header('Location: ' . $url);
    exit(0);
}

//static message (doesnt do away until reload or user navigates to another page)
// function alertMessage()
// {
//     if(isset($_SESSION['status']))
//     {
//         $type = isset($_SESSION['status_type']) ? $_SESSION['status_type'] : 'success';

//         $class = ($type === 'error') ? 'alert alert-danger' : 'alert alert-success';

//         echo '<div class="'.$class.'"> 
//         <h6>'.htmlspecialchars($_SESSION['status']).'</h6>
//         </div>';

//         unset($_SESSION['status']);
//         unset($_SESSION['status_type']);
//     }
// }

// timer message but stiff ui
// function alertMessage()
// {
//     if(isset($_SESSION['status']))
//     {
//         $type = isset($_SESSION['status_type']) ? $_SESSION['status_type'] : 'success';
//         $class = ($type === 'error') ? 'alert alert-danger' : 'alert alert-success';

//         $message = '<div id="alert-msg" class="'.$class.'"> 
//         <h6>'.htmlspecialchars($_SESSION['status']).'</h6>
//         </div>';

//         unset($_SESSION['status']);
//         unset($_SESSION['status_type']);

//         return $message;
//     }
//     return '';
//}

function alertMessage()
{
    if(isset($_SESSION['status']))
    {
        $type = isset($_SESSION['status_type']) ? $_SESSION['status_type'] : 'success';
        $class = ($type === 'error') ? 'alert alert-danger' : 'alert alert-success';

        echo '<div class="'.$class.'" id="alert-msg"> 
            <h6>'.htmlspecialchars($_SESSION['status']).'</h6>
        </div>';

         // Remove after showing once
        unset($_SESSION['status']);
        unset($_SESSION['status_type']);
    }
}


function getAll($tableName)
{
    global $conn;

    $table= validate($tableName);
    $query = "SELECT * FROM $table";
    $result = mysqli_query($conn,$query);
    return $result;
}

function checkparamid($paramType)
{
    if(isset($_GET[$paramType])){
        if($_GET[$paramType]!= null){
            return $_GET[$paramType];
        }else{
            return 'No id found';
        }
    }else{
        return 'No id given';
    }
}

function getById($tableName,$id)
{
    global $conn;

    $table = validate($tableName);
    $id = validate($id);
    $query = "SELECT * FROM $table WHERE id='$id' LIMIT 1";
    $result = mysqli_query($conn,$query);

    if($result){
        if(mysqli_num_rows($result) == 1)
        {
            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
            $response = [
                'status' => 200,
                'message' => 'Fetched Data Successful',
                'data' => $row
            ];
            return $response;
        }
        else
        {
            $response = [
                'status' => 404,
                'message' => 'No Data Found'
            ];
            return $response;
        }
    }else{
        $response = [
            'status' => 500,
            'message' => 'Something went wrong'
        ];
        return $response;
    }
}

function deleteQuery($tableName,$id){
    global $conn;

    $table =validate($tableName);
    $id =validate($id);

    $query= "DELETE FROM $table WHERE id='$id' LIMIT 1";
    $result = mysqli_query($conn,$query);
    return $result;
}

function logoutSession()
{
    unset($_SESSION['auth']);
    unset($_SESSION['loggedInUserRole']);
    unset($_SESSION['loggedInUser']);
}
?>
