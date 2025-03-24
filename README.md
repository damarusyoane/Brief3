# Brief3
Gestion des utilisateurs avec l'architecture MVC en PHP
Controllers:
- AuthController.php: Gere login, logout, password reset
- AdminController.php: Gere admin dashboard, user management
- UserController.php: Gere user dashboard, profile, sessions

Models:
- UserModel.php: Gere les operations de l'utilisateurs, La gestion des roles

Views:


/auth/
- login.php
- forgot_password.php
- reset_password.php
- profile.php

/user/
- index.php
- create.php
- edit.php


 Checklist
□ bd Setup
  □ Cree bd
  □ Cree admin role et utilisateur

□ Fonction Admin 
  □ Admin login
  □ Cree nouveau utilisateur
  □ Modifie ustilisateurs
  □ Voir tout les utilisateurs
  □ Change le statuts des utilisateurs

□ Fonction Utilisateurs
  □ Utilisateurs login
  □ Update profile
  □ Change password
  □ Logout

□ Securisation
  □ Password hashing
  □ Access control

  Coment utiliser l'app
  Se placer sur le dossiers public et sur le fichier index.php
  1. Access /auth/login
   - Try with incorrect credentials (should show error)
   - Login with admin credentials:
     Username: administrateur0305
     Password: motdepasse348
   - Should redirect to /users/index
   - Verify dashboard shows:
     * Total users 
     * Active users 
     * Info Users
   


1. Create Regular User:
   - Go to /users/create
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
     * Role
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
   - Should redirect to /auth/profile

2. Profile Management:
   - Try updating profile:
     * Change username
     * Change email
   - Try changing password:
     * Enter new password
  click on update user



1. Forgot Password:
   - Go to /auth/forgot-password
   - Enter email
   - Check for reset email
   - Click reset link
   - Set new password
   - Try logging in with new password




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



1. User Creation:
   - Missing required fields
   - Invalid email format
   - Password too short
   - Duplicate username/email

2. User Authentication:
   - Wrong password
   - Inactive user login
   - Invalid reset token

3. Profile Updates:
   - Invalid email format
   - New passwords don't match
