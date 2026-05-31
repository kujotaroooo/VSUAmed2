<?php
    session_start();
    require_once '../model/program.php';
    require_once '../model/connector.php';

    if($_SERVER["REQUEST_METHOD"] == 'POST'){

        if(isset($_POST['addProgram'])){
            $add = addProgram($conn, $_POST);

            if($add === 'exists'){
                $_SESSION['msg'] = ['type' => 'error', 'text' => 'Program already exists.'];
            } elseif($add) {
                $_SESSION['msg'] = ['type' => 'success', 'text' => 'Program added successfully.'];
            } else {
                $_SESSION['msg'] = ['type' => 'error', 'text' => 'Error adding program. Please try again.'];
            }

            header("Location: ../views/medicalrecord.php?pagess=addProgram");
            exit;
        }

    }
?>