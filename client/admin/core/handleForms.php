<?php
require_once '../../classloader.php';


if (isset($_POST['insertNewUserBtn'])) {
    $username = htmlspecialchars(trim($_POST['username']));
    $email = htmlspecialchars(trim($_POST['email']));
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    if (!empty($username) && !empty($email) && !empty($password) && !empty($confirm_password)) {
        if ($password == $confirm_password) {
            if (!$userObj->usernameExists($username)) {
                $role = 'administrator';
                if ($userObj->registerUser($username, $email, $password, $role)) {
                    $_SESSION['message'] = "Admin registered successfully!";
                    $_SESSION['status'] = '200';
                    header("Location: ../login.php");
                } else {
                    $_SESSION['message'] = "An error occurred during registration.";
                    $_SESSION['status'] = '400';
                    header("Location: ../register.php");
                }
            } else {
                $_SESSION['message'] = $username . " as username is already taken";
                $_SESSION['status'] = '400';
                header("Location: ../register.php");
            }
        } else {
            $_SESSION['message'] = "Please make sure both passwords are equal";
            $_SESSION['status'] = '400';
            header("Location: ../register.php");
        }
    } else {
        $_SESSION['message'] = "Please make sure there are no empty input fields";
        $_SESSION['status'] = '400';
        header("Location: ../register.php");
    }
}


if (isset($_POST['loginUserBtn'])) {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (!empty($email) && !empty($password)) {
        $user = $userObj->getUserByEmailAndRole($email, 'administrator');
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            header("Location: ../category.php");
        } else {
            $_SESSION['message'] = "Username/password invalid or not an admin.";
            $_SESSION['status'] = "400";
            header("Location: ../login.php");
        }
    } else {
        $_SESSION['message'] = "Please make sure there are no empty input fields";
        $_SESSION['status'] = '400';
        header("Location: ../login.php");
    }
}

if (isset($_GET['logoutUserBtn'])) {
    session_unset();
    session_destroy();
    header("Location: ../../../index.php");
}

// category management
if (isset($_POST['addCategoryBtn'])) {
    $category_name = trim($_POST['category_name']);
    if (!empty($category_name)) {
        $categoryObj = new Category();
        $categoryObj->addCategory($category_name);
        $_SESSION['message'] = "Category added successfully!";
        $_SESSION['status'] = '200';
    } else {
        $_SESSION['message'] = "Category name cannot be empty.";
        $_SESSION['status'] = '400';
    }
    header("Location: ../category.php");
    exit();
}

if (isset($_POST['addSubcategoryBtn'])) {
    $category_id = $_POST['category_id'];
    $subcategory_name = trim($_POST['subcategory_name']);
    if (!empty($category_id) && !empty($subcategory_name)) {
        $categoryObj = new Category();
        $categoryObj->addSubcategory($category_id, $subcategory_name);
        $_SESSION['message'] = "Subcategory added successfully!";
        $_SESSION['status'] = '200';
    } else {
        $_SESSION['message'] = "Please select a category and enter a subcategory name.";
        $_SESSION['status'] = '400';
    }
    header("Location: ../category.php");
    exit();
}
// Edit Category
if (isset($_POST['editCategoryBtn'])) {
    $category_id = $_POST['category_id'];
    $category_name = trim($_POST['category_name']);
    if (!empty($category_id) && !empty($category_name)) {
        $categoryObj = new Category();
        $categoryObj->updateCategory($category_id, $category_name);
        $_SESSION['message'] = "Category updated successfully!";
        $_SESSION['status'] = '200';
    } else {
        $_SESSION['message'] = "Category name cannot be empty.";
        $_SESSION['status'] = '400';
    }
    header("Location: ../category.php");
    exit();
}

if (isset($_GET['deleteCategory'])) {
    $category_id = $_GET['deleteCategory'];
    if (!empty($category_id)) {
        $categoryObj = new Category();
        $categoryObj->deleteCategory($category_id);
        $_SESSION['message'] = "Category deleted successfully!";
        $_SESSION['status'] = '200';
    } else {
        $_SESSION['message'] = "Category ID missing.";
        $_SESSION['status'] = '400';
    }
    header("Location: ../category.php");
    exit();
}

if (isset($_POST['editSubcategoryBtn'])) {
    $subcategory_id = $_POST['subcategory_id'];
    $subcategory_name = trim($_POST['subcategory_name']);
    if (!empty($subcategory_id) && !empty($subcategory_name)) {
        $categoryObj = new Category();
        $categoryObj->updateSubcategory($subcategory_id, $subcategory_name);
        $_SESSION['message'] = "Subcategory updated successfully!";
        $_SESSION['status'] = '200';
    } else {
        $_SESSION['message'] = "Subcategory name cannot be empty.";
        $_SESSION['status'] = '400';
    }
    header("Location: ../category.php");
    exit();
}

if (isset($_GET['deleteSubcategory'])) {
    $subcategory_id = $_GET['deleteSubcategory'];
    if (!empty($subcategory_id)) {
        $categoryObj = new Category();
        $categoryObj->deleteSubcategory($subcategory_id);
        $_SESSION['message'] = "Subcategory deleted successfully!";
        $_SESSION['status'] = '200';
    } else {
        $_SESSION['message'] = "Subcategory ID missing.";
        $_SESSION['status'] = '400';
    }
    header("Location: ../category.php");
    exit();
}
