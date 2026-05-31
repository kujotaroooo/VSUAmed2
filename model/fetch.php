<?php
    require_once 'connector.php';

    function getPrograms(){
        global $conn;

        $sql = "SELECT * FROM program";
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        $result = $stmt->get_result();

        $programs = [];

        while($row = $result->fetch_assoc()){
            $programs[] = $row;
        }
        return $programs;
    }

    function getYear(){
        global $conn;

        $sql = "SELECT * FROM year_level";
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        $result = $stmt->get_result();

        $year = [];

        while($row = $result->fetch_assoc()){
            $year[] = $row;
        }
        return $year;
    }

    function getSection(){
        global $conn;

        $sql = "SELECT * FROM section";
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        $result = $stmt->get_result();

        $section = [];

        while($row = $result->fetch_assoc()){
            $section[] = $row;
        }
        return $section;

    }

function getVisitHistory(){
    global $conn;

    $sql = "SELECT v.*, p.*, s.*, a.*,
                   prog.program_name,
                   sec.section_name,
                   yl.year_level_name
            FROM visits AS v
            INNER JOIN prescription AS p ON p.visit_id = v.visit_id
            INNER JOIN students AS s ON s.student_id = v.student_id
            INNER JOIN admin AS a ON a.staff_id = v.staff_id
            LEFT JOIN student_enrollment AS se ON se.student_id = s.student_id
            LEFT JOIN program AS prog ON prog.program_id = se.program_id
            LEFT JOIN section AS sec ON sec.section_id = se.section_id
            LEFT JOIN year_level AS yl ON yl.year_level_id = se.year_level_id
            ORDER BY v.created_at DESC";

    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();
    $history = [];
    while($row = $result->fetch_assoc()){
        $history[] = $row;
    }
    return $history;
}

    function getE_Student(){
        global $conn;

        $sql = "SELECT * FROM students AS s
                INNER JOIN student_enrollment AS se ON se.student_id = s.student_id
                WHERE se.status = 'Active'
                OR se.status = 'Irregular'";
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        $result = $stmt->get_result();

        $section = [];

        while($row = $result->fetch_assoc()){
            $section[] = $row;
        }
        return $section;

    }

    function getRecordByMonth(){
        global $conn;

        $sql = "SELECT
                MONTHNAME(created_at) AS month,
                COUNT(*) AS total
            FROM visits
            GROUP BY MONTH(created_at)
            ORDER BY MONTH(created_at)";

        $stmt = $conn->prepare($sql);
        $stmt->execute();

        $result = $stmt->get_result();

        $months = [];
        $totals = [];

        while($row = $result->fetch_assoc()){
            $months[] = $row['month'];
            $totals[] = $row['total'];
        }

        return [
            'months' => $months,
            'totals' => $totals
        ];
    }

?>