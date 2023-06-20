<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <div class="container">
        <h2>Register</h2>
        <form action="register_process.php" method="post">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" name="username" id="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" name="password" id="password" required>
            </div>
            <div class="form-group">
                <label for="gmail">Gmail:</label>
                <input type="email" name="gmail" id="gmail" required>
            </div>
            <div class="form-group">
                <label for="alamat">Alamat:</label>
                <input type="text" name="alamat" id="alamat" required>
            </div>
            <div class="form-group">
                <label for="no_tlp">Nomor Telepon:</label>
                <input type="tel" name="no_tlp" id="no_tlp" required>
            </div>
            <div class="form-group">
                <label for="role">Role:</label>
                <select name="role" id="role">
                    <option value="admin">Admin</option>
                    <option value="user">User</option>
                </select>
            </div>
            <div class="form-group">
                <button type="submit" name="register">Register</button>
            </div>
        </form>
        <p>Already have an account? <a href="index.php">Login Here</a></p>
    </div>
</body>
</html>
