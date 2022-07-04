<?php
    require_once("../templates/t_index.php");
    require_once("../templates/commom.php");
    require_once("../db/connection.db.php");
    require_once("../db/class_user.php");
    require_once(__DIR__ . '/../utils/session.php');

    $session = new Session();

    $db = getDatabaseConnection();

    $user = User::getCurrentUser($db,$session->getId());

    if(!$session->isLoggedIn()) {
        header('Location: index.php');
        die();
    }

    // CSRF
    if ($_SESSION['csrf'] !== $_POST['csrf']){
        header("Location: ../pages/index.php");
        die();
    }

    if(isset($_POST['update'])) {
        $fields = verifyAllFields($session);
        $check = "Password";

        if($fields['password'] != $check) {
            
            User::changePassword($db,$user->id,$fields['password']);
            User::updateUser($db,$fields['first_name'],$fields['last_name'],$fields['email'],$fields['address'],$fields['phone_number'],$session->getId());
            $_SESSION['name'] = $fields['first_name'] . ' ' . $fields['last_name'];
        }
        else {
            User::updateUser($db,$fields['first_name'],$fields['last_name'],$fields['email'],$fields['address'],$fields['phone_number'],$session->getId());
            $_SESSION['name'] = $fields['first_name'] . ' ' . $fields['last_name'];
        }
    }
    header('Location: ../pages/profile.php');
?>

<?php function onInvalidField(Session $session, string $msg){
    $session->addMessage('error', $msg);
    header('Location: ../pages/profile.php');
    die();
} ?>

<?php function verifyAllFields(Session $session) : array{
  if($_POST['email'] == "")
    onInvalidField($session, 'Please enter a email.');

  $email = strip_tags($_POST['email']);
  if(!filter_var($email, FILTER_VALIDATE_EMAIL))
    onInvalidField($session, 'Invalid email.');

  if($_POST['phone'] == "")
    onInvalidField($session, 'Please enter a phone number.');

  $phone = strip_tags($_POST['phone']);
  if(!preg_match('/9\d{8}/', $phone)){
    onInvalidField($session, 'Invalid phone number.');
  }

  $firstName = strip_tags($_POST['firstName']);
  $lastName = strip_tags($_POST['lastName']);
  $adress = strip_tags($_POST['adress']);

  if($firstName == "")
      onInvalidField($session, 'Invalid first name.');

  if($lastName == "")
      onInvalidField($session, 'Invalid last name.');

  if($adress == "")
      onInvalidField($session, 'Invalid adress.');

  $password = $_POST['password'];

  $check = "Password";
  
  if($password != $check) {
    if(strlen($password) < '8')
        onInvalidField($session, 'Your Password Must Contain At Least 8 Characters!');
    elseif(!preg_match("#[0-9]+#", $password)) 
        onInvalidField($session, 'Your Password Must Contain At Least 1 Number!');
    elseif(!preg_match("#[A-Z]+#",$password))
        onInvalidField($session, 'Your Password Must Contain At Least 1 Capital Letter!');
    elseif(!preg_match("#[a-z]+#",$password))
        onInvalidField($session, 'Your Password Must Contain At Least 1 Lowercase Letter!');
  }

  $session->addMessage('success', 'Data Changed Successfully!');

  return [
    'first_name' => $firstName,
    'last_name' => $lastName,
    'email' => $email,
    'address' => $adress,
    'phone_number' => $phone,
    'password' => $password
  ];
} ?>
