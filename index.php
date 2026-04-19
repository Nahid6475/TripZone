<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit();
}

// Database connection
$conn = new mysqli('localhost', 'root', '', 'tripzone_crud_db');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get statistics
$users_count = $conn->query("SELECT COUNT(*) as count FROM users")->fetch_assoc()['count'];
$bookings_count = $conn->query("SELECT COUNT(*) as count FROM bookings")->fetch_assoc()['count'];
$messages_count = $conn->query("SELECT COUNT(*) as count FROM messages")->fetch_assoc()['count'];
$notes_count = $conn->query("SELECT COUNT(*) as count FROM user_notes")->fetch_assoc()['count'];

// Handle delete actions
if (isset($_GET['delete_user'])) {
    $id = (int)$_GET['delete_user'];
    $conn->query("DELETE FROM users WHERE id = $id");
    header("Location: index.php?tab=users");
    exit();
}

if (isset($_GET['delete_booking'])) {
    $id = (int)$_GET['delete_booking'];
    $conn->query("DELETE FROM bookings WHERE id = $id");
    header("Location: index.php?tab=bookings");
    exit();
}

if (isset($_GET['delete_message'])) {
    $id = (int)$_GET['delete_message'];
    $conn->query("DELETE FROM messages WHERE id = $id");
    header("Location: index.php?tab=messages");
    exit();
}

if (isset($_GET['delete_note'])) {
    $id = (int)$_GET['delete_note'];
    $conn->query("DELETE FROM user_notes WHERE id = $id");
    header("Location: index.php?tab=notes");
    exit();
}

// Get current tab
$active_tab = $_GET['tab'] ?? 'dashboard';

// Fetch data based on tab
$users = [];
$bookings = [];
$messages = [];
$notes = [];

if ($active_tab == 'users') {
    $result = $conn->query("SELECT * FROM users ORDER BY id DESC");
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
} elseif ($active_tab == 'bookings') {
    $result = $conn->query("SELECT b.*, u.name as user_name FROM bookings b LEFT JOIN users u ON b.user_id = u.id ORDER BY b.id DESC");
    while ($row = $result->fetch_assoc()) {
        $bookings[] = $row;
    }
} elseif ($active_tab == 'messages') {
    $result = $conn->query("SELECT * FROM messages ORDER BY id DESC");
    while ($row = $result->fetch_assoc()) {
        $messages[] = $row;
    }
} elseif ($active_tab == 'notes') {
    $result = $conn->query("SELECT n.*, u.name as user_name FROM user_notes n LEFT JOIN users u ON n.user_id = u.id ORDER BY n.id DESC");
    while ($row = $result->fetch_assoc()) {
        $notes[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - TripZone</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #f0f2f5;
        }
        
        /* Sidebar */
        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            width: 280px;
            height: 100%;
            background: linear-gradient(180deg, #0F4C5C, #1a6b5e);
            color: white;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
            z-index: 100;
        }
        
        .sidebar-header {
            padding: 25px 20px;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        
        .sidebar-header h2 {
            font-size: 1.5rem;
            margin-bottom: 5px;
        }
        
        .sidebar-header p {
            font-size: 0.75rem;
            opacity: 0.8;
        }
        
        .sidebar-menu {
            padding: 20px 0;
        }
        
        .sidebar-menu a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 25px;
            color: rgba(255,255,255,0.85);
            text-decoration: none;
            transition: all 0.3s;
        }
        
        .sidebar-menu a:hover {
            background: rgba(255,255,255,0.1);
            color: white;
        }
        
        .sidebar-menu a.active {
            background: rgba(255,255,255,0.15);
            border-left: 3px solid #E76F51;
            color: white;
        }
        
        .sidebar-menu i {
            width: 25px;
            font-size: 1.1rem;
        }
        
        .logout-btn {
            position: absolute;
            bottom: 30px;
            left: 0;
            right: 0;
            margin: 0 20px;
            background: rgba(255,255,255,0.1);
            border-radius: 10px;
        }
        
        /* Main Content */
        .main-content {
            margin-left: 280px;
            padding: 20px 30px;
        }
        
        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }
        
        .top-bar h1 {
            font-size: 1.8rem;
            color: #0F4C5C;
        }
        
        .admin-info {
            background: white;
            padding: 8px 20px;
            border-radius: 50px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }
        
        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: white;
            border-radius: 20px;
            padding: 20px;
            display: flex;
            align-items: center;
            gap: 15px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            transition: transform 0.3s;
        }
        
        .stat-card:hover {
            transform: translateY(-3px);
        }
        
        .stat-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #0F4C5C, #2A9D8F);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
        }
        
        .stat-info h3 {
            font-size: 1.8rem;
            font-weight: 800;
            color: #0F4C5C;
        }
        
        .stat-info p {
            color: #5A6E66;
            font-size: 0.85rem;
        }
        
        /* Tables */
        .data-table {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        
        .data-table table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .data-table th {
            background: #0F4C5C;
            color: white;
            padding: 15px;
            text-align: left;
        }
        
        .data-table td {
            padding: 12px 15px;
            border-bottom: 1px solid #eee;
        }
        
        .data-table tr:hover {
            background: #f8f9fa;
        }
        
        .delete-btn {
            background: #E76F51;
            color: white;
            border: none;
            padding: 5px 12px;
            border-radius: 20px;
            cursor: pointer;
            font-size: 0.75rem;
            transition: 0.2s;
        }
        
        .delete-btn:hover {
            background: #cf5a3c;
        }
        
        .badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 600;
        }
        
        .badge-pending { background: #FFF3EA; color: #E76F51; }
        .badge-confirmed { background: #d4edda; color: #155724; }
        
        .empty-row td {
            text-align: center;
            padding: 40px;
            color: #999;
        }
        
        @media (max-width: 768px) {
            .sidebar {
                width: 70px;
            }
            .sidebar-header h2, .sidebar-header p, .sidebar-menu span {
                display: none;
            }
            .sidebar-menu a {
                justify-content: center;
                padding: 15px;
            }
            .main-content {
                margin-left: 70px;
            }
            .stats-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <h2><i class="fas fa-crown"></i> TripZone</h2>
            <p>Admin Panel</p>
        </div>
        <div class="sidebar-menu">
            <a href="?tab=dashboard" class="<?php echo $active_tab == 'dashboard' ? 'active' : ''; ?>">
                <i class="fas fa-tachometer-alt"></i> <span>Dashboard</span>
            </a>
            <a href="?tab=users" class="<?php echo $active_tab == 'users' ? 'active' : ''; ?>">
                <i class="fas fa-users"></i> <span>Users</span>
            </a>
            <a href="?tab=bookings" class="<?php echo $active_tab == 'bookings' ? 'active' : ''; ?>">
                <i class="fas fa-calendar-check"></i> <span>Bookings</span>
            </a>
            <a href="?tab=messages" class="<?php echo $active_tab == 'messages' ? 'active' : ''; ?>">
                <i class="fas fa-envelope"></i> <span>Messages</span>
            </a>
            <a href="?tab=notes" class="<?php echo $active_tab == 'notes' ? 'active' : ''; ?>">
                <i class="fas fa-sticky-note"></i> <span>User Notes</span>
            </a>
        </div>
        <div class="logout-btn">
            <a href="admin_logout.php" style="color: white; text-decoration: none; display: block; text-align: center; padding: 12px;">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </div>
    </div>
    
    <!-- Main Content -->
    <div class="main-content">
        <div class="top-bar">
            <h1><i class="fas fa-chart-line"></i> 
                <?php 
                    if($active_tab == 'dashboard') echo 'Dashboard';
                    elseif($active_tab == 'users') echo 'Manage Users';
                    elseif($active_tab == 'bookings') echo 'Manage Bookings';
                    elseif($active_tab == 'messages') echo 'Manage Messages';
                    elseif($active_tab == 'notes') echo 'Manage User Notes';
                ?>
            </h1>
            <div class="admin-info">
                <i class="fas fa-user-shield"></i> <?php echo $_SESSION['admin_username']; ?>
            </div>
        </div>
        
        <?php if($active_tab == 'dashboard'): ?>
            <!-- Stats Cards -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon"><i class="fas fa-users"></i></div>
                    <div class="stat-info">
                        <h3><?php echo $users_count; ?></h3>
                        <p>Total Users</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon"><i class="fas fa-calendar-check"></i></div>
                    <div class="stat-info">
                        <h3><?php echo $bookings_count; ?></h3>
                        <p>Total Bookings</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon"><i class="fas fa-envelope"></i></div>
                    <div class="stat-info">
                        <h3><?php echo $messages_count; ?></h3>
                        <p>Total Messages</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon"><i class="fas fa-sticky-note"></i></div>
                    <div class="stat-info">
                        <h3><?php echo $notes_count; ?></h3>
                        <p>User Notes</p>
                    </div>
                </div>
            </div>
            
            <!-- Recent Activity -->
            <div class="data-table">
                <table>
                    <thead>
                        <tr>
                            <th colspan="2">Recent System Activity</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><i class="fas fa-users"></i> Total Registered Users</td>
                            <td><strong><?php echo $users_count; ?></strong> users</td>
                        </tr>
                        <tr>
                            <td><i class="fas fa-calendar-check"></i> Total Bookings</td>
                            <td><strong><?php echo $bookings_count; ?></strong> bookings</td>
                        </tr>
                        <tr>
                            <td><i class="fas fa-envelope"></i> Total Messages</td>
                            <td><strong><?php echo $messages_count; ?></strong> messages</td>
                        </tr>
                        <tr>
                            <td><i class="fas fa-sticky-note"></i> Total Notes</td>
                            <td><strong><?php echo $notes_count; ?></strong> notes</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        <?php elseif($active_tab == 'users'): ?>
            <div class="data-table">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Registered On</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(count($users) > 0): ?>
                            <?php foreach($users as $user): ?>
                            <tr>
                                <td><?php echo $user['id']; ?></td>
                                <td><?php echo htmlspecialchars($user['name']); ?></td>
                                <td><?php echo htmlspecialchars($user['email']); ?></td>
                                <td><?php echo date('d M Y', strtotime($user['created_at'])); ?></td>
                                <td>
                                    <a href="?tab=users&delete_user=<?php echo $user['id']; ?>" onclick="return confirm('Delete this user?')" class="delete-btn">Delete</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr class="empty-row"><td colspan="5">No users found</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        <?php elseif($active_tab == 'bookings'): ?>
            <div class="data-table">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Package</th>
                            <th>Customer</th>
                            <th>Travel Date</th>
                            <th>People</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(count($bookings) > 0): ?>
                            <?php foreach($bookings as $booking): ?>
                            <tr>
                                <td><?php echo $booking['id']; ?></td>
                                <td><?php echo htmlspecialchars($booking['package_name']); ?></td>
                                <td><?php echo htmlspecialchars($booking['customer_name']); ?></td>
                                <td><?php echo date('d M Y', strtotime($booking['travel_date'])); ?></td>
                                <td><?php echo $booking['number_of_people']; ?></td>
                                <td><span class="badge badge-<?php echo $booking['status']; ?>"><?php echo $booking['status']; ?></span></td>
                                <td>
                                    <a href="?tab=bookings&delete_booking=<?php echo $booking['id']; ?>" onclick="return confirm('Delete this booking?')" class="delete-btn">Delete</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr class="empty-row"><td colspan="7">No bookings found</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        <?php elseif($active_tab == 'messages'): ?>
            <div class="data-table">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Subject</th>
                            <th>Message</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(count($messages) > 0): ?>
                            <?php foreach($messages as $msg): ?>
                            <tr>
                                <td><?php echo $msg['id']; ?></td>
                                <td><?php echo htmlspecialchars($msg['name']); ?></td>
                                <td><?php echo htmlspecialchars($msg['email']); ?></td>
                                <td><?php echo htmlspecialchars($msg['subject']); ?></td>
                                <td><?php echo substr(htmlspecialchars($msg['message']), 0, 50); ?>...</td>
                                <td><?php echo date('d M Y', strtotime($msg['created_at'])); ?></td>
                                <td>
                                    <a href="?tab=messages&delete_message=<?php echo $msg['id']; ?>" onclick="return confirm('Delete this message?')" class="delete-btn">Delete</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr class="empty-row"><td colspan="7">No messages found</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        <?php elseif($active_tab == 'notes'): ?>
            <div class="data-table">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>User</th>
                            <th>Note Content</th>
                            <th>Created</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(count($notes) > 0): ?>
                            <?php foreach($notes as $note): ?>
                            <tr>
                                <td><?php echo $note['id']; ?></td>
                                <td><?php echo htmlspecialchars($note['user_name'] ?? 'Unknown'); ?></td>
                                <td><?php echo substr(htmlspecialchars($note['content']), 0, 60); ?>...</td>
                                <td><?php echo date('d M Y', strtotime($note['created_at'])); ?></td>
                                <td>
                                    <a href="?tab=notes&delete_note=<?php echo $note['id']; ?>" onclick="return confirm('Delete this note?')" class="delete-btn">Delete</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr class="empty-row"><td colspan="5">No notes found</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
<?php $conn->close(); ?>