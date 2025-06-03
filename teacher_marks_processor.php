<?php
// teacher_marks_processor.php
session_start();
require 'connection.php';
require 'vendor/autoload.php';  // PhpSpreadsheet must be installed via Composer

use PhpOffice\PhpSpreadsheet\IOFactory;

// 1) Only Teachers allowed
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'teacher') {
    header("Location: login.php?role=teacher");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: teacher_upload_marks.php");
    exit();
}

// 2) Validate the "semester" field
$semester_name = trim($_POST['semester'] ?? '');
$valid_sems    = ['sem1','sem2','sem3','sem4','sem5','sem6'];
if (!in_array($semester_name, $valid_sems, true)) {
    $_SESSION['upload_error'] = "Invalid semester selected.";
    header("Location: teacher_upload_marks.php");
    exit();
}

// 3) Lookup semester_id from `semesters` table
$stmt = $conn->prepare("SELECT id FROM semesters WHERE name = ? LIMIT 1");
$stmt->bind_param('s', $semester_name);
$stmt->execute();
$stmt->bind_result($semester_id);
$stmt->fetch();
$stmt->close();

if (!$semester_id) {
    $_SESSION['upload_error'] = "Selected semester not found in database.";
    header("Location: teacher_upload_marks.php");
    exit();
}

// 4) Validate that a file was uploaded without error
if (!isset($_FILES['marks_file']) || $_FILES['marks_file']['error'] !== UPLOAD_ERR_OK) {
    $_SESSION['upload_error'] = "File upload error. Please try again.";
    header("Location: teacher_upload_marks.php");
    exit();
}

$fileTmp  = $_FILES['marks_file']['tmp_name'];
$fileName = $_FILES['marks_file']['name'];
$ext      = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

if (!in_array($ext, ['xlsx','xls','csv'], true)) {
    $_SESSION['upload_error'] = "Invalid file type. Only .xlsx, .xls, or .csv allowed.";
    header("Location: teacher_upload_marks.php");
    exit();
}

// 5) Load the spreadsheet via PhpSpreadsheet
try {
    if ($ext === 'csv') {
        $reader = IOFactory::createReader('Csv');
    } else {
        $reader = IOFactory::createReaderForFile($fileTmp);
    }
    $spreadsheet = $reader->load($fileTmp);
    $sheet       = $spreadsheet->getActiveSheet();
    // toArray(null, true, true, true) returns rows as associative arrays keyed by column letter
    $rows = $sheet->toArray(null, true, true, true);
} catch (\PhpOffice\PhpSpreadsheet\Reader\Exception $e) {
    $_SESSION['upload_error'] = "Error reading spreadsheet: " . $e->getMessage();
    header("Location: teacher_upload_marks.php");
    exit();
}

// 6) Parse the header (row #1)
$headerRow = $rows[1] ?? [];
if (empty($headerRow)) {
    $_SESSION['upload_error'] = "Spreadsheet is empty or missing header row.";
    header("Location: teacher_upload_marks.php");
    exit();
}

// Normalize header names (lowercase, trimmed)
foreach ($headerRow as $colLetter => $colName) {
    $headerRow[$colLetter] = strtolower(trim((string)$colName));
}

// Build a map: columnName → columnLetter
$columns = [];
foreach ($headerRow as $colLetter => $colName) {
    if ($colName !== '') {
        $columns[$colName] = $colLetter;
    }
}

// Ensure first header is "roll_number"
if (!isset($columns['roll_number'])) {
    $_SESSION['upload_error'] = "Header missing required column: roll_number";
    header("Location: teacher_upload_marks.php");
    exit();
}

// All other column keys become subject names
$subjectNames = [];
foreach ($columns as $colName => $colLetter) {
    if ($colName === 'roll_number') continue;
    $subjectNames[] = $colName;
}
if (empty($subjectNames)) {
    $_SESSION['upload_error'] = "No subject columns found in header. You need at least one subject after roll_number.";
    header("Location: teacher_upload_marks.php");
    exit();
}

// 7) Iterate over data rows (row 2 through end)
$rowsProcessed = 0;
$errors        = [];

// PREPARE a statement to lookup students.id by matching students.roll_number:
$stmtLookupStudent = $conn->prepare("
    SELECT id
    FROM students
    WHERE roll_number = ?
    LIMIT 1
");

for ($rowNum = 2; $rowNum <= count($rows); $rowNum++) {
    $row = $rows[$rowNum];

    // 7a) Get roll_number from the corresponding column letter
    $rollNumber = trim((string)$row[$columns['roll_number']]);
    if ($rollNumber === '') {
        // Skip any row with blank roll_number
        continue;
    }

    // 7b) Look up that student's table ID via students.roll_number
    $stmtLookupStudent->bind_param('s', $rollNumber);
    $stmtLookupStudent->execute();
    $stmtLookupStudent->store_result();
    if ($stmtLookupStudent->num_rows === 0) {
        $errors[] = "Row $rowNum: Student with roll_number '$rollNumber' not found in students table.";
        continue;
    }
    $stmtLookupStudent->bind_result($student_table_id);
    $stmtLookupStudent->fetch();
    $stmtLookupStudent->free_result();

    // 7c) For each subject-column, read marks and upsert into `marks`
    foreach ($subjectNames as $subject) {
        $colLetter = $columns[$subject];
        $markValue = trim((string)$row[$colLetter]);

        if ($markValue === '' || !is_numeric($markValue)) {
            // If blank or not numeric, skip it
            continue;
        }
        $markInt = (int)$markValue;

        // Check if a row already exists for (student_id, semester_id, subject)
        $stmtCheck = $conn->prepare("
            SELECT id
            FROM marks
            WHERE student_id = ?
              AND semester_id = ?
              AND subject = ?
            LIMIT 1
        ");
        $stmtCheck->bind_param('iis', $student_table_id, $semester_id, $subject);
        $stmtCheck->execute();
        $stmtCheck->store_result();

        if ($stmtCheck->num_rows > 0) {
            // UPDATE existing
            $stmtCheck->bind_result($existing_id);
            $stmtCheck->fetch();
            $stmtCheck->close();

            $stmtUpd = $conn->prepare("
                UPDATE marks 
                SET marks_obtained = ?
                WHERE id = ?
            ");
            $stmtUpd->bind_param('ii', $markInt, $existing_id);
            $stmtUpd->execute();
            $stmtUpd->close();
        } else {
            // INSERT new record
            $stmtCheck->close();
            $stmtIns = $conn->prepare("
                INSERT INTO marks
                  (student_id, semester_id, subject, marks_obtained)
                VALUES (?, ?, ?, ?)
            ");
            $stmtIns->bind_param('iisi', $student_table_id, $semester_id, $subject, $markInt);
            $stmtIns->execute();
            $stmtIns->close();
        }
    }

    $rowsProcessed++;
}
$stmtLookupStudent->close();

// 8) Store flash messages & redirect back
if (empty($errors)) {
    $_SESSION['upload_success'] = "Successfully processed $rowsProcessed row(s) for <strong>$semester_name</strong>.";
} else {
    $errorText = "Processed $rowsProcessed row(s), but encountered errors:\n" . implode("\n", $errors);
    $_SESSION['upload_error'] = $errorText;
}

header("Location: teacher_upload_marks.php");
exit();
