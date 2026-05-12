<?php
$servername = getenv('DB_HOST');
$username   = getenv('DB_USER');
$password   = getenv('DB_PASS');
$database   = getenv('DB_NAME');

$conn = mysqli_init();
mysqli_ssl_set($conn, NULL, NULL, '/etc/ssl/certs/ca-certificates.crt', NULL, NULL);
mysqli_real_connect($conn, $servername, $username, $password, $database, 3306, NULL, MYSQLI_CLIENT_SSL);

if (mysqli_connect_errno()) {
  die("Connection Error: " . mysqli_connect_error());
}

$conn->query("CREATE TABLE IF NOT EXISTS userdetails (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(150) NOT NULL,
  mobile VARCHAR(15) NOT NULL,
  password VARCHAR(255) NOT NULL
)");

$success = "";
$error = "";

if (isset($_POST["submit"])) {
  $name     = $_POST['name'];
  $email    = $_POST['email'];
  $mobile   = $_POST['mobile'];
  $password = $_POST['password'];

  if (empty($name) || empty($email) || empty($mobile) || empty($password)) {
    $error = "All fields are required!";
  } else {
    $sql = "INSERT INTO userdetails (name, email, mobile, password) VALUES ('$name', '$email', '$mobile', '$password')";
    if ($conn->query($sql)) {
      $success = "New record created successfully!";
    } else {
      $error = "Error: " . $conn->error;
    }
  }
}

$records = $conn->query("SELECT id, name, email, mobile FROM userdetails ORDER BY id DESC");
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>CRUD App using PHP MySQL</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body style="background-color:gainsboro;">
  <div class="container py-5 px-5">
    <div class="text-center py-3">
      <h2>CRUD OPERATIONS CI/CD</h2>
    </div>
    <?php if ($success): ?>
      <div class="alert alert-success"><?= $success ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
      <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>
    <form method="post">
      <div class="mb-3">
        <label class="form-label">Name</label>
        <input type="text" class="form-control" name="name" placeholder="Enter Your Name">
      </div>
      <div class="mb-3">
        <label class="form-label">Email address</label>
        <input type="email" class="form-control" name="email" placeholder="Enter Your Email">
      </div>
      <div class="mb-3">
        <label class="form-label">Mobile Number</label>
        <input type="number" class="form-control" name="mobile" placeholder="Enter Your Mobile Number">
      </div>
      <div class="mb-3">
        <label class="form-label">Password</label>
        <input type="password" class="form-control" name="password" placeholder="Enter Your Password">
      </div>
      <button type="submit" name="submit" class="btn btn-primary">Submit</button>
    </form>
    <div class="mt-5">
      <h4>Stored Records <span class="badge bg-secondary"><?= $records->num_rows ?></span></h4>
      <?php if ($records->num_rows > 0): ?>
        <table class="table table-striped table-bordered mt-3">
          <thead class="table-dark">
            <tr><th>#</th><th>Name</th><th>Email</th><th>Mobile</th></tr>
          </thead>
          <tbody>
            <?php while ($row = $records->fetch_assoc()): ?>
              <tr>
                <td><?= $row['id'] ?></td>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td><?= htmlspecialchars($row['email']) ?></td>
                <td><?= htmlspecialchars($row['mobile']) ?></td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      <?php else: ?>
        <p class="text-muted mt-3">No records yet. Add one above!</p>
      <?php endif; ?>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
