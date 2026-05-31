<?php
    require_once 'connector.php';
 
function updateStudent($data) {
    global $conn;
 
    $stmt = $conn->prepare("UPDATE students SET last_name=?, first_name=?, middle_name=?, sex=?, birth_date=?, contact_number=?, stud_email=? WHERE student_id=?");
    $stmt->execute([
        $data['lname'],
        $data['fname'],
        $data['mname'],
        $data['sex'],
        $data['bday'],
        $data['cont'],
        $data['email'],
        $data['student_id']
    ]);
 
    $stmt2 = $conn->prepare("UPDATE student_enrollment SET year_level_id=?, program_id=?, section_id=? WHERE student_id=?");
    return $stmt2->execute([
        $data['year'],
        $data['prog'],
        $data['sec'],
        $data['student_id']
    ]);
}
function addStdnt($conn, $post){

    // Safety: skip if student number is empty
    if(empty(trim($post['num']))){
        return false;
    }

    $sqlCheck = "SELECT student_id FROM students WHERE student_number = ? AND student_number != ''";
    $stmtCheck = $conn->prepare($sqlCheck);
    $stmtCheck->bind_param("s", $post['num']);
    $stmtCheck->execute();
    $result = $stmtCheck->get_result();

    if ($result->num_rows > 0) {
        return "exists";
    }   
 
    $sql = "INSERT INTO students
    (student_number, first_name, last_name, middle_name, birth_date, sex, contact_number, stud_email)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
 
    $stmt = $conn->prepare($sql);
 
    $stmt->bind_param(
        "ssssssss",
        $post['num'],
        $post['fname'],
        $post['lname'],
        $post['mname'],
        $post['bday'],
        $post['sex'],
        $post['cont'],
        $post['email']
    );
 
    return $stmt->execute() ? $conn->insert_id : false;
}
 
 
    function search($conn, $post){
        $search = "%" . $post['search'] . "%";
        $sql = "SELECT *
                FROM students AS s
                INNER JOIN student_enrollment AS se ON se.student_id = s.student_id
                INNER JOIN program AS p ON p.program_id = se.program_id
                INNER JOIN year_level AS y ON y.year_level_id = se.year_level_id
                INNER JOIN section AS sec ON sec.section_id = se.section_id
                WHERE 
                    s.student_number LIKE ?
                    OR s.first_name LIKE ?
                    OR s.last_name LIKE ?
                    OR s.middle_name LIKE ?
                    OR CONCAT(s.first_name, ' ', s.last_name) LIKE ?
                    OR CONCAT(s.first_name, ' ', s.middle_name, ' ', s.last_name) LIKE ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssss", $search, $search, $search, $search, $search, $search);
        $stmt->execute();
        $result = $stmt->get_result();
        $students = [];
        while ($row = $result->fetch_assoc()) {
            $students[] = $row;
        }
        return $students;
    }
    
   function getStudentsByYear($pr, $yr){
    global $conn;
    $sql = "SELECT s.*, se.*,
                   p.program_code,
                   y.year_level_name,
                   sec.section_name
            FROM students AS s
            INNER JOIN student_enrollment AS se ON se.student_id = s.student_id
            INNER JOIN program AS p ON p.program_id = se.program_id
            INNER JOIN year_level AS y ON y.year_level_id = se.year_level_id
            INNER JOIN section AS sec ON sec.section_id = se.section_id
            WHERE
                se.program_id = ?
                AND se.year_level_id = ?
            ORDER BY sec.section_name ASC, s.last_name ASC, s.first_name ASC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $pr, $yr);
    $stmt->execute();
    $result = $stmt->get_result();
    $students = [];
    while ($row = $result->fetch_assoc()) {
        $students[] = $row;
    }
    return $students;
}
 
    function getRecentVisits($id){
        global $conn;
 
        $sql = "SELECT *
                FROM visits AS v
                INNER JOIN prescription AS p ON p.visit_id = v.visit_id
                INNER JOIN admin AS a ON a.staff_id = v.staff_id
                WHERE v.student_id = ?
                ORDER BY v.created_at DESC";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $records = [];
        while($row = $result->fetch_assoc()){
            $records[] = $row;
        }
        return $records;
    }
 function getVisitCount($id){
    global $conn;
    $sql = "SELECT COUNT(*) as total FROM visits WHERE student_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    return $result['total'] ?? 0;
}
function getStudentById($id){
    global $conn;
    $sql = "SELECT s.*,
                   p.program_code,
                   y.year_level_name,
                   se.status
            FROM students AS s
            INNER JOIN student_enrollment AS se ON se.student_id = s.student_id
            INNER JOIN program AS p ON p.program_id = se.program_id
            INNER JOIN year_level AS y ON y.year_level_id = se.year_level_id
            WHERE s.student_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}   function deleteStudent($id) {
    global $conn;

    $stmt = $conn->prepare("DELETE p FROM prescription p INNER JOIN visits v ON v.visit_id = p.visit_id WHERE v.student_id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    $stmt2 = $conn->prepare("DELETE FROM visits WHERE student_id=?");
    $stmt2->bind_param("i", $id);
    $stmt2->execute();

    $stmt3 = $conn->prepare("DELETE FROM student_enrollment WHERE student_id=?");
    $stmt3->bind_param("i", $id);
    $stmt3->execute();

    $stmt4 = $conn->prepare("DELETE FROM students WHERE student_id=?");
    $stmt4->bind_param("i", $id);
    return $stmt4->execute();
}
 
    function enrollStud($id, $post){
        global $conn;
        $sql = "INSERT INTO student_enrollment
        (student_id, year_level_id, program_id, section_id, status, enrollment_date)
        VALUES (?, ?, ?, ?, ?, NOW())";
 
        $stmt = $conn->prepare($sql);
 
        $stmt->bind_param(
            "sssss",
            $id,
            $post['year'],
            $post['prog'],
            $post['sec'],
            $post['status']
        );
 
        $stmt->execute();
        return $stmt;
 
    }
 
    function getStudentByNumber($num){
        global $conn;
 
        $sql = "SELECT student_id
                FROM students
                WHERE student_number = ?";
 
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $num['stud_number']);
        $stmt->execute();
 
        $result = $stmt->get_result()->fetch_assoc();
 
        if ($result) {
            return $result['student_id'];
        }
 
        return null;
    }
 
    function addVisitInfo($id, $post){
        global $conn;
        $sql = "INSERT INTO visits (student_id, staff_id, complaint, diagnosis, notes, created_at)
                    VALUES (?, ?, ?, ?, ?, NOW())";
 
        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            "iisss",
            $id,
            $post['staff'],
            $post['complaint'],
            $post['diag'],
            $post['note']
        );
        if ($stmt->execute()) {
            return $conn->insert_id;
        }
    }
 
    function addPrescriptInfo($id, $post){
        global $conn;
        $sql = "INSERT INTO prescription (visit_id, medicine_name, dosage, duration, instructions)
                    VALUES (?, ?, ?, ?, ?)";
 
        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            "issss",
            $id,
            $post['med'],
            $post['dose'],
            $post['dur'],
            $post['ins']
        );
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }
?>
 