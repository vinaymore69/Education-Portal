
        <?php
        header('Content-Type: application/json');
        
        // Database connection
        $pdo = new PDO('mysql:host=localhost;dbname=education_platform', $username, $password);
        
        // Fetch statistics
        $stats = [
            'averageScore' => $pdo->query("SELECT AVG(score) FROM student_scores WHERE semester = 'current'")->fetchColumn(),
            'newEnrollments' => $pdo->query("SELECT COUNT(*) FROM enrollments WHERE created_at >= DATE_SUB(NOW(), INTERVAL 1 MONTH)")->fetchColumn(),
            'completedCourses' => $pdo->query("SELECT COUNT(*) FROM course_completions WHERE completed_at >= DATE_SUB(NOW(), INTERVAL 1 MONTH)")->fetchColumn(),
            'activeStudents' => $pdo->query("SELECT COUNT(DISTINCT student_id) FROM student_activity WHERE last_activity >= DATE_SUB(NOW(), INTERVAL 1 WEEK)")->fetchColumn()
        ];
        
        echo json_encode($stats);
        ?>
        
        // activities.php
        <?php
        header('Content-Type: application/json');
        
        $pdo = new PDO('mysql:host=localhost;dbname=education_platform', $username, $password);
        
        $activities = $pdo->query("
            SELECT s.name, a.action, a.subject, a.created_at 
            FROM activities a 
            JOIN students s ON a.student_id = s.id 
            ORDER BY a.created_at DESC 
            LIMIT 10
        ")->fetchAll(PDO::FETCH_ASSOC);
        
        echo json_encode($activities);
        ?>
        */