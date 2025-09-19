<?php require_once 'classloader.php'; ?>
<?php 
if (!$userObj->isLoggedIn()) {
  header("Location: login.php");
}

if (!$userObj->isFreelancer()) {
  header("Location: ../client/index.php");
} 
$categories = $categoryObj->getCategories();
$getProposals = $proposalObj->getProposals();
$allSubcategories = [];
foreach ($categories as $cat) {
    $allSubcategories[$cat['category_id']] = $categoryObj->getSubcategories($cat['category_id']);
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">
    <script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>
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
      <div class="display-4 text-center">Hello there and welcome! <span class="text-success"><?php echo $_SESSION['username']; ?></span>. Add Proposal Here!</div>
      <div class="row">
        <div class="col-md-5">
          <div class="card mt-4 mb-4">
            <div class="card-body">
              <form action="core/handleForms.php" method="POST" enctype="multipart/form-data">
                <div class="card-body">
                  <script>
                    document.addEventListener("DOMContentLoaded", function () {
                      const notyf = new Notyf();

                      <?php  
                      if (isset($_SESSION['message']) && isset($_SESSION['status'])) {
                        $msg = json_encode($_SESSION['message']);

                        if ($_SESSION['status'] == "200") {
                          echo "notyf.success({ message: $msg, position: {x: 'center', y: 'top'}, duration: 2000});";
                        }
                        else {
                          echo "notyf.error({ message: $msg, position: {x: 'center', y: 'top'}, duration: 2000});";
                        }

                      }
                      unset($_SESSION['message']);
                      unset($_SESSION['status']);
                      ?>
                    });
                  </script>
                  <h1 class="mb-4 mt-4">Add Proposal Here!</h1>
                  <div class="form-group">
                    <label for="exampleInputEmail1">Description</label>
                    <input type="text" class="form-control" name="description" required>
                  </div>
                  <div class="form-group">
                    <label for="exampleInputEmail1">Minimum Price</label>
                    <input type="number" class="form-control" name="min_price" required>
                  </div>
                  <div class="form-group">
                    <label for="exampleInputEmail1">Max Price</label>
                    <input type="number" class="form-control" name="max_price" required>
                  </div>
                  <div class="form-group">
                    <label for="category_id">Category</label>
                    <select name="category_id" id="category_id" class="form-control" required>
                      <option value="">Select Category</option>
                      <?php foreach ($categories as $cat): ?>
                        <option value="<?php echo $cat['category_id']; ?>"><?php echo htmlspecialchars($cat['category_name']); ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                  <div class="form-group">
                    <label for="subcategory_id">Subcategory</label>
                    <select name="subcategory_id" id="subcategory_id" class="form-control" required>
                      <option value="">Select Subcategory</option>
                      <!-- Subcategories will be loaded via JS -->
                    </select>
                  </div>
                  <div class="form-group">
                    <label for="exampleInputEmail1">Image</label>
                    <input type="file" class="form-control" name="image" required>
                    <input type="submit" class="btn btn-primary float-right mt-4" name="insertNewProposalBtn">
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
        <div class="col-md-7">
          <?php foreach ($getProposals as $proposal) { ?>
          <div class="card shadow mt-4 mb-4">
            <div class="card-body">
              <h2><a href="other_profile_view.php?user_id=<?php echo $proposal['user_id']; ?>"><?php echo $proposal['username']; ?></a></h2>
              <img src="<?php echo '../images/' . $proposal['image']; ?>" alt="" class="img-fluid">
              <p class="mt-4">
                <?php
                  // Display category name
                  $catId = $proposal['category_id'];
                  echo isset($categories) && isset($catId) && isset($categories[array_search($catId,array_column($categories, 'category_id'))])
                    ? htmlspecialchars($categories[array_search($catId, array_column($categories, 'category_id'))]['category_name'])
                      : 'Unknown Category';

                    // Display subcategory name
                    $subcatId = $proposal['subcategory_id'];
                    $subcatName = 'Unknown Subcategory';
                    if (isset($allSubcategories[$catId])) {
                      foreach ($allSubcategories[$catId] as $subcat) {
                        if ($subcat['subcategory_id'] == $subcatId) {
                          $subcatName = $subcat['subcategory_name'];
                          break;
                        }
                      }
                    }
                    echo " - " . htmlspecialchars($subcatName);
                  ?>
              </p>
              <p class="mt-4"><i><?php echo $proposal['proposals_date_added']; ?></i></p>
              <p class="mt-2"><?php echo $proposal['description']; ?></p>
              <h4><i><?php echo number_format($proposal['min_price']) . " - " . number_format($proposal['max_price']); ?> PHP</i></h4>
              <div class="float-right">
                <a href="#">Check out services</a>
              </div>
            </div>
          </div>
          <?php } ?>
        </div>
      </div>
    </div>
    <script>
    var subcategories = <?php echo json_encode($allSubcategories); ?>;
    $('#category_id').change(function() {
        var catId = $(this).val();
        var $subcat = $('#subcategory_id');
        $subcat.empty();
        $subcat.append('<option value="">Select Subcategory</option>');
        if (catId && subcategories[catId]) {
            subcategories[catId].forEach(function(sub) {
                $subcat.append('<option value="' + sub.subcategory_id + '">' + sub.subcategory_name + '</option>');
            });
        }
    });
    </script>
  </body>
</html>