// Back-end CRUD functions, >>Omar's job<<
<?php
require_once 'conn.php';

// Initialize variables
$name = $email = $phone = $office = $hours = $dob = $gender = $hire_date = $salary = $rank = $department = '';
$error = '';
$success = '';

// CRUD Operations
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Create Professor
    if (isset($_POST['add_professor'])) {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $office = $_POST['office'];
        $hours = $_POST['hours'];
        $dob = $_POST['dob'];
        $gender = $_POST['gender'];
        $hire_date = $_POST['hire_date'];
        $salary = $_POST['salary'];
        $rank = $_POST['rank'];
        $department = $_POST['department'];

        if (empty($name) || empty($email) || empty($department)) {
            $error = 'Name, email and department are required!';
        } else {
            $stmt = $conn->prepare("INSERT INTO professor (first_name, last_name, email, phone_number, office_location, office_hours, date_of_birth, gender, hire_date, salary, `rank`, department_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            
            // Split name into first and last
            $name_parts = explode(' ', $name, 2);
            $first_name = $name_parts[0];
            $last_name = isset($name_parts[1]) ? $name_parts[1] : '';
            
            $stmt->bind_param("sssssssssdss", $first_name, $last_name, $email, $phone, $office, $hours, $dob, $gender, $hire_date, $salary, $rank, $department);
            
            if ($stmt->execute()) {
                $success = 'Professor added successfully!';
                // Clear form
                $name = $email = $phone = $office = $hours = $dob = $gender = $hire_date = $salary = $rank = $department = '';
            } else {
                $error = 'Error adding professor: ' . $conn->error;
            }
            $stmt->close();
        }
    }

    // Update Professor
    if (isset($_POST['update_professor'])) {
        $id = $_POST['id'];
        $name = $_POST['name'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $office = $_POST['office'];
        $hours = $_POST['hours'];
        $dob = $_POST['dob'];
        $gender = $_POST['gender'];
        $hire_date = $_POST['hire_date'];
        $salary = $_POST['salary'];
        $rank = $_POST['rank'];
        $department = $_POST['department'];

        if (empty($name) || empty($email) || empty($department)) {
            $error = 'Name, email and department are required!';
        } else {
            // Split name into first and last
            $name_parts = explode(' ', $name, 2);
            $first_name = $name_parts[0];
            $last_name = isset($name_parts[1]) ? $name_parts[1] : '';
            
            $stmt = $conn->prepare("UPDATE professor SET first_name=?, last_name=?, email=?, phone_number=?, office_location=?, office_hours=?, date_of_birth=?, gender=?, hire_date=?, salary=?, `rank`=?, department_id=? WHERE professor_id=?");
            $stmt->bind_param("sssssssssdssi", $first_name, $last_name, $email, $phone, $office, $hours, $dob, $gender, $hire_date, $salary, $rank, $department, $id);
            
            if ($stmt->execute()) {
                $success = 'Professor updated successfully!';
            } else {
                $error = 'Error updating professor: ' . $conn->error;
            }
            $stmt->close();
        }
    }
}

// Delete Professor
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM professor WHERE professor_id=?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        $success = 'Professor deleted successfully!';
    } else {
        $error = 'Error deleting professor: ' . $conn->error;
    }
    $stmt->close();
}

// Get Professor for editing
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $stmt = $conn->prepare("SELECT * FROM professor WHERE professor_id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $professor = $result->fetch_assoc();
    $stmt->close();
    
    if ($professor) {
        $name = $professor['first_name'] . ' ' . $professor['last_name'];
        $email = $professor['email'];
        $phone = $professor['phone_number'];
        $office = $professor['office_location'];
        $hours = $professor['office_hours'];
        $dob = $professor['date_of_birth'];
        $gender = $professor['gender'];
        $hire_date = $professor['hire_date'];
        $salary = $professor['salary'];
        $rank = $professor['rank'];
        $department = $professor['department_id'];
    }
}

// Get all professors
$professors = [];
$stmt = $conn->prepare("SELECT p.*, d.department_name FROM professor p JOIN department d ON p.department_id = d.department_id ORDER BY p.last_name, p.first_name");
$stmt->execute();
$result = $stmt->get_result();
$professors = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Get all departments for dropdown
$departments = [];
$stmt = $conn->prepare("SELECT * FROM department ORDER BY department_name");
$stmt->execute();
$result = $stmt->get_result();
$departments = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>





// Front-end stuff, >>Amr's job<<

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Professor Management</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: #f5f5f5;
            color: #333;
            line-height: 1.6;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        header {
            background-color: #2c3e50;
            color: white;
            padding: 20px 0;
            margin-bottom: 30px;
        }
        
        header h1 {
            text-align: center;
        }
        
        .nav-links {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 20px;
        }
        
        .nav-links a {
            color: white;
            text-decoration: none;
            padding: 5px 10px;
            border-radius: 3px;
            transition: background-color 0.3s;
        }
        
        .nav-links a:hover {
            background-color: #34495e;
        }
        
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .form-container {
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        
        .form-group input, 
        .form-group select, 
        .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        
        .form-row {
            display: flex;
            gap: 15px;
        }
        
        .form-row .form-group {
            flex: 1;
        }
        
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            font-size: 16px;
        }
        
        .btn:hover {
            background-color: #2980b9;
        }
        
        .btn-danger {
            background-color: #e74c3c;
        }
        
        .btn-danger:hover {
            background-color: #c0392b;
        }
        
        .btn-success {
            background-color: #2ecc71;
        }
        
        .btn-success:hover {
            background-color: #27ae60;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: white;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        
        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        
        th {
            background-color: #2c3e50;
            color: white;
        }
        
        tr:hover {
            background-color: #f5f5f5;
        }
        
        .action-links a {
            color: #3498db;
            text-decoration: none;
            margin-right: 10px;
        }
        
        .action-links a:hover {
            text-decoration: underline;
        }
        
        .search-container {
            margin-bottom: 20px;
        }
        
        .search-container input {
            padding: 10px;
            width: 300px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        
        .salary {
            text-align: right;
        }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <h1>Professor Management System</h1>
            <div class="nav-links">
                <a href="index.html">Home</a>
                <a href="students.php">Students</a>
                <a href="professors.php">Professors</a>
            </div>
        </div>
    </header>
    
    <div class="container">
        <?php if ($error): ?>
            <div class="alert alert-error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>
        
        <div class="form-container">
            <h2><?php echo isset($_GET['edit']) ? 'Edit Professor' : 'Add New Professor'; ?></h2>
            <form method="POST" action="">
                <?php if (isset($_GET['edit'])): ?>
                    <input type="hidden" name="id" value="<?php echo $_GET['edit']; ?>">
                <?php endif; ?>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="name">Full Name</label>
                        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($name); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="phone">Phone</label>
                        <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($phone); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="office">Office Location</label>
                        <input type="text" id="office" name="office" value="<?php echo htmlspecialchars($office); ?>">
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="hours">Office Hours</label>
                        <input type="text" id="hours" name="hours" value="<?php echo htmlspecialchars($hours); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="dob">Date of Birth</label>
                        <input type="date" id="dob" name="dob" value="<?php echo htmlspecialchars($dob); ?>">
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="gender">Gender</label>
                        <select id="gender" name="gender">
                            <option value="Male" <?php echo ($gender == 'Male') ? 'selected' : ''; ?>>Male</option>
                            <option value="Female" <?php echo ($gender == 'Female') ? 'selected' : ''; ?>>Female</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="hire_date">Hire Date</label>
                        <input type="date" id="hire_date" name="hire_date" value="<?php echo htmlspecialchars($hire_date); ?>">
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="salary">Salary ($)</label>
                        <input type="number" id="salary" name="salary" step="0.01" value="<?php echo htmlspecialchars($salary); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="rank">Rank</label>
                        <select id="rank" name="rank">
                            <option value="Assistant" <?php echo ($rank == 'Assistant') ? 'selected' : ''; ?>>Assistant Professor</option>
                            <option value="Associate" <?php echo ($rank == 'Associate') ? 'selected' : ''; ?>>Associate Professor</option>
                            <option value="Full" <?php echo ($rank == 'Full') ? 'selected' : ''; ?>>Full Professor</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="department">Department</label>
                    <select id="department" name="department" required>
                        <option value="">Select Department</option>
                        <?php foreach ($departments as $dept): ?>
                            <option value="<?php echo $dept['department_id']; ?>" <?php echo ($department == $dept['department_id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($dept['department_name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <?php if (isset($_GET['edit'])): ?>
                        <button type="submit" name="update_professor" class="btn btn-success">Update Professor</button>
                        <a href="professors.php" class="btn">Cancel</a>
                    <?php else: ?>
                        <button type="submit" name="add_professor" class="btn">Add Professor</button>
                    <?php endif; ?>
                </div>
            </form>
        </div>
        
        <div class="search-container">
            <input type="text" id="search" placeholder="Search professors...">
        </div>
        
        <h2>Professor List</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Department</th>
                    <th>Rank</th>
                    <th class="salary">Salary</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($professors as $prof): ?>
                    <tr>
                        <td><?php echo $prof['professor_id']; ?></td>
                        <td><?php echo htmlspecialchars($prof['first_name'] . ' ' . $prof['last_name']); ?></td>
                        <td><?php echo htmlspecialchars($prof['email']); ?></td>
                        <td><?php echo htmlspecialchars($prof['department_name']); ?></td>
                        <td><?php echo htmlspecialchars($prof['rank']); ?></td>
                        <td class="salary">$<?php echo number_format($prof['salary'], 2); ?></td>
                        <td class="action-links">
                            <a href="professors.php?edit=<?php echo $prof['professor_id']; ?>">Edit</a>
                            <a href="professors.php?delete=<?php echo $prof['professor_id']; ?>" onclick="return confirm('Are you sure you want to delete this professor?')">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script>
        // Simple search functionality
        document.getElementById('search').addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const rows = document.querySelectorAll('tbody tr');
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        });
    </script>
</body>
</html>
