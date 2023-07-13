<?php
function getWeekday($date)
{
    $wday = date('w', strtotime($date));
    $wdays = ['日', '月', '火', '水', '木', '金', '土'];
    return $wdays[$wday];
}
?>
