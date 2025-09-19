<?php require_once 'classloader.php'; ?>
<?php 
if (!$userObj->isLoggedIn()) {
  header("Location: login.php");
}

if (!$userObj->isClient() && !$userObj->isAdmin()) {
  header("Location: ../freelancer/index.php");
} 
?>
<!doctype html>
  <html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>
    <style>
      body {
        font-family: "Arial";
      }
    </style>
  </head>
  <body>
    <?php include 'includes/navbar.php'; ?>
    <div class="container-fluid">
      <div class="display-4 text-center">Hello there and welcome! Here are all the submitted project offers!</div>
      <div class="row justify-content-center">
        <div class="col-md-10 mt-4 mb-4">
          <?php $offers = $offerObj->getOffers(); ?>
          <table class="table table-bordered table-hover mt-4">
            <thead style="background-color: #023E8A;" class="text-white">
              <tr>
                <th>Offer ID</th>
                <th>Proposal ID</th>
                <th>Client Username</th>
                <th>Client Email</th>
                <th>Description</th>
                <th>Date Submitted</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($offers as $offer): ?>
                <tr>
                  <td><?php echo $offer['offer_id']; ?></td>
                  <td><?php echo $offer['proposal_id']; ?></td>
                  <td><?php echo $offer['username']; ?></td>
                  <td><?php echo $offer['email']; ?></td>
                  <td><?php echo $offer['description']; ?></td>
                  <td><?php echo $offer['offer_date_added']; ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </body>
</html>