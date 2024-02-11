<?php
session_start();
// include Library
include "./database/connection.php";
include("./TCPDF/tcpdf.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $jsonData = urldecode($_POST['jsonData']);
    $decodedData = json_decode($jsonData, true);
} else {
    echo "Invalid access to sample.php";
}

// $SessionArray = $_SESSION['SessionArray'];

// $CourseSubjectIdSetted = $SessionArray['CourseSubjectIdSetted'];
// $GradingSessionIdSetted = $SessionArray['GradingSessionIdSetted'];  
// $CourseSubjectNameSetted = $SessionArray['CourseSubjectNameSetted'];
$CourseSubjectIdSetted = $_POST['courseSubjectId'];
$GradingSessionIdSetted = $_POST['gradingSessionId'];
$CourseSubjectNameSetted = $_POST['courseSubjectName'];

$courseQuery = "SELECT * FROM course_subject_table WHERE course_subject_id = '$CourseSubjectIdSetted'";
$courseResult = $con->query($courseQuery)->fetch_assoc();

// Assuming $courseResult['course_subject_time_start'] is in the format 'HH:MM:SS'
$starttimeParts = explode(':', $courseResult['course_subject_time_start']);
$displayStartTime = date("h:i A", strtotime($starttimeParts[0] . ':' . $starttimeParts[1]));

$endtimeParts = explode(':', $courseResult['course_subject_time_end']);
$displayEndTime = date("h:i A", strtotime($endtimeParts[0] . ':' . $endtimeParts[1]));


// Make TCPDF Object 
$pdf = new TCPDF('P', 'mm', 'A4');

// Remove Default Header and Footer 
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);


// $file = file_get_contents('MOCK_DATA.json');
// $data = json_decode($file);

// Add Content for each page
$pageCount = 0;
$entriesPerPage = 20;
$totalEntries = count($decodedData);
for ($start = 0; $start < $totalEntries; $start += $entriesPerPage) {
    // Add Page
    $pdf->AddPage();

    $url = './image/headerlogo.png';
    $img = file_get_contents($url);
    $pdf->Image('./image/logo.png', 25, 5, '', 28);

    $pdf->setFont('Helvetica', 'B', 12);
    $pdf->Cell(43, 5, "", 0, 0);
    $pdf->Cell(0, 5, "GRANBY COLLEGES OF SCIENCE AND TECHNOLOGY", 0, 1);

    $pdf->setFont('times', 'B', 11);
    $pdf->Cell(45, 5, "", 0, 0);
    $pdf->Cell(0, 5, "Ibayo Silangan Naic, Cavite,", 0, 1);
    
    $pdf->Cell(47, 5, "", 0, 0);
    $pdf->Cell(0, 5, "Telefax: (046) 412-0437", 0, 1);
    $pdf->Ln();
    $pdf->Ln(5);

    $pdf->writeHTMLCell('','', '', '', '<hr style="height: 3px;">', 0);
    $pdf->Ln();


    $pdf->setFont('times', '', 10);
    $pdf->Cell(10, 7, "",);
    $pdf->Cell(42, 7, "COURSE", 0);
    $pdf->Cell(58, 7, ": " . ($courseResult['course_name'] ? : 'N/A'), 0);
    $pdf->Cell(33, 7, "DAY", 0);
    $pdf->Cell(55, 7, ": " . ($courseResult['course_subject_day'] ? : 'N/A'), 0);
    $pdf->Cell(10, 7, "");
    $pdf->Ln();

    $pdf->Cell(10, 7, "");
    $pdf->Cell(42, 7, "SUBJECT", 0);
    $pdf->Cell(58, 7, ": " . ($courseResult['subject_name'] ? : 'N/A'), 0);
    $pdf->Cell(33, 7, "TIME", 0);
    $pdf->Cell(55, 7, ": " . ($displayStartTime . ' - ' . $displayEndTime ? : 'N/A'), 0);
    $pdf->Cell(10, 7, "");
    $pdf->Ln();

    $pdf->Cell(10, 7, "");
    $pdf->Cell(42, 7, "SUBJ TITLE", 0);
    $pdf->Cell(58, 7, ": " . ($courseResult['subject_title'] ? : 'N/A'), 0);
    $pdf->Cell(33, 7, "ROOM", 0);
    $pdf->Cell(55, 7, ": " . ($courseResult['course_subject_room'] ? : 'N/A'), 0);
    $pdf->Cell(10, 7, "");
    $pdf->Ln();

    $pdf->Cell(10, 7, "");
    $pdf->Cell(42, 7, "SY", 0);
    $pdf->Cell(58, 7, ": " . ($courseResult['sy_start'] . ' - ' . $courseResult['sy_end'] ? : 'N/A'), 0);
    $pdf->Cell(33, 7, "SEMESTER", 0);
    $pdf->Cell(55, 7, ": " . ($courseResult['sy_semester'] ? : 'N/A'), 0);
    $pdf->Cell(10, 7, "");
    $pdf->Ln();

    $pdf->Ln(2);

    // Make Table
    $pdf->setFont('times', 'B', 10);
    $pdf->Cell(10, 7, "No.", 1, 0, 'C');
    $pdf->Cell(50, 7, "Name", 1, 0, 'C');
    $pdf->Cell(10, 7, "P.G", 1, 0, 'C');
    $pdf->Cell(10, 7, "20%", 1, 0, 'C');
    $pdf->Cell(10, 7, "M.G", 1, 0, 'C');
    $pdf->Cell(10, 7, "20%", 1, 0, 'C');
    $pdf->Cell(10, 7, "S.G", 1, 0, 'C');
    $pdf->Cell(10, 7, "20%", 1, 0, 'C');
    $pdf->Cell(10, 7, "F.G", 1, 0, 'C');
    $pdf->Cell(10, 7, "20%", 1, 0, 'C');
    $pdf->Cell(15, 7, "Finals", 1, 0, 'C');
    $pdf->Cell(15, 7, "Equiv.", 1, 0, 'C');
    $pdf->Cell(20, 7, "Remarks", 1, 1, 'C');

    $pdf->setFont('times', '', 10);
   // Loop Data for the current page
    for ($i = $start; $i < min($start + $entriesPerPage, $totalEntries); $i++) {
        $pdf->Cell(10, 7, $decodedData[$i][0], 1, 0, 'C');
        $pdf->Cell(50, 7, $decodedData[$i][1], 1, 0, 'L');
        $pdf->Cell(10, 7, round($decodedData[$i][2]), 1, 0, 'C');
        $pdf->Cell(10, 7, round($decodedData[$i][3]), 1, 0, 'C');
        $pdf->Cell(10, 7, round($decodedData[$i][4]), 1, 0, 'C');
        $pdf->Cell(10, 7, round($decodedData[$i][5]), 1, 0, 'C');
        $pdf->Cell(10, 7, round($decodedData[$i][6]), 1, 0, 'C');
        $pdf->Cell(10, 7, round($decodedData[$i][7]), 1, 0, 'C');
        $pdf->Cell(10, 7, round($decodedData[$i][8]), 1, 0, 'C');
        $pdf->Cell(10, 7, round($decodedData[$i][9]), 1, 0, 'C');
        $pdf->Cell(15, 7, round($decodedData[$i][10]), 1, 0, 'C');
        $pdf->Cell(15, 7, $decodedData[$i][11], 1, 0, 'C');
        $pdf->Cell(20, 7, $decodedData[$i][12], 1, 1, 'C');
    }

    if (($start + $entriesPerPage) >= $totalEntries) {
        $pdf->setFont('times', 'B', 10);
        $pdf->Cell(190, 7, "-----NOTHING FOLLOWS-----", 'LBR', 1, 'C');
    }
    $pdf->Cell(190, 7, " ", 'LR', 1);
    
    $pdf->Cell(10, 0, "", 'L');
    $pdf->Cell(52, 0, "1.0 to- 5.0 SCALE", 0);
    $pdf->Cell(48, 0, "PERCENTAGE EQUIVALENT", 0);
    $pdf->Cell(60, 0, "", 0);
    $pdf->Cell(20, 0, "", 'R', 1);

    $pdf->setFont('times', '', 10);
    
    $pdf->Cell(10, 0, "", 'L');
    $pdf->Cell(52, 0, "1.00", 0);
    $pdf->Cell(48, 0, "99-100%", 0);
    $pdf->Cell(60, 0, "", 0);
    $pdf->Cell(20, 0, "", 'R', 1);

    $pdf->Cell(10, 0, "", 'L');
    $pdf->Cell(52, 0, "1.25", 0);
    $pdf->Cell(58, 0, "96-98%", 0);
    $pdf->setFont('times', 'B', 10);
    $pdf->Cell(60, 0, ($courseResult['course_subject_teacher'] ? : 'N/A'), 'B', 0, 'C');
    $pdf->setFont('times', '', 10);
    $pdf->Cell(10, 0, "", 'R', 1);
    
    $pdf->Cell(10, 0, "", 'L');
    $pdf->Cell(52, 0, "1.50", 0);
    $pdf->Cell(58, 0, "93-95%", 0);
    $pdf->Cell(60, 0, "Professor/Instructor", 0, 0, 'C');
    $pdf->Cell(10, 0, "", 'R', 1);

    $pdf->Cell(10, 0, "", 'L');
    $pdf->Cell(52, 0, "1.75", 0);
    $pdf->Cell(48, 0, "90-92%", 0);
    $pdf->Cell(60, 0, "", 0, 0);
    $pdf->Cell(20, 0, "", 'R', 1);

    $pdf->Cell(10, 0, "", 'L');
    $pdf->Cell(52, 0, "2.00", 0);
    $pdf->Cell(48, 0, "87-89%", 0);
    $pdf->Cell(60, 0, "", 0, 0);
    $pdf->Cell(20, 0, "", 'R', 1);

    $pdf->Cell(10, 0, "", 'L');
    $pdf->Cell(52, 0, "2.25", 0);
    $pdf->Cell(48, 0, "84-86%", 0);
    $pdf->Cell(60, 0, "", 0, 0);
    $pdf->Cell(20, 0, "", 'R', 1);

    $pdf->Cell(10, 0, "", 'L');
    $pdf->Cell(52, 0, "2.50", 0);
    $pdf->Cell(58, 0, "81-83%", 0);
    $pdf->setFont('times', 'B', 10);
    $pdf->Cell(60, 0, ($courseResult['course_subject_program_head'] ? : 'N/A'), 'B', 0, 'C');
    $pdf->setFont('times', '', 10);
    $pdf->Cell(10, 0, "", 'R', 1);

    $pdf->Cell(10, 0, "", 'L');
    $pdf->Cell(52, 0, "2.75", 0);
    $pdf->Cell(58, 0, "78-80%", 0);
    $pdf->Cell(60, 0, "Program Head/Program Adviser", 0, 0, 'C');
    $pdf->Cell(10, 0, "", 'R', 1);

    $pdf->Cell(10, 0, "", 'L');
    $pdf->Cell(52, 0, "3.00", 0);
    $pdf->Cell(48, 0, "75-77%", 0);
    $pdf->Cell(60, 0, "", 0, 0);
    $pdf->Cell(20, 0, "", 'R', 1);

    $pdf->Cell(10, 0, "", 'L');
    $pdf->Cell(52, 0, "4.00", 0);
    $pdf->Cell(48, 0, "65-74%", 0);
    $pdf->Cell(60, 0, "", 0, 0);
    $pdf->Cell(20, 0, "", 'R', 1);

    $pdf->Cell(10, 0, "", 'L');
    $pdf->Cell(52, 0, "5.00", 0);
    $pdf->Cell(48, 0, "FAILED", 0);
    $pdf->Cell(60, 0, "", 0, 0);
    $pdf->Cell(20, 0, "", 'R', 1);

    $pdf->Cell(10, 0, "", 'L');
    $pdf->Cell(52, 0, "S", 0);
    $pdf->Cell(58, 0, "Satisfactory", 0);
    $pdf->setFont('times', 'B', 10);
    $pdf->Cell(60, 0, "DR. MERCURIO G. VILLANUEVA, CSP, DBM", 'B', 0, 'C');
    $pdf->setFont('times', '', 10);
    $pdf->Cell(10, 0, "", 'R', 1);

    $pdf->Cell(10, 0, "", 'L');
    $pdf->Cell(52, 0, "CR", 0);
    $pdf->Cell(58, 0, "Credited", 0);
    $pdf->Cell(60, 0, "President/ The Founder", 0, 0, 'C');
    $pdf->Cell(10, 0, "", 'R', 1);

    $pdf->Cell(10, 0, "", 'L');
    $pdf->Cell(52, 0, "INC", 0);
    $pdf->Cell(48, 0, "Incomplete", 0);
    $pdf->Cell(60, 0, "", 0, 0);
    $pdf->Cell(20, 0, "", 'R', 1);

    $pdf->Cell(10, 0, "", 'L');
    $pdf->Cell(52, 0, "D", 0);
    $pdf->Cell(48, 0, "Dropped", 0);
    $pdf->Cell(60, 0, "", 0, 0);
    $pdf->Cell(20, 0, "", 'R', 1);

    $pdf->Cell(10, 0, "", 'L');
    $pdf->Cell(52, 0, "W", 0);
    $pdf->Cell(73, 0, "Withdrawn (Authorized)", 0);
    $pdf->Cell(30, 0, "", 'B', 0, 'C');
    $pdf->Cell(25, 0, "", 'R', 1);

    $pdf->Cell(10, 0, "", 'L');
    $pdf->Cell(52, 0, "NC", 0);
    $pdf->Cell(73, 0, "Non-Credit", 0);
    $pdf->Cell(30, 0, "Date", 0, 0, 'C');
    $pdf->Cell(25, 0, "", 'R', 1);

    $pdf->Cell(190, 0, " ", 'LR', 1);
    $pdf->Cell(190, 0, " ", 'LBR', 1);

    $pageCount++;
}

// Output
$pdf->Output();
?>
