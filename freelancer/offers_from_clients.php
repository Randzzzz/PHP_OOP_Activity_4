<?php require_once 'classloader.php'; ?>
<?php 
if (!$userObj->isLoggedIn()) {
  header("Location: login.php");
}

if (!$userObj->isFreelancer()) {
  header("Location: ../client/index.php");
} 
$categories = $categoryObj->getCategories();
$allSubcategories = [];
foreach ($categories as $cat) {
    $allSubcategories[$cat['category_id']] = $categoryObj->getSubcategories($cat['category_id']);
}

$filterCategoryId = isset($_GET['category_id']) ? intval($_GET['category_id']) : null;
$filterSubcategoryId = isset($_GET['subcategory_id']) ? intval($_GET['subcategory_id']) : null;

$allProposals = $proposalObj->getProposalsByUserID($_SESSION['user_id']);
if ($filterCategoryId && $filterSubcategoryId) {
  // Filter by both category and subcategory
  $getProposalsByUserID = array_filter($allProposals, function($proposal) use ($filterCategoryId, $filterSubcategoryId) {
    return $proposal['category_id'] == $filterCategoryId && $proposal['subcategory_id'] == $filterSubcategoryId;
  });
} elseif ($filterCategoryId) {
  // Filter by category only
  $getProposalsByUserID = array_filter($allProposals, function($proposal) use ($filterCategoryId) {
    return $proposal['category_id'] == $filterCategoryId;
  });
} else {
  // No filter
  $getProposalsByUserID = $allProposals;
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
      <div class="display-4 text-center">Hello there and welcome! </div>
      <div>
        Current: [
        <?php
        $currentCategory = 'All';
        $currentSubcategory = '';
        if ($filterCategoryId) {
          $catIndex = array_search($filterCategoryId, array_column($categories, 'category_id'));
          if ($catIndex !== false) {
            $currentCategory = htmlspecialchars($categories[$catIndex]['category_name']);
            if ($filterSubcategoryId && isset($allSubcategories[$filterCategoryId])) {
              foreach ($allSubcategories[$filterCategoryId] as $subcat) {
                if ($subcat['subcategory_id'] == $filterSubcategoryId) {
                  $currentSubcategory = ' / ' . htmlspecialchars($subcat['subcategory_name']);
                  break;
                }
              }
            }
          }
        }
        echo $currentCategory . $currentSubcategory;
        ?>
        ]
      </div>
      <div class="row justify-content-center">
        <div class="col-md-12">
          <?php foreach ($getProposalsByUserID as $proposal) { ?>
          <div class="card shadow mt-4 mb-4">
            <div class="card-body">
              <div class="row">
                <div class="col-md-6">
                  <h2><a href="#"><?php echo $proposal['username']; ?></a></h2>
                  <img src="<?php echo '../images/'.$proposal['image']; ?>" class="img-fluid" alt="">
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
                  <p class="mt-4 mb-4"><?php echo $proposal['description']; ?></p>
                  <h4><i><?php echo number_format($proposal['min_price']) . " - " . number_format($proposal['max_price']);?> PHP</i></h4>
                  <div class="float-right">
                    <a href="#">Check out services</a>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="card">
                    <div class="card-header"><h2>All Offers</h2></div>
                    <div class="card-body overflow-auto">
                      <?php $getOffersByProposalID = $offerObj->getOffersByProposalID($proposal['proposal_id']); ?>
                      <?php foreach ($getOffersByProposalID as $offer) { ?>
                      <div class="offer">
                        <h4><?php echo $offer['username']; ?> <span class="text-primary">( <?php echo $offer['contact_number']; ?> )</span></h4>
                        <small><i><?php echo $offer['offer_date_added']; ?></i></small>
                        <p><?php echo $offer['description']; ?></p>
                        <hr>
                      </div>
                      <?php } ?>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <?php } ?>
        </div>
      </div>
    </div>
  </body>
</html>