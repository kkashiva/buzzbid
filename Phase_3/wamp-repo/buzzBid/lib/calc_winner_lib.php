<?php

function calc_auction_winners($caller){
    
if (!empty($_SESSION['lastResultTime'])) {
    $lastResultTime = $_SESSION['lastResultTime'];
    $currentTime = new DateTime();
    $minutes = abs($currentTime->getTimestamp() - $lastResultTime->getTimestamp()) / 60;
   // $minutes = 2;
    if ($minutes < 1) {
        $url = empty($caller) ? 'main_menu.php' : $caller;
        header('Location: ' .$url);                                               
    }
}
else{
    $_SESSION['caller'] = $caller;
    header("Location: calc_winners.php");
}
}
