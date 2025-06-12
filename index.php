<?php
    $server = 'db';
    $username = 'root';
    $password = 'rootpassword';
    $schema = 'StudentRecord';
    $pdo = new PDO('mysql:dbname=' . $schema . ';host=' . $server, $username, $password, 
        [ PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = $_POST['input_name'] ?? '';
        $course = $_POST['input_course'] ?? '';
        $subject1 = $_POST['input_subject_1'] ?? '';
        $subject2 = $_POST['input_subject_2'] ?? '';
        $subject3 = $_POST['input_subject_3'] ?? '';
        $subject4 = $_POST['input_subject_4'] ?? '';
        $year = (int)$_POST['input_year'] ?? 0;

        function randomGrade()
        {
            $grades = ["A", "B", "C", "D", "E", "F"];
            return $grades[array_rand($grades)];
        }

        $subjects = json_encode([
            $subject1 => randomGrade(),
            $subject2 => randomGrade(),
            $subject3 => randomGrade(),
            $subject4 => randomGrade(),
        ]);

        $stmt = $pdo->prepare("INSERT INTO Students (Name, Course, Subjects, `Year of Study`) VALUES (?, ?, ?, ?)");
        $stmt->execute([$name, $course, $subjects, $year]);
    }

    if ($_SERVER['REQUEST_METHOD'] === 'GET')
    {
        $name = $_GET['query_name'] ?? '';

        if ($name) {
            $stmt = $pdo->prepare("SELECT Name, Course, Subjects, `Year of Study` FROM Students WHERE Name = ?");
            $stmt->execute([$name]);
            $student = $stmt->fetch(PDO::FETCH_ASSOC);

            var_dump($student);
        }
    }
?>

<!DOCTYPE html>
<html>

    <header>
        <title>Student Record | Input Record</title>
        <script src=".js"></script>
        <link rel="stylesheet", href=".css">
    </header>

    <body>
        <h1 name="input_header">Student Registration</h1>
        <form method="post" action="index.php">
            <input type="text" name="input_name" placeholder="Name"/><br><br>
            <input type="text" name="input_course" placeholder="Course"/><br><br>
            <input type="text" name="input_subject_1" placeholder="Subject 1"/><br><br>
            <input type="text" name="input_subject_2" placeholder="Subject 2"/><br><br>
            <input type="text" name="input_subject_3" placeholder="Subject 3"/><br><br>
            <input type="text" name="input_subject_4" placeholder="Subject 4"/><br><br>
            <input type="text" name="input_year" placeholder="Year of Study"/><br><br>    
            <input type="submit" name="input_submit" value="Submit"/><br><br>
        </form>
    </body>

    <body>
        <form method="get" action="index.php">
            <h1 name="output_header">Student Query</h1>
            <input type="input_name" name="query_name" placeholder="Student Name"/><br><br>
            <input type="submit" name="query_submit" value="Query"/><br><br>

            <?php if ($student): ?>
                <p name="output_name">Student Name: <?echo $student["Name"]?></p>
                <p name="output_course">Course: <?echo $student["Course"]?></p>
                <p name="output_subject_1">Subjects: <?echo $student["Subjects"]?></p>
                <p name="output_year">Student Year: <?echo $student["Year of Study"]?></p>   
            <?php endif; ?>
            
        </form>
    </body>

</html>