<?php
// teacher_upload_marks.php
session_start();
require 'connection.php';

// 1) Only Teachers allowed:
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'teacher') {
    header("Location: login.php?role=teacher");
    exit();
}

// 2) (Optional) Fetch list of semesters for a dropdown. 
//    Adjust if your `semesters` table has different columns.
$semesters = [];
$stmt = $conn->prepare("SELECT id, name FROM semesters ORDER BY id");
$stmt->execute();
$stmt->bind_result($sem_id, $sem_name);
while ($stmt->fetch()) {
    $semesters[] = ['id' => $sem_id, 'name' => $sem_name];
}
$stmt->close();

// If there was a flash‐message in session, grab it:
$uploadSuccess = $_SESSION['upload_success'] ?? '';
$uploadError   = $_SESSION['upload_error']   ?? '';
unset($_SESSION['upload_success'], $_SESSION['upload_error']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Upload Student Marks</title>
    <style>
      body { font-family: Arial, sans-serif; }
      .container { max-width: 600px; margin: 40px auto; }
      .alert { padding: 10px; margin-bottom: 20px; border-radius: 4px; }
      .alert-success { background-color: #e0f6e9; color: #2d6a4f; }
      .alert-error   { background-color: #ffe5e5; color: #9d0208; }
      label, select, input[type="file"], button { display: block; width: 100%; margin-bottom: 12px; }
      button { padding: 10px; background: #1d3557; color: white; border: none; border-radius: 4px; }
      button:hover { background: #457b9d; cursor: pointer; }
    </style>
</head>
<body>
  <div class="container">
    <h2>Upload Marks for a Semester</h2>

    <!-- Show Success or Error -->
    <?php if ($uploadSuccess): ?>
      <div class="alert alert-success"><?= $uploadSuccess ?></div>
    <?php endif; ?>
    <?php if ($uploadError): ?>
      <div class="alert alert-error" style="white-space: pre-wrap;"><?= nl2br($uploadError) ?></div>
    <?php endif; ?>

    <form action="teacher_marks_processor.php" method="post" enctype="multipart/form-data">
      <!-- Semester dropdown -->
      <label for="semester">Select Semester:</label>
      <select name="semester" id="semester" required>
        <option value="" disabled selected>-- Choose Semester --</option>
        <?php foreach ($semesters as $sem): ?>
          <option value="<?= htmlspecialchars($sem['name'], ENT_QUOTES) ?>">
            <?= htmlspecialchars(strtoupper($sem['name'])) ?>
          </option>
        <?php endforeach; ?>
      </select>

      <!-- File input -->
      <label for="marks_file">Choose .xlsx / .xls / .csv file:</label>
      <input type="file" name="marks_file" id="marks_file" accept=".xlsx,.xls,.csv" required>

      <button type="submit">Upload & Process</button>
    </form>
  </div>
</body>
</html>
