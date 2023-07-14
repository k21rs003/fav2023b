<?php

include ('lib/calendar.php');
include ('lib/input.php');

if (isset($_POST['year']) && isset($_POST['month']) && isset($_POST['day'])) {
    $y = $_POST['year'];
    $m = sprintf("%02d", $_POST['month']);
    $d = sprintf("%02d", $_POST['day']);
    $date = "{$y}-{$m}-{$d}"; // 年月日

    $wd = getWeekday($date);

    $firstDayOfMonth = date("w", mktime(0, 0, 0, $m, 1, $y)); // 曜日
    $lastDayOfMonth = date("t", mktime(0, 0, 0, $m, 1, $y)); // 月日数

    echo "[空き状況確認]<br>";

    $f = isset($_POST['facility']) ? $_POST['facility'] : 0;
    
    $facilityName = ($f >= 1 && $f <= 3) ? $facilities[$f] : '-';

    $holidayWdays = [0, 6]; // 土日の配列

    if ($d == 0) {
        echo "検索: {$y}年{$m}月<br>";
        echo "施設名: {$facilityName}<br>";

        echo "<br>==========<br>";
        echo "　{$y}年{$m}月<br>";
        echo "==========<br>";

        for ($day = 1; $day <= $lastDayOfMonth; $day++) {
            $currentDayWeek = date('w', mktime(0, 0, 0, $m, $day, $y));
            $wdays = ['日', '月', '火', '水', '木', '金', '土'];

            $formattedDate = sprintf("%04d-%02d-%02d", $y, $m, $day);
            $dateKey = sprintf("%02d-%02d", $m, $day);
            $holidayName = $spHoliday[$dateKey] ?? '';
            $reserveData = $facility[$dateKey] ?? '';

            $dayCount = sprintf("%02d", $day);
            echo "{$dayCount} ({$wdays[$currentDayWeek]}):";

            //施設検索

            if (in_array($holidayName, $spHoliday)) {
                echo "<br>　-name: {$holidayName}<br>";
                echo "　-type: public_holiday<br>";
            } elseif (in_array($currentDayWeek, $holidayWdays)) {
                echo "<br>　-name: 定休日<br>";
                echo "　-type: local_holiday<br>";
            } else {
                if (isset($facility[$dateKey]) && $facility[$dateKey]['name'] === $facilityName) {
                    $coreTime = $facility[$dateKey]['coreTime'];

                    //echo "　-core time:";

                    if (is_array($coreTime)) {
                        foreach ($coreTime as $time) {
                            echo " <span style='color: blue;'>{$coreTimes[$time]}</span>";
                        }
                    } else {
                        echo " <span style='color: blue;'>{$coreTimes[$coreTime]}</span>";
                    }

                    echo "<br>";
                } elseif ($facilityName === '-') {
                    if (!empty($reserveData)) {
                        $reserveName = $reserveData['name'];
                        $coreTime = $reserveData['coreTime'];
                        echo "<br>　*Reserve facility: <span style='color: blue;'>{$reserveName}</span><br>";
                        echo "　-core time: ";

                        if (is_array($coreTime)) {
                            foreach ($coreTime as $time) {
                                echo " <span style='color: blue;'>{$coreTimes[$time]}</span>";
                            }
                        } else {
                            echo " <span style='color: blue;'>{$coreTimes[$coreTime]}</span>";
                        }

                        echo "<br>";
                    } else {
                        echo "<br>";
                    }
                } else {
                    echo "<br>";
                }
            }
        }
    } else {
        echo "検索: {$y}年{$m}月{$d}日({$wd})<br>";
        echo "施設名: {$facilityName}<br>";

        echo "=============<br>";
        $currentDayWeek = date('w', mktime(0, 0, 0, $m, $d, $y));
        $dateKey = sprintf("%02d-%02d", $m, $d);
        $holidayName = $spHoliday[$dateKey] ?? '';
        $reserveData = $facility[$dateKey] ?? '';

        if (in_array($holidayName, $spHoliday)) {
            echo "-name: {$holidayName}<br>";
            echo "-type: public_holiday<br>";
        } elseif (in_array($currentDayWeek, $holidayWdays)) {
            echo "-name: 定休日<br>";
            echo "-type: local_holiday<br>";
        } else {
            if (isset($facility[$dateKey]) && $facility[$dateKey]['name'] === $facilityName) {
                $coreTime = $facility[$dateKey]['coreTime'];

                echo "core time:";

                if (is_array($coreTime)) {
                    foreach ($coreTime as $time) {
                        echo "{$coreTimes[$time]}　";
                    }
                } else {
                    echo "{$coreTimes[$coreTime]}　";
                }

                echo "<br>";
            } elseif ($facilityName === '-') {
                if (!empty($reserveData)) {
                    $reserveName = $reserveData['name'];
                    $coreTime = $reserveData['coreTime'];
                    echo "<br>　*Reserve facility: <span style='color: blue;'>{$reserveName}</span><br>";
                    echo "　-core time: ";

                    if (is_array($coreTime)) {
                        foreach ($coreTime as $time) {
                            echo " <span style='color: blue;'>{$coreTimes[$time]}</span>";
                        }
                    } else {
                        echo " <span style='color: blue;'>{$coreTimes[$coreTime]}</span>";
                    }

                    echo "<br>";
                } else {
                    echo "[予約なし]";
                }
            } else {
                echo "[予約なし]";
            }
        }
    }
}
?>
