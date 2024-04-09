<?php
include('lib/common.php');
// form submitted with POST method from ratings_view.php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
    $ratedBy = mysqli_real_escape_string($db, $_POST['rated_by']);
    $itemId = mysqli_real_escape_string($db, $_POST['item_ID']);
    $query = "DELETE FROM ItemRating WHERE rated_by = '$ratedBy' AND item_ID = $itemId";
    mysqli_query($db, $query);
    // Redirect back to ratings_view.php to reflect the changes
    header("Location: ratings_view.php?itemID=$itemId");
    exit;
}
