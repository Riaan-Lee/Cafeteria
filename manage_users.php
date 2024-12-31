<?php
session_start();
include('connectDB.php');

// Fetch Users with Search and Role Filters
$search = isset($_GET['search']) ? $_GET['search'] : '';
$role_filter = isset($_GET['role']) ? $_GET['role'] : '';

$query = "SELECT id, name, email, role FROM users WHERE 1=1";
if (!empty($search)) {
    $query .= " AND (name LIKE ? OR email LIKE ?)";
}
if (!empty($role_filter)) {
    $query .= " AND role = ?";
}

$stmt = $connect->prepare($query);
if (!empty($search) && !empty($role_filter)) {
    $search_term = "%$search%";
    $stmt->bind_param('sss', $search_term, $search_term, $role_filter);
} elseif (!empty($search)) {
    $search_term = "%$search%";
    $stmt->bind_param('ss', $search_term, $search_term);
} elseif (!empty($role_filter)) {
    $stmt->bind_param('s', $role_filter);
}
$stmt->execute();
$users = $stmt->get_result();

// Handle Create, Update, and Delete Operations
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'add') {
            // Add new user
            $hashed_password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $stmt = $connect->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
            $stmt->bind_param('ssss', $_POST['name'], $_POST['email'], $hashed_password, $_POST['role']);
            $stmt->execute();
        } elseif ($_POST['action'] === 'update') {
            // Update existing user
            $stmt = $connect->prepare("UPDATE users SET name = ?, email = ?, role = ? WHERE id = ?");
            $stmt->bind_param('sssi', $_POST['name'], $_POST['email'], $_POST['role'], $_POST['id']);
            $stmt->execute();
        }
    }
    header("Location: manage_users.php");
    exit();
}

// Handle Delete Operations
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['delete_id'])) {
    $stmt = $connect->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param('i', $_GET['delete_id']);
    $stmt->execute();
    header("Location: manage_users.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <link rel="stylesheet" href="../Css/admin.css">
</head>
<body>

    <!-- Navigation Bar -->
    <ul id="navigation">
        <li class="left"><a href="admin_summary.php">Dashboard</a></li>
        <li class="left"><a href="manage_users.php" class="active">Manage Users</a></li>
        <li class="right"><a href="logout.php">Logout</a></li>
    </ul>

    <!-- Search and Filter -->
    <div class="search-filter-container">
        <form method="GET" action="manage_users.php">
            <input type="text" name="search" placeholder="Search by name or email" value="<?php echo htmlspecialchars($search); ?>">
            <select name="role">
                <option value="">All Roles</option>
                <option value="admin" <?php if ($role_filter === 'admin') echo 'selected'; ?>>Admin</option>
                <option value="staff" <?php if ($role_filter === 'staff') echo 'selected'; ?>>Staff</option>
                <option value="customer" <?php if ($role_filter === 'customer') echo 'selected'; ?>>Customer</option>
            </select>
            <button type="submit" class="btn">Search</button>
        </form>
    </div>

    <!-- Add New User -->
    <div class="menu-form">
        <h2>Add New User</h2>
        <form method="POST" action="manage_users.php">
            <input type="hidden" name="action" value="add">
            <label>Name</label>
            <input type="text" name="name" required>
            <label>Email</label>
            <input type="email" name="email" required>
            <label>Password</label>
            <input type="password" name="password" required>
            <label>Role</label>
            <select name="role" required>
                <option value="admin">Admin</option>
                <option value="staff">Staff</option>
                <option value="customer">Customer</option>
            </select>
            <button type="submit" class="btn">Add User</button>
        </form>
    </div>

    <!-- Existing Users -->
    <div class="menu-container">
        <h2>Existing Users</h2>
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($user = $users->fetch_assoc()): ?>
                    <tr>
                        <form method="POST" action="manage_users.php">
                            <input type="hidden" name="action" value="update">
                            <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
                            <td><input type="text" name="name" value="<?php echo $user['name']; ?>" required></td>
                            <td><input type="email" name="email" value="<?php echo $user['email']; ?>" required></td>
                            <td>
                                <select name="role">
                                    <option value="admin" <?php echo $user['role'] === 'admin' ? 'selected' : ''; ?>>Admin</option>
                                    <option value="staff" <?php echo $user['role'] === 'staff' ? 'selected' : ''; ?>>Staff</option>
                                    <option value="customer" <?php echo $user['role'] === 'customer' ? 'selected' : ''; ?>>Customer</option>
                                </select>
                            </td>
                            <td>
                                <button type="submit" class="btn">Update</button>
                                <a href="manage_users.php?delete_id=<?php echo $user['id']; ?>" class="btn delete-btn">Delete</a>
                            </td>
                        </form>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

</body>
</html>
