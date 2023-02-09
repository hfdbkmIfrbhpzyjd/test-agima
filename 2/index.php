<?php

function ValidateComment($text)
{
  return strlen($text) <= 500;
}

function ValidateRating($rating)
{
  $validValues = [];
  for ($i = 0; $i <= 10; $i++) {
    $validValues[] = strval($i);
  }

  return in_array($rating, $validValues);
}

$success = false;

$errStatus = [
  'comment' => false,
  'emptyEmail' => false,
  'errRating' => false,
  'errCorrectEmail' => false,
];

$errMessages = [
  'comment' => 'Comment must be less than 500 characters',
  'emptyEmail' => 'Email is required',
  'errRating' => 'Rating input error',
  'errCorrectEmail' => 'Enter a valid email',
];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

  $record = [
    'email' => '',
    'name' => '',
    'rating' => '',
    'comment' => '',
  ];

  if (isset($_POST['name'])) {
    $record['name'] = $_POST['name'];
  }

  if (isset($_POST['email']) && $_POST['email'] != '') {

    if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
      $record['email'] = $_POST['email'];
    } else {
      $errStatus['errCorrectEmail'] = true;
    }
  } else {
    $errStatus['emptyEmail'] = true;
  }

  if (isset($_POST['rating']) && ValidateRating($_POST['rating'])) {
    $record['rating'] = $_POST['rating'];
  } else {
    $errStatus['errRating'] = true;
  }

  if (isset($_POST['comment']) && ValidateComment($_POST['comment'])) {
    $record['comment'] = $_POST['comment'];
  } else {
    $errStatus['comment'] = true;
  }
  $success = !in_array(true, $errStatus);
  if ($success) {
    $log = date('Y-m-d H:i:s') . ' ' . print_r($record, true);
    file_put_contents(__DIR__ . '/log.txt', $log . PHP_EOL, FILE_APPEND);

    $_POST = [];
  }
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
  <title>Document</title>
</head>

<body>
  <div class="container">
    <div class="py-5">
      <?php if ($success) : ?>
        <div class="alert alert-success" role="alert">
          Your feedback is very important to us!
        </div>
      <?php endif; ?>
      <?php foreach ($errStatus as $key => $status) : ?>
        <?php if ($status) : ?>
          <div class="alert alert-danger"><?= $errMessages[$key] ?></div>
        <?php endif; ?>
      <?php endforeach; ?>
      <h2 class="mb-3">Feedback</h2>
      <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <div class="form-group row mb-3">
          <label for="FormLabel1" class="col-sm-1 col-form-label">Email<span class="text-danger">*</span></label>
          <div class="col-sm-3">
            <input name="email" type="text" class="form-control" id="FormLabel1" placeholder="mail@mail.mail" value="<?= htmlspecialchars($_POST['email']) ?? '' ?>">
          </div>
        </div>
        <div class="form-group row mb-3">
          <label for="FormLabel2" class="col-sm-1 col-form-label">Name</label>
          <div class="col-sm-3">
            <input name="name" type="text" class="form-control" id="FormLabel2" placeholder="Name" value="<?= htmlspecialchars($_POST['name']) ?? '' ?>">
          </div>
        </div>
        <div class="form-group row mb-3">
          <label for="FormControlSelect" class=" col-sm-1 col-form-label">Rating</label>
          <div class="col-sm-1">
            <select name="rating" class="form-control text-center" id="FormControlSelect">
              <?php for ($i = 0; $i <= 10; $i++) : ?>
                <option value="<?= $i ?>" <?= $_POST['rating'] == $i ? 'selected' : '' ?>><?= $i ?></option>
              <?php endfor; ?>
            </select>
          </div>
        </div>
        <div class="form-group mb-3">
          <div class="mb-2"> <label for="FormControlTextarea">Comment:</label></div>
          <div class="col-sm-4">
            <textarea name="comment" class="form-control" id="FormControlTextarea" rows="3"><?= htmlspecialchars($_POST['comment']) ?? '' ?></textarea>
          </div>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
      </form>
    </div>
  </div>
</body>

</html>