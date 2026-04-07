<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Soil Monitoring System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        body { background-color: #f8f9fa; }
        .sidebar { min-height: 100vh; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .sidebar .nav-link { color: rgba(255, 255, 255, 0.8); padding: 12px 20px; margin: 5px 0; border-radius: 8px; transition: all 0.3s ease; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { color: white; background-color: rgba(255, 255, 255, 0.2); transform: translateX(5px); }
        .main-content { padding: 20px; }
        .card { box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075); border: none; border-radius: 10px; }
        .card-header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 10px 10px 0 0 !important; font-weight: 600; }
        .table { margin-bottom: 0; }
        .badge { font-size: 0.75em; }
        .user-avatar { width: 40px; height: 40px; border-radius: 50%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; }
        .stats-card { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 15px; }
        .stats-icon { font-size: 2rem; opacity: 0.8; }
        .refresh-btn { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; border-radius: 25px; padding: 8px 20px; color: white; transition: all 0.3s ease; }
        .refresh-btn:hover { transform: translateY(-2px); box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4); color: white; }
        .loading-spinner { display: none; }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3 col-lg-2 sidebar p-0">
                <div class="p-4">
                    <h4 class="text-white mb-4"><i class="bi bi-speedometer2 me-2"></i> Admin Panel</h4>
                    <nav class="nav flex-column">
                        <a class="nav-link active" href="#users"><i class="bi bi-people-fill me-2"></i> Users Management</a>
                        <a class="nav-link" href="login.php"><i class="bi bi-box-arrow-right me-2"></i> Logout</a>
                    </nav>
                </div>
            </div>

            <div class="col-md-9 col-lg-10 main-content">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h2 class="mb-0">User Management</h2>
                        <p class="text-muted">Manage system users and their roles</p>
                    </div>
                    <button class="btn refresh-btn" onclick="loadUsers()">
                        <div class="loading-spinner spinner-border spinner-border-sm me-2" role="status"></div>
                        <i class="bi bi-arrow-clockwise me-1"></i> Refresh
                    </button>
                </div>

                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="card stats-card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div><h5 class="card-title">Total Users</h5><h2 class="mb-0" id="total-users">0</h2></div>
                                    <i class="bi bi-people stats-icon"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card stats-card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div><h5 class="card-title">Administrators</h5><h2 class="mb-0" id="admin-count">0</h2></div>
                                    <i class="bi bi-shield-check stats-icon"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card stats-card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div><h5 class="card-title">Regular Users</h5><h2 class="mb-0" id="user-count">0</h2></div>
                                    <i class="bi bi-person stats-icon"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header"><h5 class="mb-0"><i class="bi bi-table me-2"></i> System Users</h5></div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Avatar</th><th>ID</th><th>Username</th><th>Email</th><th>Role</th><th>Status</th><th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="users-table">
                                    <tr>
                                        <td colspan="7" class="text-center py-4">
                                            <div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>
                                            <p class="mt-2 text-muted">Loading users...</p>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="alert alert-danger mt-3" id="error-alert" style="display: none;">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i> <span id="error-message"></span>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="userModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">User Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="modal-body"></div>
                <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button></div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let users = [];

        function getUserInitials(username) { return username ? username.substring(0, 2).toUpperCase() : '??'; }

        function getRoleBadgeClass(role) {
            switch(role?.toLowerCase()) {
                case 'admin': case 'administrator': return 'bg-danger';
                case 'moderator': return 'bg-warning';
                default: return 'bg-primary';
            }
        }

        function showError(message) {
            const errorAlert = document.getElementById('error-alert');
            const errorMessage = document.getElementById('error-message');
            errorMessage.textContent = message;
            errorAlert.style.display = 'block';
            setTimeout(() => { errorAlert.style.display = 'none'; }, 5000);
        }

        function updateStats() {
            const totalUsers = users.length;
            const adminCount = users.filter(user => user.role?.toLowerCase() === 'admin' || user.role?.toLowerCase() === 'administrator').length;
            const userCount = totalUsers - adminCount;

            document.getElementById('total-users').textContent = totalUsers;
            document.getElementById('admin-count').textContent = adminCount;
            document.getElementById('user-count').textContent = userCount;
        }

        async function loadUsers() {
            const loadingSpinner = document.querySelector('.loading-spinner');
            const refreshBtn = document.querySelector('.refresh-btn');
            
            loadingSpinner.style.display = 'inline-block';
            refreshBtn.disabled = true;

            try {
                // FIXED: Now calls your PHP file instead of the Python server!
                const response = await fetch(`admin_users_api.php`);
                
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                const data = await response.json();
                users = Array.isArray(data) ? data : [];
                
                renderUsersTable();
                updateStats();
                
            } catch (error) {
                console.error('Error loading users:', error);
                renderUsersTable();
                updateStats();
                showError('Could not load users. Check database connection.');
            } finally {
                loadingSpinner.style.display = 'none';
                refreshBtn.disabled = false;
            }
        }

        function renderUsersTable() {
            const tableBody = document.getElementById('users-table');
            
            if (users.length === 0) {
                tableBody.innerHTML = `<tr><td colspan="7" class="text-center py-4"><i class="bi bi-inbox display-4 text-muted"></i><p class="mt-2 text-muted">No users found</p></td></tr>`;
                return;
            }

            tableBody.innerHTML = users.map(user => `
                <tr>
                    <td><div class="user-avatar">${getUserInitials(user.username)}</div></td>
                    <td>${user.id}</td>
                    <td><strong>${user.username}</strong></td>
                    <td>${user.email || 'N/A'}</td>
                    <td><span class="badge ${getRoleBadgeClass(user.role)}">${user.role || 'user'}</span></td>
                    <td><span class="badge bg-success">Active</span></td>
                    <td><button class="btn btn-sm btn-outline-primary" onclick="viewUser(${user.id})"><i class="bi bi-eye"></i></button></td>
                </tr>
            `).join('');
        }

        function viewUser(userId) {
            const user = users.find(u => parseInt(u.id) === parseInt(userId));
            if (!user) return;

            document.getElementById('modal-body').innerHTML = `
                <div class="row">
                    <div class="col-md-4 text-center">
                        <div class="user-avatar mx-auto mb-3" style="width: 80px; height: 80px; font-size: 2rem;">${getUserInitials(user.username)}</div>
                    </div>
                    <div class="col-md-8">
                        <table class="table table-borderless">
                            <tr><th>ID:</th><td>${user.id}</td></tr>
                            <tr><th>Username:</th><td>${user.username}</td></tr>
                            <tr><th>Email:</th><td>${user.email || 'N/A'}</td></tr>
                            <tr><th>Role:</th><td><span class="badge ${getRoleBadgeClass(user.role)}">${user.role || 'user'}</span></td></tr>
                            <tr><th>Status:</th><td><span class="badge bg-success">Active</span></td></tr>
                        </table>
                    </div>
                </div>
            `;
            new bootstrap.Modal(document.getElementById('userModal')).show();
        }

        document.addEventListener('DOMContentLoaded', function() {
            loadUsers();
        });
    </script>
</body>
</html>