<?php
  /* This action handles the registration of an user */
  declare(strict_types = 1);

  require_once(__DIR__ . '/../utils/session.php');
  $session = new Session();

  require_once(__DIR__ . '/../db/connection.db.php');
  require_once(__DIR__ . '/../db/class_user.php');

  $db = getDatabaseConnection();

  $fields = verifyAllFields($session);

  $user = User::getExistingUser($db, $fields['email'], $fields['phone_number']);

  if ($user) {
    $session->addMessage('error', 'Already exists a user with the same email/phone.');
  } else {
    $newUser = User::register($db, $fields);
    $session->addMessage('success', 'Register successful!');
    header('Location: ../pages/login.php');
    die();
  }

  header('Location: ' . $_SERVER['HTTP_REFERER']);
?>


<?php function onInvalidField(Session $session, string $msg){
    $session->addMessage('error', $msg);
    header('Location: ../pages/login.php?register=true');
    die();
} ?>

<?php function verifyAllFields(Session $session) : array{
  if($_POST['email'] == "")
    onInvalidField($session, 'Please enter a email.');

  $email = strip_tags($_POST['email']);
  if(!filter_var($email, FILTER_VALIDATE_EMAIL))
    onInvalidField($session, 'Invalid email.');

  if($_POST['phone_number'] == "")
    onInvalidField($session, 'Please enter a phone number.');

  $phoneNumber = strip_tags($_POST['phone_number']);
  if(!preg_match('/9\d{8}/', $phoneNumber)){
    onInvalidField($session, 'Invalid phone number.');
  }

  $firstName = strip_tags($_POST['first_name']);
  $lastName = strip_tags($_POST['last_name']);
  $address = strip_tags($_POST['address']);

  if($firstName == "")
      onInvalidField($session, 'Invalid first name.');

  if($lastName == "")
      onInvalidField($session, 'Invalid address.');

  if($address == "")
      onInvalidField($session, 'Invalid phone number.');

  $password = $_POST['password'];
  if(strlen($password) < '8')
    onInvalidField($session, 'Your Password Must Contain At Least 8 Characters!');
  elseif(!preg_match("#[0-9]+#", $password)) 
    onInvalidField($session, 'Your Password Must Contain At Least 1 Number!');
  elseif(!preg_match("#[A-Z]+#",$password))
    onInvalidField($session, 'Your Password Must Contain At Least 1 Capital Letter!');
  elseif(!preg_match("#[a-z]+#",$password))
    onInvalidField($session, 'Your Password Must Contain At Least 1 Lowercase Letter!');

  return [
    'first_name' => $firstName,
    'last_name' => $lastName,
    'email' => $email,
    'address' => $address,
    'phone_number' => $phoneNumber,
    'password' => $password
  ];
} ?>
