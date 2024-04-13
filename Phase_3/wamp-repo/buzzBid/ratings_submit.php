<?php
include('lib/common.php');
// form submitted with POST method from ratings_view.php when rate button is clicked
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['rate'])) {
    $ratedBy = mysqli_real_escape_string($db, $_POST['rated_by']);
    $itemId = mysqli_real_escape_string($db, $_POST['item_ID']);
    $rating = mysqli_real_escape_string($db, $_POST['stars']);
    $comment = mysqli_real_escape_string($db, $_POST['rating_comment']);
    $rateDateTime = mysqli_real_escape_string($db, $_POST['rate_date_time']);
    $query = "INSERT INTO ItemRating (rated_by, item_ID, rate_date_time, stars, rating_comment) VALUES ('$ratedBy', $itemId, '$rateDateTime', $rating, '$comment')";
    mysqli_query($db, $query);
    // Redirect back to ratings_view.php to reflect the changes
    header("Location: ratings_view.php?itemID=$itemId");
    exit;
}

