<?php require_once '../app/views/templates/header.php'; ?>

<div class="container-fluid mt-4">
    <div class="row">
        <!-- Sidebar -->
        <nav class="col-md-2 d-none d-md-block bg-light sidebar">
            <div class="sidebar-sticky">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?controller=admin&action=dashboard">
                            <i class="fas fa-tachometer-alt"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="index.php?controller=admin&action=users">
                            <i class="fas fa-users"></i> Users
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?controller=admin&action=logs">
                            <i class="fas fa-history"></i> Logs
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        <!-- Main content -->
        <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Edit User</h1>
            </div>

            <?php if (isset($data['error'])): ?>
                <div class="alert alert-danger"><?php echo $data['error']; ?></div>
            <?php endif; ?>

            <div class="card">
                <div class="card-body">
                    <form action="index.php?controller=admin&action=editUser&id=<?php echo $data['user']['id']; ?>" method="POST">
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($data['user']['username']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($data['user']['email']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="password">New Password (leave blank to keep current)</label>
                            <input type="password" class="form-control" id="password" name="password">
                        </div>
                        <div class="form-group">
                            <label for="role">Role</label>
                            <select class="form-control" id="role" name="role_id">
                                <option value="2" <?php echo $data['user']['role_id'] == 2 ? 'selected' : ''; ?>>User</option>
                                <option value="1" <?php echo $data['user']['role_id'] == 1 ? 'selected' : ''; ?>>Admin</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select class="form-control" id="status" name="status">
                                <option value="active" <?php echo $data['user']['status'] == 'active' ? 'selected' : ''; ?>>Active</option>
                                <option value="inactive" <?php echo $data['user']['status'] == 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Update User</button>
                        <a href="index.php?controller=admin&action=users" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </main>
    </div>
</div>

<?php require_once '../app/views/templates/footer.php'; ?> 