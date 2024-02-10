<?php
// session_start();
// include "./database/connection.php";

// // Query to fetch student details and grades for different sessions and components
// $sql = "SELECT 
//             s.student_id,
//             s.student_full_name,
//             gs.grading_session_id,
//             ct.component_name,
//             GROUP_CONCAT(sg.student_grade) AS component_grades
//         FROM 
//             student_table s
//             LEFT JOIN student_grade_table sg ON s.student_id = sg.student_id
//             LEFT JOIN component_value_table cv ON sg.component_value_id = cv.component_value_id
//             LEFT JOIN component_table ct ON cv.component_id = ct.component_id
//             LEFT JOIN grading_session_table gs ON ct.grading_session_id = gs.grading_session_id
//         GROUP BY 
//             s.student_id, gs.grading_session_id, ct.component_id";

// $result = $con->query($sql);

// // Check if there are results
// if ($result->num_rows > 0) {
//     $studentsData = array();

//     while ($row = $result->fetch_assoc()) {
//         $studentId = $row['student_full_name'];
//         $gradingSessionId = $row['grading_session_id'];
//         $componentName = $row['component_name'];
//         $componentGrades = explode(',', $row['component_grades']);

//         $studentsData[$studentId][$gradingSessionId][$componentName] = $componentGrades;
//     }

//     // Output the result
//     foreach ($studentsData as $studentId => $data) {
//         echo "Student ID: " . $studentId . "\n";
//         // echo "Student Name: " . $data['student_full_name'] . "\n";
//         foreach ($data as $gradingSessionId => $sessionData) {
//             if ($gradingSessionId !== 'student_full_name') {
//                 echo "Grading Session ID: " . $gradingSessionId . "\n";
//                 foreach ($sessionData as $componentName => $grades) {
//                     echo $componentName . " Grades: {" . implode(',', $grades) . "}\n";
//                 }
//             }
//         }
//         echo '<hr>';
//     }
// } else {
//     echo "No records found";
// }

// // Close the connection
// $con->close();
?>


<?php
// session_start();
// include "./database/connection.php";

// // Query to fetch student details and grades for different sessions and components
// $sql = "SELECT 
//             s.student_id,
//             s.student_full_name,
//             gs.grading_session_id,
//             ct.component_name,
//             GROUP_CONCAT(sg.student_grade) AS component_grades,
//             GROUP_CONCAT(cv.component_value) AS component_values
//         FROM 
//             student_table s
//             LEFT JOIN student_grade_table sg ON s.student_id = sg.student_id
//             LEFT JOIN component_value_table cv ON sg.component_value_id = cv.component_value_id
//             LEFT JOIN component_table ct ON cv.component_id = ct.component_id
//             LEFT JOIN grading_session_table gs ON ct.grading_session_id = gs.grading_session_id
//         GROUP BY 
//             s.student_id, gs.grading_session_id, ct.component_id";

// $result = $con->query($sql);

// // Check if there are results
// if ($result->num_rows > 0) {
//     $studentsData = array();

//     while ($row = $result->fetch_assoc()) {
//         $studentId = $row['student_full_name'];
//         $gradingSessionId = $row['grading_session_id'];
//         $componentName = $row['component_name'];
//         $componentGrades = explode(',', $row['component_grades']);
//         $componentValues = explode(',', $row['component_values']);

//         $studentsData[$studentId][$gradingSessionId][$componentName][0] = $componentGrades;
//         $studentsData[$studentId][$gradingSessionId][$componentName][1] = $componentValues;
//     }

//     // Output the result
//     foreach ($studentsData as $studentId => $data) {
//         echo "Student ID: " . $studentId . "\n";
//         // echo "Student Name: " . $data['student_full_name'] . "\n";
//         foreach ($data as $gradingSessionId => $sessionData) {
//             if ($gradingSessionId !== 'student_full_name') {
//                 echo "Grading Session ID: " . $gradingSessionId . "\n";
//                 foreach ($sessionData as $componentName => $componentData) {
//                     echo $componentName . " Grades: {" . implode(',', $componentData[0]) . "}\n";
//                     echo $componentName . " Component Values: {" . implode(',', $componentData[1]) . "}\n";
//                 }
//             }
//         }
//         echo '<hr>';
//     }
// } else {
//     echo "No records found";
// }

// // Close the connection
// $con->close();
?>
<?php
// session_start();
// include "./database/connection.php";

// // Query to fetch student details and grades for different sessions and components
// $sql = "SELECT 
//             s.student_id,
//             s.student_full_name,
//             gs.grading_session_id,
//             gs.grading_session_name,
//             ct.component_name,
//             ct.component_percentage,
//             gs.grading_session_base,
//             GROUP_CONCAT(sg.student_grade) AS component_grades,
//             GROUP_CONCAT(cv.component_value) AS component_values
//         FROM 
//             student_table s
//             LEFT JOIN student_grade_table sg ON s.student_id = sg.student_id
//             LEFT JOIN component_value_table cv ON sg.component_value_id = cv.component_value_id
//             LEFT JOIN component_table ct ON cv.component_id = ct.component_id
//             LEFT JOIN grading_session_table gs ON ct.grading_session_id = gs.grading_session_id
//         GROUP BY 
//             s.student_id, gs.grading_session_id, ct.component_id";

// $result = $con->query($sql);

// // Check if there are results
// if ($result->num_rows > 0) {
//     $studentsData = array();

//     while ($row = $result->fetch_assoc()) {
//         $studentId = $row['student_full_name'];
//         $gradingSessionId = $row['grading_session_id'];
//         $componentName = $row['component_name'];
//         $componentPercentage = $row['component_percentage'];
//         $gradingSessionBase = (int)$row['grading_session_base'];
//         $componentGrades = explode(',', $row['component_grades']);
//         $componentValues = explode(',', $row['component_values']);

//         // $weightedGrades = calculateWeightedGrades($componentGrades, $componentValues, $gradingSessionBase); // For weighted grade (total of student_grade / total of component_value) * (100 - $grading_session_base) + grading_session_base 
//         $weightedGrades = calculateWeightedGrades($componentGrades, $componentValues, $gradingSessionBase, $componentPercentage);

//         $studentsData[$studentId][$gradingSessionId][$componentName][0] = $componentGrades;
//         $studentsData[$studentId][$gradingSessionId][$componentName][1] = $componentValues;
//         $studentsData[$studentId][$gradingSessionId][$componentName][2] = $weightedGrades;
//         $studentsData[$studentId][$gradingSessionId][$componentName][3] = $componentPercentage;
//     }

//     // Output the result
//     // Output the result
// foreach ($studentsData as $studentId => $data) {
//     echo "Student ID: " . $studentId . "\n";
//     foreach ($data as $gradingSessionId => $sessionData) {
//         if ($gradingSessionId !== 'student_full_name') {
//             echo "Grading Session ID: " . $gradingSessionId . "\n";
//             foreach ($sessionData as $componentName => $componentData) {
//                 echo $componentName . " Grades: {" . implode(',', $componentData[0]) . "}\n";
//                 echo $componentName . " Component Values: {" . implode(',', $componentData[1]) . "}\n";
//                 echo $componentName . " Weighted Grades: {" . $componentData[2] . "}\n";  // Access the weighted grade directly
//                 echo $componentName . " Component Percentage: {" . $componentData[3] . "}\n";  // Access the weighted grade directly
//             }
//         }
//     }
//     echo '<hr>';
// }

// } else {
//     echo "No records found";
// }

// // Close the connection
// $con->close();

// // Function to calculate weighted grades
// function calculateWeightedGrades($grades, $values, $base, $componentPercentage) {
//     $totalNormalizedGrades = 0;
//     $totalComponentValues = 0;

//     // Calculate the total sum of normalized grades and total sum of component values
//     foreach ($grades as $key => $grade) {
//         $totalNormalizedGrades += (int) $grade;
//         $totalComponentValues += (int) $values[$key];
//     }

//     // Calculate the weighted grade using the total sum of normalized grades and component values
//     // $weightedGrade = ($totalNormalizedGrades / max(1, $totalComponentValues)) * (100 - $base) + $base;

//     $weightedGrade = (($totalNormalizedGrades / max(1, $totalComponentValues)) * (100 - $base) + $base) * ($componentPercentage / 100);

//     return $weightedGrade;
// }

?>

<!-- THIS IS ALREADY OKAY -->

<?php
// session_start();
// include "./database/connection.php";

// // Query to fetch student details and grades for different sessions and components
// $sql = "SELECT 
//             s.student_id,
//             s.student_full_name,
//             gs.grading_session_id,
//             gs.grading_session_name,
//             ct.component_id,
//             ct.component_name,
//             ct.component_percentage,
//             gs.grading_session_base,
//             GROUP_CONCAT(sg.student_grade) AS component_grades,
//             GROUP_CONCAT(cv.component_value) AS component_values
//         FROM 
//             student_table s
//             LEFT JOIN student_grade_table sg ON s.student_id = sg.student_id
//             LEFT JOIN component_value_table cv ON sg.component_value_id = cv.component_value_id
//             LEFT JOIN component_table ct ON cv.component_id = ct.component_id
//             LEFT JOIN grading_session_table gs ON ct.grading_session_id = gs.grading_session_id
//         GROUP BY 
//             s.student_id, gs.grading_session_id, ct.component_id";

//     $result = $con->query($sql);

//     // Check if there are results
//     if ($result->num_rows > 0) {
//         $studentsData = array();

//         while ($row = $result->fetch_assoc()) {
//             $studentId = $row['student_full_name'];
//             $gradingSessionId = $row['grading_session_id'];
//             $componentId = $row['component_id'];
//             $componentName = $row['component_name'];
//             $componentPercentage = $row['component_percentage'];
//             $gradingSessionBase = (int)$row['grading_session_base'];
//             $componentGrades = explode(',', $row['component_grades']);
//             $componentValues = explode(',', $row['component_values']);
        
//             // Initialize $allPossibleComponentIds
//             $allPossibleComponentIds = range(0, count($componentGrades) - 1);
        
//             // Initialize arrays for all possible component IDs with default values of 0
//             $allComponentGrades = array_fill_keys($allPossibleComponentIds, 0);
//             $allComponentValues = array_fill_keys($allPossibleComponentIds, 0);
        
//             // Fill in actual grades and values at the correct component IDs
//             foreach ($componentGrades as $key => $grade) {
//                 $allComponentGrades[$key] = $grade;
//                 $allComponentValues[$key] = $componentValues[$key];
//             }
        
//             // Calculate weighted grades for each component
//             $weightedGrades = calculateWeightedGrades($allComponentGrades, $allComponentValues, $gradingSessionBase, $componentPercentage);
        
//             // Save the data in the array
//             $studentsData[$studentId][$gradingSessionId][$componentId][0] = $allComponentGrades;
//             $studentsData[$studentId][$gradingSessionId][$componentId][1] = $allComponentValues;
//             $studentsData[$studentId][$gradingSessionId][$componentId][2] = $weightedGrades;
//         }
        

//         // Output the result
//         foreach ($studentsData as $studentId => $data) {
//             echo "Student ID: " . $studentId . "\n";
//             foreach ($data as $gradingSessionId => $sessionData) {
//                 if ($gradingSessionId !== 'student_full_name') {
//                     echo "Grading Session ID: " . $gradingSessionId . "\n";
//                     foreach ($sessionData as $componentId => $componentData) {
//                         echo "Component ID: " . $componentId . "\n";
//                         echo $componentName . " Grades: {" . implode(',', $componentData[0]) . "}\n";
//                         echo $componentName . " Component Values: {" . implode(',', $componentData[1]) . "}\n";
//                         echo $componentName . " Weighted Grades: {" . $componentData[2] . "}\n";
//                     }
//                     // Calculate and output the total weighted grade for the session
//                     $totalWeightedGrade = calculateTotalWeightedGrade($sessionData);
//                     echo "Total Weighted Grade: " . $totalWeightedGrade . "\n";
//                 }
//             }
//             echo '<hr>';
//         }
//     } else {
//         echo "No records found";
//     }

//     // Close the connection
//     $con->close();

//     // Function to calculate weighted grades
//     function calculateWeightedGrades($grades, $values, $base, $componentPercentage) {
//         $totalNormalizedGrades = 0;
//         $totalComponentValues = 0;

//         // Calculate the total sum of normalized grades and total sum of component values
//         foreach ($grades as $key => $grade) {
//             $totalNormalizedGrades += (int) $grade;
//             $totalComponentValues += (int) $values[$key];
//         }

//         // Calculate the weighted grade using the total sum of normalized grades and component values
//         $weightedGrade = (($totalNormalizedGrades / max(1, $totalComponentValues)) * (100 - $base) + $base) * ($componentPercentage / 100);

//         return $weightedGrade;
//     }

//     // Function to calculate total weighted grade for the session
//     function calculateTotalWeightedGrade($sessionData) {
//         $totalWeightedGrade = 0;

//         foreach ($sessionData as $componentData) {
//             $totalWeightedGrade += $componentData[2];
//         }

//         return $totalWeightedGrade;
//     }
?>
<?php
// session_start();
// include "./database/connection.php";

// // Query to fetch student details and grades for different sessions and components
// $sql = "SELECT 
//             s.student_id,
//             s.student_full_name,
//             gs.grading_session_id,
//             gs.grading_session_name,
//             ct.component_id,
//             ct.component_name,
//             ct.component_percentage,
//             gs.grading_session_base,
//             GROUP_CONCAT(sg.student_grade) AS component_grades,
//             GROUP_CONCAT(cv.component_value) AS component_values
//         FROM 
//             student_table s
//             LEFT JOIN student_grade_table sg ON s.student_id = sg.student_id
//             LEFT JOIN component_value_table cv ON sg.component_value_id = cv.component_value_id
//             LEFT JOIN component_table ct ON cv.component_id = ct.component_id
//             LEFT JOIN grading_session_table gs ON ct.grading_session_id = gs.grading_session_id
//         GROUP BY 
//             s.student_id, gs.grading_session_id, ct.component_id";

// $result = $con->query($sql);

// // Check if there are results
// if ($result->num_rows > 0) {
//     $studentsData = array();

//     while ($row = $result->fetch_assoc()) {
//         $studentId = $row['student_full_name'];
//         $gradingSessionId = $row['grading_session_id'];
//         $componentId = $row['component_id'];
//         $componentName = $row['component_name'];
//         $componentPercentage = $row['component_percentage'];
//         $gradingSessionBase = (int)$row['grading_session_base'];
//         $componentGrades = explode(',', $row['component_grades']);
//         $componentValues = explode(',', $row['component_values']);

//         // Initialize $allPossibleComponentIds
//         $allPossibleComponentIds = range(0, count($componentGrades) - 1);

//         // Initialize arrays for all possible component IDs with default values of 0
//         $allComponentGrades = array_fill_keys($allPossibleComponentIds, 0);
//         $allComponentValues = array_fill_keys($allPossibleComponentIds, 0);

//         // Fill in actual grades and values at the correct component IDs
//         foreach ($componentGrades as $key => $grade) {
//             $allComponentGrades[$key] = $grade;
//             $allComponentValues[$key] = $componentValues[$key];
//         }

//         // Calculate weighted grades for each component
//         $weightedGrades = calculateWeightedGrades($allComponentGrades, $allComponentValues, $gradingSessionBase, $componentPercentage);

//         // Save the data in the array
//         $studentsData[$studentId][$gradingSessionId][$componentId][0] = $allComponentGrades;
//         $studentsData[$studentId][$gradingSessionId][$componentId][1] = $allComponentValues;
//         $studentsData[$studentId][$gradingSessionId][$componentId][2] = $weightedGrades;
//     }

//     // Output the result
//     foreach ($studentsData as $studentId => $data) {
//         echo "Student ID: " . $studentId . "\n";
//         foreach ($data as $gradingSessionId => $sessionData) {
//             if ($gradingSessionId !== 'student_full_name') {
//                 echo "Grading Session ID: " . $gradingSessionId . "\n";
//                 foreach ($sessionData as $componentId => $componentData) {
//                     echo "Component ID: " . $componentId . "\n";
//                     echo $componentName . " Grades: {" . implode(',', $componentData[0]) . "}\n";
//                     echo $componentName . " Component Values: {" . implode(',', $componentData[1]) . "}\n";
//                     echo $componentName . " Weighted Grades: {" . $componentData[2] . "}\n";
//                 }
//                 // Calculate and output the total weighted grade for the session
//                 $totalWeightedGrade = calculateTotalWeightedGrade($sessionData);
//                 echo "Total Weighted Grade: " . $totalWeightedGrade . "\n";
//             }
//         }
//         echo '<hr>';
//     }
// } else {
//     echo "No records found";
// }

// // Close the connection
// $con->close();

// // Function to calculate weighted grades
// function calculateWeightedGrades($grades, $values, $base, $componentPercentage) {
//     $totalNormalizedGrades = 0;
//     $totalComponentValues = 0;

//     // Calculate the total sum of normalized grades and total sum of component values
//     foreach ($grades as $key => $grade) {
//         $totalNormalizedGrades += (int) $grade;
//         $totalComponentValues += (int) $values[$key];
//     }

//     // Calculate the weighted grade using the total sum of normalized grades and component values
//     $weightedGrade = (($totalNormalizedGrades / max(1, $totalComponentValues)) * (100 - $base) + $base) * ($componentPercentage / 100);

//     return $weightedGrade;
// }

// // Function to calculate total weighted grade for the session
// function calculateTotalWeightedGrade($sessionData) {
//     $totalWeightedGrade = 0;

//     foreach ($sessionData as $componentData) {
//         $totalWeightedGrade += $componentData[2];
//     }

//     return $totalWeightedGrade;
// }

?>

<?php
session_start();
include "./database/connection.php";

// Query to fetch student details and grades for different sessions and components
$sql = "SELECT 
            s.student_id,
            s.student_full_name,
            gs.grading_session_id,
            gs.grading_session_percentage,
            gs.grading_session_name,
            ct.component_id,
            ct.component_name,
            ct.component_percentage,
            gs.grading_session_base,
            GROUP_CONCAT(sg.student_grade) AS component_grades,
            GROUP_CONCAT(cv.component_value) AS component_values
        FROM 
            student_table s
            LEFT JOIN student_grade_table sg ON s.student_id = sg.student_id
            LEFT JOIN component_value_table cv ON sg.component_value_id = cv.component_value_id
            LEFT JOIN component_table ct ON cv.component_id = ct.component_id
            LEFT JOIN grading_session_table gs ON ct.grading_session_id = gs.grading_session_id
        GROUP BY 
            s.student_id, gs.grading_session_id, ct.component_id";

$result = $con->query($sql);

// Check if there are results
if ($result->num_rows > 0) {
    $studentsData = array();

    while ($row = $result->fetch_assoc()) {
        $studentId = $row['student_full_name'];
        $gradingSessionId = $row['grading_session_id'];
        $gradingSessionPercentage = $row['grading_session_percentage'];
        $componentId = $row['component_id'];
        $componentName = $row['component_name'];
        $componentPercentage = $row['component_percentage'];
        $gradingSessionBase = (int)$row['grading_session_base'];
        $componentGrades = explode(',', $row['component_grades']);
        $componentValues = explode(',', $row['component_values']);

        // Initialize $allPossibleComponentIds
        $allPossibleComponentIds = range(0, count($componentGrades) - 1);

        // Initialize arrays for all possible component IDs with default values of 0
        $allComponentGrades = array_fill_keys($allPossibleComponentIds, 0);
        $allComponentValues = array_fill_keys($allPossibleComponentIds, 0);

        // Fill in actual grades and values at the correct component IDs
        foreach ($componentGrades as $key => $grade) {
            $allComponentGrades[$key] = $grade;
            $allComponentValues[$key] = $componentValues[$key];
        }

        // Calculate weighted grades for each component
        $weightedGrades = calculateWeightedGrades($allComponentGrades, $allComponentValues, $gradingSessionBase, $componentPercentage);

        // Save the data in the array
        $studentsData[$studentId][$gradingSessionId]['grading_session_percentage'] = $gradingSessionPercentage;
        $studentsData[$studentId][$gradingSessionId]['components'][$componentId][0] = $allComponentGrades;
        $studentsData[$studentId][$gradingSessionId]['components'][$componentId][1] = $allComponentValues;
        $studentsData[$studentId][$gradingSessionId]['components'][$componentId][2] = $weightedGrades;
        
    }

    foreach ($studentsData as $studentId => $data) {
        echo "Student ID: " . $studentId . "\n";
        foreach ($data as $gradingSessionId => $sessionData) {
            if (is_numeric($gradingSessionId)) {
                echo "\nGrading Session ID: " . $gradingSessionId . "\n";
                echo "Grading Session Percentage: " . $sessionData['grading_session_percentage'] . "%\n";
        
                foreach ($sessionData['components'] as $componentId => $componentData) {
                    echo "Component ID: " . $componentId . "\n";
                    echo $componentName . " Grades: {" . implode(',', $componentData[0]) . "}\n";
                    echo $componentName . " Component Values: {" . implode(',', $componentData[1]) . "}\n";
                    echo $componentName . " Weighted Grades: {" . round((float) $componentData[2], 2) . "}\n";
                }
        
                // Calculate and output the total weighted grade for the session
                $totalWeightedGrade = calculateTotalWeightedGrade($sessionData['components']);
                echo "Total Weighted Grade: " . $totalWeightedGrade . "\n";

                echo "Total Session Grade: " . $totalWeightedGrade * ($sessionData['grading_session_percentage'] / 100) . "\n";
            }
        }
        
        echo '<hr>';
    }
    
} else {
    echo "No records found";
}

// Close the connection
$con->close();

// Function to calculate weighted grades
function calculateWeightedGrades($grades, $values, $base, $componentPercentage) {
    $totalNormalizedGrades = 0;
    $totalComponentValues = 0;

    // Calculate the total sum of normalized grades and total sum of component values
    foreach ($grades as $key => $grade) {
        $totalNormalizedGrades += (int) $grade;
        $totalComponentValues += (int) $values[$key];
    }

    // Calculate the weighted grade using the total sum of normalized grades and component values
    $weightedGrade = (($totalNormalizedGrades / max(1, $totalComponentValues)) * (100 - $base) + $base) * ($componentPercentage / 100);

    return $weightedGrade;
}
// Function to calculate total weighted grade for the session
function calculateTotalWeightedGrade($sessionData) {
    $totalWeightedGrade = 0;

    foreach ($sessionData as $componentData) {
        // Check if index 2 exists in $componentData
        if (isset($componentData[2])) {
            $totalWeightedGrade += round((float) $componentData[2], 2);
        } else {
            echo "Debugging: Missing value at index 2\n";
            var_dump($componentData); // Output the componentData array for debugging
        }
    }
    

    return $totalWeightedGrade;
}


?>