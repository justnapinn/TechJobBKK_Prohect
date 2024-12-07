<?php
require 'databaseConnect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_type = $_POST['user_type'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $birthday = $_POST['birthday'];
    $address = $_POST['address'];
    $subdistrict = $_POST['subdistrict'];
    $district = $_POST['district'];
    $province = $_POST['province'];
    $postal_code = $_POST['postal_code'];
    $user_email = $_POST['user_email'];
    $user_phone = $_POST['user_phone'];

    // Check date of birth
    $current_date = date('Y-m-d'); // Current date in format YYYY-MM-DD
    if ($birthday >= $current_date) {
        header("Location: register.php?error=Invalid+birthdate.+Birthdate+must+be+before+today.");
        exit();
    }

    // Check the email to see if it ends with @[alphabet].[alphabet]
    if (!preg_match('/^[a-zA-Z0-9._%+-]+@[a-zA-Z]+\.[a-zA-Z]+$/', $user_email)) {
        header("Location: register.php?error=Invalid+email.+Email+must+be+a+valid+email+address.");
        exit();
    }

    // Convert user_type from integer to enum
    if ($user_type == 1) {
        $user_type = 'applicant';
    } elseif ($user_type == 2) {
        $user_type = 'company';
        $last_name = 'Company';
        $birthday  = '2023-10-21';
    } else {
        $user_type = 'admin';
    }

    // check for duplicate data
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? OR user_email = ? OR user_phone = ? OR (first_name = ? AND last_name = ?)");
    $stmt->bind_param("sssss", $username, $user_email, $user_phone, $first_name, $last_name);
    $stmt->execute();
    $result = $stmt->get_result();
    $user_data = $result->fetch_assoc();

    if ($result->num_rows > 0) {
        echo "<script>
            alert('Username or Email has been used. Please try again.');
            window.location.href = 'register.php';
        </script>";
        exit();
    }

    $stmt->close(); 

    // save new row in database
    $stmt = $conn->prepare("INSERT INTO users (user_id, user_type, username, password, first_name, last_name, birthday, address, subdistrict, district,province, postal_code, user_email, user_phone) VALUES (UUID(), ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?)");
    $stmt->bind_param("sssssssssssss", $user_type, $username, $password, $first_name, $last_name, $birthday, $address, $subdistrict, $district,$province, $postal_code, $user_email, $user_phone);

    if ($stmt->execute()) {
        echo "<script>
            alert('Registration completed!');
            window.location.href = 'login.html';
        </script>";
        exit();
    } else {
        echo "<script>
            alert('Registration failed. Please try again.');
            window.location.href = 'register.php';
        </script>";
        exit();
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TechJobBKK - Registration</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script type="text/javascript"
            src="https://earthchie.github.io/jquery.Thailand.js/jquery.Thailand.js/dependencies/JQL.min.js"></script>
    <script type="text/javascript"
            src="https://earthchie.github.io/jquery.Thailand.js/jquery.Thailand.js/dependencies/typeahead.bundle.js"></script>
    <link rel="stylesheet"
          href="https://earthchie.github.io/jquery.Thailand.js/jquery.Thailand.js/dist/jquery.Thailand.min.css">
    <script type="text/javascript"
            src="https://earthchie.github.io/jquery.Thailand.js/jquery.Thailand.js/dist/jquery.Thailand.min.js"></script>
    <style>
        body {
            background-color: #f1d4a2;
        }
        .register-container {
            max-width: 600px;
            margin: 50px auto;
            background: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        }
        .register-container h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #343a40;
            font-weight: bold;
        }
        .register-container .form-control:focus {
            box-shadow: none;
            border-color: #007bff;
        }
        .register-container .btn {
            width: 100%;
            background-color: #007bff;
            color: #fff;
        }
        .register-container .btn:hover {
            background-color: #0056b3;
        }
        .register-container p {
            text-align: center;
            margin-top: 20px;
        }
        .register-container a {
            color: #007bff;
            text-decoration: none;
        }
        .register-container a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <h2>Register to TechJobBKK</h2>
        <form method="POST" action="register.php">
            <div class="mb-3">
                <label for="user_type" class="form-label">Type:</label>
                <select name="user_type" id="user_type" class="form-select" required>
                    <option value="1">Applicant</option>
                    <option value="2">Company</option>
                </select>
            </div>
        
            <div class="mb-3">
                <label for="username" class="form-label">Username:</label>
                <input type="text" name="username" id="username" class="form-control" placeholder="Username" required>
            </div>
        
            <div class="mb-3">
                <label for="password" class="form-label">Password:</label>
                <input type="password" name="password" id="password" class="form-control" placeholder="Password" required>
            </div>
        
            <!-- Applicant Fields  -->
            <div id="applicant-fields">
                <div class="mb-3">
                    <label for="first_name" class="form-label">First Name:</label>
                    <input type="text" name="first_name" id="first_name" class="form-control" placeholder="First Name" required>
                </div>
                <div class="mb-3">
                    <label for="last_name" class="form-label">Last Name:</label>
                    <input type="text" name="last_name" id="last_name" class="form-control" placeholder="Last Name" required>
                </div>
                <div class="mb-3">
                    <label for="birthday" class="form-label">Birthday:</label>
                    <input type="date" name="birthday" id="birthday" class="form-control" required>
                </div>
            </div>

        
            <!-- Company Field -->
            <div id="company-fields" class="d-none">
                <div class="mb-3">
                    <label for="first_name" class="form-label">Company Name:</label>
                    <input type="text" name="first_name" id="first_name" class="form-control" placeholder="Company Name" required>
                </div>
            </div>
        
            <!-- Others Fields -->
        
            <div class="mb-3">
                <label for="address" class="form-label">Address:</label>
                <input type="text" name="address" id="address" class="form-control" placeholder="Address" required>
            </div>
            
            <div class="mb-3">
                <label for="subdistrict" class="form-label">Subdistrict:</label>
                <input type="text" name="subdistrict" class="form-control" placeholder="Subdistrict" 
                id="district" value="<?php echo isset($user_data['subdistrict']) ? htmlspecialchars($user_data['subdistrict']) : ''; ?>"
                required>
            </div>

            <div class="mb-3">
                <label for="district" class="form-label">District:</label>
                <input type="text" name="district" class="form-control" placeholder="District" id="amphoe"
                value="<?php echo isset($user_data['district']) ? htmlspecialchars($user_data['district']) : ''; ?>" required>
            </div>

            <div class="mb-3">
                <label for="province" class="form-label">Province:</label>
                <input type="text" name="province" id="province" class="form-control" placeholder="Province" 
                value="<?php echo isset($user_data['province']) ? htmlspecialchars($user_data['province']) : ''; ?>" required>
            </div>

            <div class="mb-3">
                <label for="postal_code" class="form-label">Postal Code:</label>
                <input type="text" name="postal_code" id="zipcode" class="form-control" placeholder="Postal Code" 
                value="<?php echo isset($user_data['postal_code']) ? htmlspecialchars($user_data['postal_code']) : ''; ?>" required>
            </div>
            
            <div class="mb-3">
                <label for="user_email" class="form-label">Email:</label>
                <input type="email" name="user_email" id="user_email" class="form-control" placeholder="Email" required>
            </div>
            <div class="mb-3">
                <label for="user_phone" class="form-label">Phone:</label>
                <input type="text" name="user_phone" id="user_phone" class="form-control" placeholder="Phone" required>
            </div>
        
            <div class="d-grid">
                <button type="submit" class="btn">Register</button>
            </div>
            <p>Already have an account? <a href="login.html">Login here</a></p>
        </form>
        
    </div>

    <script>
        const userTypeField = document.getElementById('user_type');
        const applicantFields = document.getElementById('applicant-fields');
        const companyFields = document.getElementById('company-fields');

        // Event listener to toggle form fields based on user type
        userTypeField.addEventListener('change', function () {
            if (this.value === '1') {
                applicantFields.classList.remove('d-none');
                companyFields.classList.add('d-none');
                document.getElementById('first_name').required = true;
                document.getElementById('last_name').required = true;
            } else if (this.value === '2') {
                applicantFields.classList.add('d-none');
                companyFields.classList.remove('d-none');
                document.getElementById('first_name').required = true; // For company name
                document.getElementById('last_name').required = false;
            }
        });
    
        $.Thailand({
            $district: $('#district'), 
            $amphoe: $('#amphoe'), 
            $province: $('#province'), 
            $zipcode: $('#zipcode'), 
        });

    </script>

</body>
</html>

