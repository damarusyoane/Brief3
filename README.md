# Brief3
Gestion des utilisateurs avec l'architecture MVC en PHP
Controllers:
- AuthController.php: Handles login, logout, password reset
- AdminController.php: Handles admin dashboard, user management
- UserController.php: Handles user dashboard, profile, sessions

Models:
- UserModel.php: User operations
- SessionModel.php: Session tracking
- RoleModel.php: Role management

Views:
/admin/
- dashboard.php
- users.php
- create_user.php
- edit_user.php

/auth/
- login.php
- forgot_password.php
- reset_password.php

/user/
- dashboard.php
- change-password.php
- sessions.php


Testing Checklist
□ Database Setup
  □ Create database
  □ Import schema
  □ Create admin role and user

□ Admin Functions
  □ Admin login
  □ Create new users
  □ Edit users
  □ View all users
  □ Monitor sessions
  □ Toggle user status

□ User Functions
  □ User login
  □ Update profile
  □ Change password
  □ View sessions
  □ Logout

□ Security Features
  □ Password hashing
  □ Session management
  □ Access control
  □ CSRF protection

  How you can test the app
  1. Access /auth/login
   - Try with incorrect credentials (should show error)
   - Login with admin credentials:
     Username: admin
     Password: Admin@123
   - Should redirect to /admin/dashboard
   - Verify dashboard shows:
     * Total users count
     * Active users count
     * Online users count
     * Recent activity


1. Create Regular User:
   - Go to /admin/users
   - Click "Create New User"
   - Fill form with:
     * Username: testuser
     * Email: test@example.com
     * Password: Test@123
     * Role: User
   - Submit and verify user appears in list

2. Edit User:
   - Click edit on created user
   - Modify:
     * Email
     * Username
     * Password (optional)
   - Save and verify changes

3. Toggle User Status:
   - Find user in list
   - Click "Deactivate"
   - Verify status changes
   - Click "Activate"
   - Verify status changes back

4. Delete User:
   - Click delete button
   - Confirm deletion
   - Verify user removed from list


1. Login as Created User:
   - Logout from admin
   - Login with:
     Username: testuser
     Password: Test@123
   - Should redirect to /user/dashboard

2. Profile Management:
   - Try updating profile:
     * Change username
     * Change email
   - Try changing password:
     * Enter current password
     * Enter new password
     * Confirm new password

3. Session Management:
   - Go to /user/sessions
   - Verify current session shows
   - Verify session details:
     * IP Address
     * Browser info
     * Login time



1. Forgot Password:
   - Go to /auth/forgot-password
   - Enter email
   - Check for reset email
   - Click reset link
   - Set new password
   - Try logging in with new password

2. Remember Me:
   - Login with "Remember Me" checked
   - Close browser
   - Reopen and verify still logged in



1. Access Control:
   - Try accessing admin pages as regular user
   - Try accessing user pages when logged out
   - Try accessing reset password with invalid token

2. Input Validation:
   - Try creating user with:
     * Invalid email format
     * Short password
     * Duplicate username
     * Duplicate email

3. Session Security:
   - Open multiple browsers
   - Verify sessions tracked separately
   - Logout from one
   - Verify other sessions unaffected


1. User Creation:
   - Missing required fields
   - Invalid email format
   - Password too short
   - Duplicate username/email

2. User Authentication:
   - Wrong password
   - Inactive user login
   - Expired session
   - Invalid reset token

3. Profile Updates:
   - Invalid email format
   - Current password incorrect
   - New passwords don't match