<?php require_once '../classloader.php'; ?>
<?php 
if (!$userObj->isLoggedIn()) {
  header("Location: login.php");
}

if (!$userObj->isAdmin()) {
  header("Location: ../../freelancer/index.php");
} 
$categories = $categoryObj->getCategories();
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

    <div class="container mt-5">
      <div class="row border p-4">
        <div class="col-md-6">
          <h3>Add Category</h3>
          <form action="core/handleForms.php" method="POST">
            <div class="form-group">
              <label>Category Name</label>
              <input type="text" name="category_name" class="form-control" required>
            </div>
            <button type="submit" name="addCategoryBtn" class="btn btn-primary">Add Category</button>
          </form>
        </div>
        <div class="col-md-6">
          <h3>Add Subcategory</h3>
          <form action="core/handleForms.php" method="POST">
            <div class="form-group">
              <label>Category</label>
              <select name="category_id" class="form-control" required>
                <option value="">Select Category</option>
                <?php foreach ($categories as $cat): ?>
                  <option value="<?php echo $cat['category_id']; ?>"><?php echo $cat['category_name']; ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="form-group">
              <label>Subcategory Name</label>
              <input type="text" name="subcategory_name" class="form-control" required>
            </div>
            <button type="submit" name="addSubcategoryBtn" class="btn btn-primary">Add Subcategory</button>
          </form>
        </div>
      </div>
      <div class="row mt-4">
        <div class="col-md-12">
          <h3>Categories & Subcategories</h3>
          <ul class="list-group">
            <?php foreach ($categories as $cat): ?>
              <li class="list-group-item">
                <form class="form-inline w-100" action="core/handleForms.php" method="POST" style="display: flex;">
                  <input type="hidden" name="category_id" value="<?php echo $cat['category_id']; ?>">
                  <span class="category-name font-weight-bold flex-grow-1" style="display:inline;"><?php echo htmlspecialchars($cat['category_name']); ?></span>
                  <input type="text" name="category_name" class="form-control form-control-sm flex-grow-1 mr-2" value="<?php echo htmlspecialchars($cat['category_name']); ?>" style="display:none; max-width:250px;">
                  <div class="ml-auto">
                    <button type="button" class="btn btn-sm btn-warning edit-btn">Edit</button>
                    <button type="submit" name="editCategoryBtn" class="btn btn-sm btn-success save-btn" style="display:none;">Save</button>
                    <button type="button" class="btn btn-sm btn-secondary cancel-btn" style="display:none;">Cancel</button>
                    <a href="core/handleForms.php?deleteCategory=<?php echo $cat['category_id']; ?>" class="btn btn-sm btn-danger ml-2 delete-btn">Delete</a>
                  </div>
                </form>
                <?php $subcats = $categoryObj->getSubcategories($cat['category_id']); ?>
                <?php if ($subcats): ?>
                  <ul class="list-group mt-2">
                    <?php foreach ($subcats as $sub): ?>
                      <li class="list-group-item d-flex justify-content-between align-items-center pl-5">
                        <form class="form-inline w-100" action="core/handleForms.php" method="POST" style="display: flex;">
                          <input type="hidden" name="subcategory_id" value="<?php echo $sub['subcategory_id']; ?>">
                          <span class="subcategory-name flex-grow-1" style="display:inline;"><?php echo htmlspecialchars($sub['subcategory_name']); ?></span>
                          <input type="text" name="subcategory_name" class="form-control form-control-sm flex-grow-1 mr-2" value="<?php echo htmlspecialchars($sub['subcategory_name']); ?>" style="display:none; max-width:250px;">
                          <div class="ml-auto">
                            <button type="button" class="btn btn-sm btn-warning edit-btn">Edit</button>
                            <button type="submit" name="editSubcategoryBtn" class="btn btn-sm btn-success save-btn" style="display:none;">Save</button>
                            <button type="button" class="btn btn-sm btn-secondary cancel-btn" style="display:none;">Cancel</button>
                            <a href="core/handleForms.php?deleteSubcategory=<?php echo $sub['subcategory_id']; ?>" class="btn btn-sm btn-danger ml-2 delete-btn">Delete</a>
                          </div>
                        </form>
                      </li>
                    <?php endforeach; ?>
                  </ul>
                <?php endif; ?>
              </li>
            <?php endforeach; ?>
          </ul>
        </div>
      </div>
    </div>
    <script>
    $(function() {
      $('.edit-btn').click(function() {
        var $form = $(this).closest('form');
        $form.find('span').hide();
        $form.find('input[type="text"]').show().focus();
        $form.find('.edit-btn, .delete-btn').hide();
        $form.find('.save-btn, .cancel-btn').show();
      });
      $('.cancel-btn').click(function() {
        var $form = $(this).closest('form');
        $form.find('input[type="text"]').hide();
        $form.find('span').show();
        $form.find('.edit-btn, .delete-btn').show();
        $form.find('.save-btn, .cancel-btn').hide();
        // Reset input value to original
        $form.find('input[type="text"]').val($form.find('span').text());
      });
    });
    </script>
  </body>
</html>