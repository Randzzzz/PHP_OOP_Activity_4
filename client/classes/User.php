<?php  

require_once 'Database.php';
/**
 * Class for handling User-related operations.
 * Inherits CRUD methods from the Database class.
 */
class User extends Database {

    /**
     * Starts a new session if one isn't already active.
     */
    public function startSession() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Checks if the username already exists in the database.
     * @param string $username The username to check.
     * @return bool True if username exists, false otherwise.
     */
    public function usernameExists($username) {
        $sql = "SELECT COUNT(*) as username_count FROM fiverr_clone_users WHERE username = ?";
        $count = $this->executeQuerySingle($sql, [$username]);
        if ($count['username_count'] > 0) {
            return true;
        }
        else {
            return false;
        }
    }
    

    /**
     * Registers a new user.
     * @param string $username The user's username.
     * @param string $email The user's email.
     * @param string $password The user's password.
     * @param string $role The user's role (client, freelancer, administrator).
     * @param string $contact_number The user's contact number.
     * @return bool True on success, false on failure.
     */
    public function registerUser($username, $email, $password, $role = 'client', $contact_number = '') {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO fiverr_clone_users (username, email, password, role, contact_number) VALUES (?, ?, ?, ?, ?)";
        try {
            $this->executeNonQuery($sql, [$username, $email, $hashed_password, $role, $contact_number]);
            return true;
        } catch (\PDOException $e) {
            return false;
        }
    }

    // Get user by email and role
    public function getUserByEmailAndRole($email, $role) {
        $sql = "SELECT * FROM fiverr_clone_users WHERE email = ? AND role = ?";
        return $this->executeQuerySingle($sql, [$email, $role]);
    }

    /**
     * Checks if a user is currently logged in.
     * @return bool
     */
    public function isLoggedIn() {
        $this->startSession();
        return isset($_SESSION['user_id']);
    }

    /**
     * Checks if the logged-in user is an admin.
     * @return bool
     */
    public function isAdmin() {
        $this->startSession();
        return isset($_SESSION['role']) && $_SESSION['role'] === 'administrator';
    }

    public function isClient() {
        $this->startSession();
        return isset($_SESSION['role']) && $_SESSION['role'] === 'client';
    }

    public function isFreelancer() {
        $this->startSession();
        return isset($_SESSION['role']) && $_SESSION['role'] === 'freelancer';
    }

    /**
     * Logs out the current user.
     */
    public function logout() {
        $this->startSession();
        session_unset();
        session_destroy();
    }

    /**
     * Retrieves fiverr_clone_users from the database.
     * @param int|null $id The user ID to retrieve, or null for all fiverr_clone_users.
     * @return array
     */
    public function getUsers($id = null) {
        if ($id) {
            $sql = "SELECT * FROM fiverr_clone_users WHERE user_id = ?";
            return $this->executeQuerySingle($sql, [$id]);
        }
        $sql = "SELECT * FROM fiverr_clone_users";
        return $this->executeQuery($sql);
    }

    /**
     * Updates a user's information.
     * @param int $id The user ID to update.
     * @param string $username The new username.
     * @param string $email The new email.
     * @param bool $is_admin The new admin status.
     * @return int The number of affected rows.
     */
    public function updateUser($contact_number, $bio_description, $user_id, $display_picture="") {
        if (empty($display_picture)) {
            $sql = "UPDATE fiverr_clone_users SET contact_number = ?, bio_description = ? WHERE user_id = ?";
            return $this->executeNonQuery($sql, [$contact_number, $bio_description, $user_id]);
        } else {
            $sql = "UPDATE fiverr_clone_users SET contact_number = ?, bio_description = ?, display_picture = ? WHERE user_id = ?";
            return $this->executeNonQuery($sql, [$contact_number, $bio_description, $display_picture, $user_id]);
        }
    }

    /**
     * Deletes a user.
     * @param int $id The user ID to delete.
     * @return int The number of affected rows.
     */
    public function deleteUser($id) {
        $sql = "DELETE FROM fiverr_clone_users WHERE user_id = ?";
        return $this->executeNonQuery($sql, [$id]);
    }
}

?>