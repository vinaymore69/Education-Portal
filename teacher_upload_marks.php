<?php
// teacher_upload_marks.php
session_start();
require 'connection.php';

// 1) Only allow teachers
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'teacher') {
    // If not logged in or not a teacher, send them to login
    header("Location: login.php?role=teacher");
    exit();
}

// 2) Retrieve any flash‐messages set by the processor
$successMsg = $_SESSION['upload_success'] ?? '';
$errorMsg   = $_SESSION['upload_error']   ?? '';
unset($_SESSION['upload_success'], $_SESSION['upload_error']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Teacher • Upload Semester Marks</title>
  <link rel="stylesheet" href="css/style.css" />
</head>
<body>
  <div style="max-width:600px; margin:50px auto;">
    <h2>Teacher Dashboard → Upload Semester Marks</h2>
    <p>
      Logged in as <strong><?= htmlspecialchars($_SESSION['user_id']) ?></strong> – 
      <a href="logout.php">Logout</a>
    </p>
    <hr>

    <?php if ($successMsg): ?>
      <p style="color:green;"><?= $successMsg ?></p>
    <?php endif; ?>

    <?php if ($errorMsg): ?>
      <p style="color:red;"><?= nl2br(htmlspecialchars($errorMsg)) ?></p>
    <?php endif; ?>

    <form 
      action="teacher_marks_processor.php" 
      method="post" 
      enctype="multipart/form-data"
    >
      <div style="margin-bottom:10px;">
        <label for="semester">Select Semester:</label><br>
        <select name="semester" id="semester" required>
          <option value="">-- Choose Semester --</option>
          <option value="sem1">Semester 1</option>
          <option value="sem2">Semester 2</option>
          <option value="sem3">Semester 3</option>
          <option value="sem4">Semester 4</option>
          <option value="sem5">Semester 5</option>
          <option value="sem6">Semester 6</option>
        </select>
      </div>

      <div style="margin-bottom:10px;">
        <label for="marks_file">Upload Excel/CSV File:</label><br>
        <input 
          type="file"
          name="marks_file"
          id="marks_file"
          accept=".xlsx,.xls,.csv"
          required
        >
      </div>

      <button type="submit">Upload Marks</button>
    </form>

    <hr>
    <p><strong>Important Notes:</strong></p>
    <ul>
      <li>The <code>first row</code> of your spreadsheet <strong>must</strong> be a header row. The first header‐cell must be <code>roll_number</code> (all lowercase, no spaces). All other header cells become <em>subject names</em> (all lowercase, no spaces; use underscores if needed).</li>
      <li>Example of a valid <code>Semester 1</code> header row:  
        <code>roll_number | math | physics | chemistry</code></li>
      <li>Example of a valid <code>Semester 2</code> header row:  
        <code>roll_number | data_structures | algorithms | dbms</code></li>
      <li>Each subsequent row corresponds to one student. The script looks up <code>students.roll_number</code> and inserts (or updates) their marks into the <code>marks</code> table.</li>
      <li>If a <code>roll_number</code> in a data row isn’t found in the <code>students</code> table, that row is skipped and an error is reported.</li>
      <li>Re‐uploading for the same <code>semester + subject</code> combination will <em>update</em> existing marks instead of creating duplicates.</li>
    </ul>
  </div>
</body>
</html>
