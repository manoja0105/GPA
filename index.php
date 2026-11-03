
<?php

// Backend: Grade Point System
$gradePoints = [
    "A+" => 4.0,
    "A"  => 4.0,
    "A-" => 3.7,
    "B+" => 3.3,
    "B"  => 3.0,
    "B-" => 2.7,
    "C+" => 2.3,
    "C"  => 2.0,
    "C-" => 1.7,
    "D"  => 1.3,
    "F"  => 0.0
];

$results = [];
$gpa = null;
$error = "";
$totalPoints = 0;
$totalSubjects = 0;

$subjects = $_POST["subject"] ?? [""];
$grades = $_POST["grade"] ?? [""];

// Calculate GPA
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (count($subjects) == 0 ||
        count($subjects) != count($grades)) {

        $error = "Please enter subject details.";

    } else {

        foreach ($subjects as $i => $subject) {

            $subject = trim($subject);
            $grade = strtoupper(trim($grades[$i]));

            if ($subject == "" ||
                !isset($gradePoints[$grade])) {

                $error = "Invalid subject name or grade.";
                break;
            }

            $point = $gradePoints[$grade];

            $totalPoints += $point;
            $totalSubjects++;

            $results[] = [
                "subject" => $subject,
                "grade" => $grade,
                "point" => $point
            ];
        }

        if ($error == "" && $totalSubjects > 0) {
            $gpa = $totalPoints / $totalSubjects;
        }
    }
}

?>

<!DOCTYPE html>
<html>
<head>

<title>GPA Calculator</title>

<style>

body {
    font-family: Arial;
    background: #eef2f7;
    padding: 30px;
}

.container {
    max-width: 800px;
    margin: auto;
    background: white;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

h1 {
    text-align: center;
    color: #2563eb;
}

h2 {
    color: #2563eb;
}

.subject-row {
    display: flex;
    gap: 10px;
    margin-bottom: 12px;
}

input {
    flex: 1;
    min-width: 0;
    padding: 12px;
    border: 1px solid #ccc;
    border-radius: 6px;
}

button {
    padding: 12px 20px;
    background: #2563eb;
    color: white;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    margin-top: 10px;
}

button:hover {
    background: #1d4ed8;
}

.calculate {
    width: 100%;
}

.result-sheet {
    margin-top: 35px;
    padding: 25px;
    border: 2px solid #2563eb;
    border-radius: 10px;
    background: #f8fafc;
}

.gpa-result {
    text-align: center;
    background: #dcfce7;
    padding: 20px;
    border-radius: 10px;
    margin-top: 20px;
}

.gpa-result h1 {
    color: green;
    font-size: 45px;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 15px;
}

th, td {
    padding: 12px;
    border: 1px solid #ddd;
    text-align: center;
}

th {
    background: #2563eb;
    color: white;
}

.error {
    color: red;
    padding: 10px;
    background: #fee2e2;
}

.grade-info {
    margin-top: 35px;
}

@media(max-width: 550px) {
    body {
        padding: 10px;
    }

    .container {
        padding: 15px;
    }
}

</style>

</head>

<body>

<div class="container">

    <h1>GPA Calculator</h1>

    <!-- SUBJECT ENTRY SHEET -->

    <h2>1. Enter Subject Details</h2>

    <form method="POST" action="">

        <div id="subject-list">

            <?php foreach ($subjects as $i => $subject): ?>

                <div class="subject-row">

                    <input
                        type="text"
                        name="subject[]"
                        placeholder="Subject Name"
                        value="<?php echo htmlspecialchars($subject); ?>"
                        required
                    >

                    <input
                        type="text"
                        name="grade[]"
                        placeholder="Grade (A+, A, B+)"
                        value="<?php echo htmlspecialchars($grades[$i] ?? ''); ?>"
                        required
                    >

                </div>

            <?php endforeach; ?>

        </div>

        <button type="button" onclick="addSubject()">
            + Add Subject
        </button>

        <button type="submit" class="calculate">
            Calculate GPA
        </button>

    </form>

    <?php if ($error != ""): ?>

        <p class="error">
            <?php echo htmlspecialchars($error); ?>
        </p>

    <?php endif; ?>


    <!-- RESULT SHEET BELOW CALCULATOR -->

    <?php if ($gpa !== null): ?>

        <div class="result-sheet">

            <h2 style="text-align:center">
                2. GPA Result Sheet
            </h2>

            <table>

                <tr>
                    <th>Subject</th>
                    <th>Grade</th>
                    <th>Grade Point</th>
                </tr>

                <?php foreach ($results as $row): ?>

                    <tr>

                        <td>
                            <?php echo htmlspecialchars($row["subject"]); ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($row["grade"]); ?>
                        </td>

                        <td>
                            <?php echo $row["point"]; ?>
                        </td>

                    </tr>

                <?php endforeach; ?>

            </table>

            <div class="gpa-result">

                <h2>Your Final GPA</h2>

                <h1>
                    <?php echo number_format($gpa, 2); ?>
                </h1>

                <p>
                    Total Subjects: <?php echo $totalSubjects; ?>
                </p>

            </div>

        </div>

    <?php endif; ?>


    <!-- GRADING SYSTEM -->

    <div class="grade-info">

        <h2>3. University Grading System</h2>

        <table>

            <tr>
                <th>Grade</th>
                <th>Marks</th>
                <th>Grade Point</th>
            </tr>

            <tr><td>A+</td><td>85 or above</td><td>4.0</td></tr>
            <tr><td>A</td><td>80 - 84</td><td>4.0</td></tr>
            <tr><td>A-</td><td>75 - 79</td><td>3.7</td></tr>
            <tr><td>B+</td><td>70 - 74</td><td>3.3</td></tr>
            <tr><td>B</td><td>65 - 69</td><td>3.0</td></tr>
            <tr><td>B-</td><td>60 - 64</td><td>2.7</td></tr>
            <tr><td>C+</td><td>50 - 59</td><td>2.3</td></tr>
            <tr><td>C</td><td>40 - 49</td><td>2.0</td></tr>
            <tr><td>C-</td><td>30 - 39</td><td>1.7</td></tr>
            <tr><td>D</td><td>20 - 29</td><td>1.3</td></tr>
            <tr><td>F</td><td>0 - 19</td><td>0.0</td></tr>

        </table>

    </div>

</div>


<script>

function addSubject() {

    let list = document.getElementById("subject-list");

    let row = document.createElement("div");

    row.className = "subject-row";

    row.innerHTML = `
        <input type="text"
               name="subject[]"
               placeholder="Subject Name"
               required>

        <input type="text"
               name="grade[]"
               placeholder="Grade (A+, A, B+)"
               required>
    `;

    list.appendChild(row);

}

</script>

</body>
</html>
