<?php  
require_once '../classloader.php';

if (isset($_POST['insertNewUserBtn'])) {
	$username = htmlspecialchars(trim($_POST['username']));
	$email = htmlspecialchars(trim($_POST['email']));
	$contact_number = htmlspecialchars(trim($_POST['contact_number']));
	$password = trim($_POST['password']);
	$confirm_password = trim($_POST['confirm_password']);

	if (!empty($username) && !empty($email) && !empty($password) && !empty($confirm_password)) {
		if ($password == $confirm_password) {

			if (!$userObj->usernameExists($username)) {
				// Register as client
				$role = 'client';
				if ($userObj->registerUser($username, $email, $password, $role, $contact_number)) {
					header("Location: ../login.php");
				} else {
					$_SESSION['message'] = "An error occured with the query!";
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
		$user = $userObj->getUserByEmailAndRole($email, 'client');
		if ($user && password_verify($password, $user['password'])) {
			$_SESSION['user_id'] = $user['user_id'];
			$_SESSION['username'] = $user['username'];
			$_SESSION['role'] = $user['role'];
			header("Location: ../index.php");
		} else {
			$_SESSION['message'] = "Username/password invalid";
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
	$userObj->logout();
	header("Location: ../../index.php");
}

if (isset($_POST['updateUserBtn'])) {
	$contact_number = htmlspecialchars($_POST['contact_number']);
	$bio_description = htmlspecialchars($_POST['bio_description']);
	$display_picture = "";
	if (isset($_FILES['display_picture']) && $_FILES['display_picture']['error'] === UPLOAD_ERR_OK) {
		$fileTmpPath = $_FILES['display_picture']['tmp_name'];
		$fileName = $_FILES['display_picture']['name'];
		$fileSize = $_FILES['display_picture']['size'];
		$fileType = $_FILES['display_picture']['type'];
		$fileNameCmps = explode(".", $fileName);
		$fileExtension = strtolower(end($fileNameCmps));
		$allowedfileExtensions = array('jpg', 'jpeg', 'png', 'gif');
		if (in_array($fileExtension, $allowedfileExtensions)) {
			$newFileName = uniqid('profile_', true) . '.' . $fileExtension;
			$uploadFileDir = '../profile_uploads/';
			if (!is_dir($uploadFileDir)) {
				mkdir($uploadFileDir, 0777, true);
			}
			$dest_path = $uploadFileDir . $newFileName;
			if(move_uploaded_file($fileTmpPath, $dest_path)) {
				$display_picture = $newFileName;
			}
		}
	}
	if ($userObj->updateUser($contact_number, $bio_description, $_SESSION['user_id'], $display_picture)) {
		header("Location: ../profile.php");
	}
}

if (isset($_POST['insertOfferBtn'])) {
	$user_id = $_SESSION['user_id'];
	$proposal_id = $_POST['proposal_id'];
	$description = htmlspecialchars($_POST['description']);
	$alreadySubmitted = $offerObj->hasUserSubmittedOffer($user_id, $proposal_id);
	if ($alreadySubmitted) {
		$_SESSION['message'] = "You have already submitted an offer for this proposal.";
		$_SESSION['status'] = '400';
		header("Location: ../index.php");
		return;
	}
	if ($offerObj->createOffer($user_id, $description, $proposal_id)) {
		$_SESSION['message'] = "Offer inserted successfully!";
		$_SESSION['status'] = '200';
		header("Location: ../index.php");
	}
}

if (isset($_POST['updateOfferBtn'])) {
	$description = htmlspecialchars($_POST['description']);
	$offer_id = $_POST['offer_id'];
	if ($offerObj->updateOffer($description, $offer_id)) {
		$_SESSION['message'] = "Offer updated successfully!";
		$_SESSION['status'] = '200';
		header("Location: ../index.php");
	}
}

if (isset($_POST['deleteOfferBtn'])) {
	$offer_id = $_POST['offer_id'];
	if ($offerObj->deleteOffer($offer_id)) {
		$_SESSION['message'] = "Offer deleted successfully!";
		$_SESSION['status'] = '200';
		header("Location: ../index.php");
	}
}

