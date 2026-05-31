<?php
    require_once 'connector.php';
function addProgram($conn, $post) {
    $prog_name = trim($post['prog_name']);
    $code      = strtoupper(trim($post['code']));

    // Check if already exists
    $check = $conn->prepare("SELECT program_id FROM program WHERE program_code = ? OR program_name = ?");
    $check->bind_param("ss", $code, $prog_name);
    $check->execute();
    $check->store_result();

    if($check->num_rows > 0) {
        return 'exists';
    }

    $stmt = $conn->prepare("INSERT INTO program (program_name, program_code) VALUES (?, ?)");
    $stmt->bind_param("ss", $prog_name, $code);

    return $stmt->execute() ? true : false;
}

?>