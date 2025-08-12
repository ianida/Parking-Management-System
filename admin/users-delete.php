<?php

require '../config/function.php';

$paraResult = checkparamid('id');
if (is_numeric($paraResult)) {
    $userId = validate($paraResult);
    $user = getById('users', $userId);
    if ($user['status'] == 200) {
        $userDeleteRes = deleteQuery('users', $userId);
        if ($userDeleteRes) {
            redirect('admin/users.php', 'User deleted successfully');
        } else {
            redirect('admin/users.php', 'Something went wrong');
        }
    } else {
        redirect('admin/users.php', $user['message']);
    }
} else {
    redirect('admin/users.php', $paraResult);
}
?>
