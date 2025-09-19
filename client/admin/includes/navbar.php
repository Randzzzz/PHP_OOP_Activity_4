<nav class="navbar navbar-expand-lg navbar-dark p-4" style="background-color: #002352ff;">
  <a class="navbar-brand" href="index.php">Admin Panel</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarNav">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" href="../index.php">Home</a>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="../index.php" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Categories
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
          <?php
          $categories = $categoryObj->getCategories();
          foreach ($categories as $cat) {
            // Category link
            echo '<a class="dropdown-item font-weight-bold" href="../index.php?category_id=' . $cat['category_id'] . '">' . htmlspecialchars($cat['category_name']) . '</a>';
            $subcategories = $categoryObj->getSubcategories($cat['category_id']);
            if ($subcategories && count($subcategories) > 0) {
              foreach ($subcategories as $subcat) {
                echo '<a class="dropdown-item pl-4" href="../index.php?category_id=' . $cat['category_id'] . '&subcategory_id=' . $subcat['subcategory_id'] . '">' . htmlspecialchars($subcat['subcategory_name']) . '</a>';
              }
            }
            echo '<div class="dropdown-divider"></div>';
          }
          ?>
        </div>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="../project_offers_submitted.php">Project Offers Submitted</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="../profile.php">Profile</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="category.php">Manage Categories</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="core/handleForms.php?logoutUserBtn=1">Logout</a>
      </li>
    </ul>
  </div>
</nav>

