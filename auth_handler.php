<?php
/**
 * Authentication & Admin Handler
 * 
 * Processes AJAX requests from the frontend for:
 *   - signup:                         Register a new user
 *   - login:                          Authenticate an existing user (regular or admin)
 *   - forgot_password:                Generate a reset token
 *   - reset_password:                 Submit new password for approval
 *   - request_password_change:        Logged-in user password change request
 *   - admin_get_dashboard_data:       Fetch dashboard metrics, requests, activities, notifications
 *   - admin_handle_password_request:  Approve or reject a user's password change request with feedback
 *   - admin_mark_notifications_read:  Clear unread notification count
 *   - logout:                         Destroy session
 */

session_start();
header('Content-Type: application/json');

require_once 'db_setup.php';

// ── Helper to format relative time ─────────────────────────────
function formatRelativeTime($datetimeStr) {
    if (!$datetimeStr) return 'Just now';
    $time = strtotime($datetimeStr);
    $diff = time() - $time;
    if ($diff < 60) return 'Just now';
    if ($diff < 3600) return floor($diff / 60) . ' min ago';
    if ($diff < 86400) return floor($diff / 3600) . ' hr ago';
    if ($diff < 604800) return floor($diff / 86400) . ' days ago';
    return date('M j, Y', $time);
}

// ── Read JSON request body (with fallback to $_POST) ────────────
$rawInput = file_get_contents('php://input');
$input = json_decode($rawInput, true);
if (!is_array($input)) {
    $input = $_POST;
}
$action = $input['action'] ?? '';

switch ($action) {

    // ═══════════════════════════════════════════════════════════
    // SIGN UP
    // ═══════════════════════════════════════════════════════════
    case 'signup':
        $username = trim($input['username'] ?? '');
        $email    = trim($input['email'] ?? '');
        $password = $input['password'] ?? '';

        if (empty($username) || empty($email) || empty($password)) {
            echo json_encode(['success' => false, 'message' => 'All fields are required.']);
            exit;
        }

        if (strlen($password) < 8) {
            echo json_encode(['success' => false, 'message' => 'Password must be at least 8 characters.']);
            exit;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode(['success' => false, 'message' => 'Please enter a valid email address.']);
            exit;
        }

        // Check if email or username already exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ? OR username = ?");
        $stmt->bind_param("ss", $email, $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo json_encode(['success' => false, 'message' => 'Email or username already exists.']);
            $stmt->close();
            exit;
        }
        $stmt->close();

        // Insert user
        $role = 'user';
        $stmt = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $username, $email, $password, $role);

        if ($stmt->execute()) {
            $_SESSION['user_id']  = $stmt->insert_id;
            $_SESSION['username'] = $username;
            $_SESSION['email']    = $email;
            $_SESSION['role']     = 'user';

            // Log activity
            $logStmt = $conn->prepare("INSERT INTO activity_logs (activity, user_identifier, type) VALUES ('New user registered', ?, 'registration')");
            $logStmt->bind_param("s", $email);
            $logStmt->execute();
            $logStmt->close();

            echo json_encode([
                'success'  => true,
                'message'  => 'Account created successfully!',
                'username' => $username,
                'role'     => 'user',
                'redirect' => 'home.php'
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Registration failed. Please try again.']);
        }
        $stmt->close();
        break;

    // ═══════════════════════════════════════════════════════════
    // LOG IN (Supports User and Admin)
    // ═══════════════════════════════════════════════════════════
    case 'login':
        $email    = trim($input['email'] ?? '');
        $password = $input['password'] ?? '';

        if (empty($email) || empty($password)) {
            echo json_encode(['success' => false, 'message' => 'Email and password are required.']);
            exit;
        }

        $stmt = $conn->prepare("SELECT id, username, email, password, role FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            echo json_encode(['success' => false, 'message' => 'No account found with that email.']);
            $stmt->close();
            exit;
        }

        $user = $result->fetch_assoc();
        $stmt->close();

        // Check password (supports plain text, with fallback for existing hashes)
        $isMatch = ($password === $user['password']) || password_verify($password, $user['password']);
        if (!$isMatch) {
            // Check if there is a pending password request for this user to give helpful feedback
            $chkReq = $conn->prepare("SELECT status, admin_feedback FROM password_requests WHERE user_id = ? ORDER BY id DESC LIMIT 1");
            $chkReq->bind_param("i", $user['id']);
            $chkReq->execute();
            $reqRes = $chkReq->get_result();
            if ($reqRes->num_rows > 0) {
                $reqRow = $reqRes->fetch_assoc();
                if ($reqRow['status'] === 'pending') {
                    echo json_encode([
                        'success' => false,
                        'message' => 'Incorrect password. Note: Your recent password change is currently pending administrator approval.'
                    ]);
                    $chkReq->close();
                    exit;
                } elseif ($reqRow['status'] === 'rejected') {
                    $fb = !empty($reqRow['admin_feedback']) ? " (Feedback: " . $reqRow['admin_feedback'] . ")" : "";
                    echo json_encode([
                        'success' => false,
                        'message' => 'Incorrect password. Your recent password change was rejected by admin' . $fb . '. Please use your original password.'
                    ]);
                    $chkReq->close();
                    exit;
                }
            }
            $chkReq->close();

            echo json_encode(['success' => false, 'message' => 'Incorrect password. Please try again.']);
            exit;
        }

        // Set session
        $role = $user['role'] ?? 'user';
        $_SESSION['user_id']  = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['email']    = $user['email'];
        $_SESSION['role']     = $role;

        $redirect = ($role === 'admin') ? 'admin_dashboard.php' : 'home.php';

        echo json_encode([
            'success'  => true,
            'message'  => $role === 'admin' ? 'Welcome back, Admin!' : 'Login successful!',
            'username' => $user['username'],
            'role'     => $role,
            'redirect' => $redirect
        ]);
        break;

    // ═══════════════════════════════════════════════════════════
    // FORGOT PASSWORD — generate reset token
    // ═══════════════════════════════════════════════════════════
    case 'forgot_password':
        $email = trim($input['email'] ?? '');

        if (empty($email)) {
            echo json_encode(['success' => false, 'message' => 'Email is required.']);
            exit;
        }

        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            echo json_encode(['success' => false, 'message' => 'No account found with that email address.']);
            $stmt->close();
            exit;
        }
        $stmt->close();

        $token = bin2hex(random_bytes(32));

        $stmt = $conn->prepare("UPDATE users SET reset_token = ?, reset_expires = DATE_ADD(NOW(), INTERVAL 1 HOUR) WHERE email = ?");
        $stmt->bind_param("ss", $token, $email);
        $stmt->execute();
        $stmt->close();

        echo json_encode([
            'success' => true,
            'message' => 'If that email exists, a reset link has been sent.',
            'token'   => $token
        ]);
        break;

    // ═══════════════════════════════════════════════════════════
    // RESET PASSWORD — submit for Admin Approval
    // ═══════════════════════════════════════════════════════════
    case 'reset_password':
        $token           = $input['token'] ?? '';
        $newPassword     = $input['new_password'] ?? '';
        $confirmPassword = $input['confirm_password'] ?? '';

        if (empty($token) || empty($newPassword) || empty($confirmPassword)) {
            echo json_encode(['success' => false, 'message' => 'All fields are required.']);
            exit;
        }

        if ($newPassword !== $confirmPassword) {
            echo json_encode(['success' => false, 'message' => 'Passwords do not match.']);
            exit;
        }

        if (strlen($newPassword) < 8) {
            echo json_encode(['success' => false, 'message' => 'Password must be at least 8 characters.']);
            exit;
        }

        // Validate token and check expiration
        $stmt = $conn->prepare("SELECT id, username, email FROM users WHERE reset_token = ? AND reset_expires > NOW()");
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            echo json_encode(['success' => false, 'message' => 'Invalid or expired reset token. Please request a new one.']);
            $stmt->close();
            exit;
        }

        $user = $result->fetch_assoc();
        $stmt->close();

        // Clear token
        $clearStmt = $conn->prepare("UPDATE users SET reset_token = NULL, reset_expires = NULL WHERE id = ?");
        $clearStmt->bind_param("i", $user['id']);
        $clearStmt->execute();
        $clearStmt->close();

        // Insert into password_requests with status 'pending'
        $reqStmt = $conn->prepare("INSERT INTO password_requests (user_id, username, email, new_password, status) VALUES (?, ?, ?, ?, 'pending')");
        $reqStmt->bind_param("isss", $user['id'], $user['username'], $user['email'], $newPassword);
        $reqStmt->execute();
        $reqId = $conn->insert_id;
        $reqStmt->close();

        // Notify Admin
        $notifTitle = "Password Change Request";
        $notifMsg = "{$user['username']} ({$user['email']}) submitted a password change request.";
        $notifStmt = $conn->prepare("INSERT INTO admin_notifications (title, message, type, is_read, reference_id) VALUES (?, ?, 'password_change', 0, ?)");
        $notifStmt->bind_param("ssi", $notifTitle, $notifMsg, $reqId);
        $notifStmt->execute();
        $notifStmt->close();

        // Log Activity
        $actMsg = "Password change requested";
        $actStmt = $conn->prepare("INSERT INTO activity_logs (activity, user_identifier, type) VALUES (?, ?, 'password_request')");
        $actStmt->bind_param("ss", $actMsg, $user['username']);
        $actStmt->execute();
        $actStmt->close();

        echo json_encode([
            'success' => true,
            'message' => 'Password reset request submitted! An administrator will review and approve your request shortly.'
        ]);
        break;

    // ═══════════════════════════════════════════════════════════
    // USER CHANGE PASSWORD (from home.php)
    // ═══════════════════════════════════════════════════════════
    case 'request_password_change':
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['success' => false, 'message' => 'You must be logged in to change your password.']);
            exit;
        }

        $userId          = $_SESSION['user_id'];
        $currentPassword = $input['current_password'] ?? '';
        $newPassword     = $input['new_password'] ?? '';
        $confirmPassword = $input['confirm_password'] ?? '';

        if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
            echo json_encode(['success' => false, 'message' => 'All password fields are required.']);
            exit;
        }

        if ($newPassword !== $confirmPassword) {
            echo json_encode(['success' => false, 'message' => 'New passwords do not match.']);
            exit;
        }

        if (strlen($newPassword) < 8) {
            echo json_encode(['success' => false, 'message' => 'Password must be at least 8 characters.']);
            exit;
        }

        // Verify current password
        $stmt = $conn->prepare("SELECT username, email, password FROM users WHERE id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($res->num_rows === 0) {
            echo json_encode(['success' => false, 'message' => 'User not found.']);
            $stmt->close();
            exit;
        }

        $user = $res->fetch_assoc();
        $stmt->close();

        $isMatch = ($currentPassword === $user['password']) || password_verify($currentPassword, $user['password']);
        if (!$isMatch) {
            echo json_encode(['success' => false, 'message' => 'Current password is incorrect.']);
            exit;
        }

        // Create pending password change request
        $reqStmt = $conn->prepare("INSERT INTO password_requests (user_id, username, email, new_password, status) VALUES (?, ?, ?, ?, 'pending')");
        $reqStmt->bind_param("isss", $userId, $user['username'], $user['email'], $newPassword);
        $reqStmt->execute();
        $reqId = $conn->insert_id;
        $reqStmt->close();

        // Add notification for admin
        $notifTitle = "Password Change Request";
        $notifMsg = "{$user['username']} requested to update their account password.";
        $notifStmt = $conn->prepare("INSERT INTO admin_notifications (title, message, type, is_read, reference_id) VALUES (?, ?, 'password_change', 0, ?)");
        $notifStmt->bind_param("ssi", $notifTitle, $notifMsg, $reqId);
        $notifStmt->execute();
        $notifStmt->close();

        // Activity log
        $actMsg = "Password change requested";
        $actStmt = $conn->prepare("INSERT INTO activity_logs (activity, user_identifier, type) VALUES (?, ?, 'password_request')");
        $actStmt->bind_param("ss", $actMsg, $user['username']);
        $actStmt->execute();
        $actStmt->close();

        echo json_encode([
            'success' => true,
            'message' => 'Password change request submitted! It will take effect once approved by the administrator.'
        ]);
        break;

    // ═══════════════════════════════════════════════════════════
    // ADMIN: GET DASHBOARD DATA
    // ═══════════════════════════════════════════════════════════
    case 'admin_get_dashboard_data':
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            echo json_encode(['success' => false, 'message' => 'Unauthorized access.']);
            exit;
        }

        // Stats: Total users
        $totalUsersCount = 2481; // Baseline marketplace count
        $userQuery = $conn->query("SELECT COUNT(*) AS cnt FROM users WHERE role != 'admin'");
        if ($userQuery) {
            $row = $userQuery->fetch_assoc();
            $totalUsersCount += (int)$row['cnt'];
        }

        // Pending approvals count
        $pendingApprovalsCount = 0;
        $pendingQuery = $conn->query("SELECT COUNT(*) AS cnt FROM password_requests WHERE status = 'pending'");
        if ($pendingQuery) {
            $row = $pendingQuery->fetch_assoc();
            $pendingApprovalsCount = (int)$row['cnt'];
        }

        // Password change requests list
        $requests = [];
        $reqQuery = $conn->query("SELECT id, user_id, username, email, status, admin_feedback, created_at, reviewed_at FROM password_requests ORDER BY id DESC LIMIT 50");
        if ($reqQuery) {
            while ($r = $reqQuery->fetch_assoc()) {
                $r['time_ago'] = formatRelativeTime($r['created_at']);
                $r['avatar_initials'] = strtoupper(substr($r['username'], 0, 2));
                $requests[] = $r;
            }
        }

        // Recent activity
        $activities = [];
        $actQuery = $conn->query("SELECT id, activity, user_identifier, type, created_at FROM activity_logs ORDER BY id DESC LIMIT 10");
        if ($actQuery) {
            while ($a = $actQuery->fetch_assoc()) {
                $a['time_ago'] = formatRelativeTime($a['created_at']);
                $activities[] = $a;
            }
        }

        // Notifications
        $notifications = [];
        $notifQuery = $conn->query("SELECT id, title, message, type, is_read, created_at FROM admin_notifications ORDER BY id DESC LIMIT 10");
        $unreadCount = 0;
        if ($notifQuery) {
            while ($n = $notifQuery->fetch_assoc()) {
                $n['time_ago'] = formatRelativeTime($n['created_at']);
                if ($n['is_read'] == 0) $unreadCount++;
                $notifications[] = $n;
            }
        }

        echo json_encode([
            'success' => true,
            'stats' => [
                'total_users'       => number_format($totalUsersCount),
                'total_listings'    => '1,204',
                'revenue_month'     => '₱248,900',
                'pending_approvals' => $pendingApprovalsCount
            ],
            'requests'      => $requests,
            'activities'    => $activities,
            'notifications' => $notifications,
            'unread_count'  => $unreadCount
        ]);
        break;

    // ═══════════════════════════════════════════════════════════
    // ADMIN: APPROVE OR REJECT PASSWORD REQUEST (with feedback)
    // ═══════════════════════════════════════════════════════════
    case 'admin_handle_password_request':
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            echo json_encode(['success' => false, 'message' => 'Unauthorized access.']);
            exit;
        }

        $requestId = (int)($input['request_id'] ?? 0);
        $decision  = $input['decision'] ?? ''; // 'approve' or 'reject'
        $feedback  = trim($input['feedback'] ?? '');

        if (!$requestId || !in_array($decision, ['approve', 'reject'])) {
            echo json_encode(['success' => false, 'message' => 'Invalid request parameters.']);
            exit;
        }

        // Fetch request
        $stmt = $conn->prepare("SELECT user_id, username, email, new_password, status FROM password_requests WHERE id = ?");
        $stmt->bind_param("i", $requestId);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($res->num_rows === 0) {
            echo json_encode(['success' => false, 'message' => 'Request not found.']);
            $stmt->close();
            exit;
        }

        $req = $res->fetch_assoc();
        $stmt->close();

        if ($decision === 'approve') {
            $defaultFeedback = !empty($feedback) ? $feedback : 'Password change approved by administrator.';
            
            // 1. Update password_requests table
            $upStmt = $conn->prepare("UPDATE password_requests SET status = 'approved', admin_feedback = ?, reviewed_at = NOW() WHERE id = ?");
            $upStmt->bind_param("si", $defaultFeedback, $requestId);
            $upStmt->execute();
            $upStmt->close();

            // 2. Update user's actual password in users table
            $usrStmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
            $usrStmt->bind_param("si", $req['new_password'], $req['user_id']);
            $usrStmt->execute();
            $usrStmt->close();

            // 3. Log activity
            $actMsg = "Password change approved";
            $logStmt = $conn->prepare("INSERT INTO activity_logs (activity, user_identifier, type) VALUES (?, ?, 'approval')");
            $logStmt->bind_param("ss", $actMsg, $req['username']);
            $logStmt->execute();
            $logStmt->close();

            echo json_encode([
                'success' => true,
                'message' => "Password change for {$req['username']} has been approved!",
                'status'  => 'approved',
                'feedback'=> $defaultFeedback
            ]);
        } else {
            // Reject
            $defaultFeedback = !empty($feedback) ? $feedback : 'Password change rejected by administrator.';

            // 1. Update password_requests table
            $upStmt = $conn->prepare("UPDATE password_requests SET status = 'rejected', admin_feedback = ?, reviewed_at = NOW() WHERE id = ?");
            $upStmt->bind_param("si", $defaultFeedback, $requestId);
            $upStmt->execute();
            $upStmt->close();

            // 2. Log activity
            $actMsg = "Password change rejected";
            $logStmt = $conn->prepare("INSERT INTO activity_logs (activity, user_identifier, type) VALUES (?, ?, 'rejection')");
            $logStmt->bind_param("ss", $actMsg, $req['username']);
            $logStmt->execute();
            $logStmt->close();

            echo json_encode([
                'success' => true,
                'message' => "Password change for {$req['username']} has been rejected.",
                'status'  => 'rejected',
                'feedback'=> $defaultFeedback
            ]);
        }
        break;

    // ═══════════════════════════════════════════════════════════
    // ADMIN: MARK NOTIFICATIONS AS READ
    // ═══════════════════════════════════════════════════════════
    case 'admin_mark_notifications_read':
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            echo json_encode(['success' => false, 'message' => 'Unauthorized access.']);
            exit;
        }

        $conn->query("UPDATE admin_notifications SET is_read = 1 WHERE is_read = 0");
        echo json_encode(['success' => true]);
        break;

    // ═══════════════════════════════════════════════════════════
    // LOGOUT
    // ═══════════════════════════════════════════════════════════
    case 'logout':
        session_destroy();
        echo json_encode(['success' => true, 'message' => 'Logged out successfully.']);
        break;

    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action.']);
        break;
}

$conn->close();
