<?php
    include 'includes/session.php';

    if(isset($_POST['add'])){
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $course = $_POST['course']; // Get course input
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $filename = $_FILES['photo']['name'];

        if(!empty($filename)){
            move_uploaded_file($_FILES['photo']['tmp_name'], '../images/'.$filename);    
        }

        // Generate voter ID
        $set = '123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $voter = substr(str_shuffle($set), 0, 15);

        // Insert the new voter into the database, including the course field
        $sql = "INSERT INTO voters (voters_id, password, firstname, lastname, course, photo) 
                VALUES ('$voter', '$password', '$firstname', '$lastname', '$course', '$filename')";
                
        if($conn->query($sql)){
            $_SESSION['success'] = 'Voter added successfully';
        }
        else{
            $_SESSION['error'] = $conn->error;
        }
    }
    else{
        $_SESSION['error'] = 'Fill up add form first';
    }

    header('location: voters.php');
?>
