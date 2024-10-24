<div class="calendar-box property">
    <?php
    // Get the full month name
    $monthName = date("F", mktime(0, 0, 0, $thismonth, 10)); // Returns 'October
    ?>
    <div class="property_tab_headers"><?php echo $monthName . ' ' . $thisyear; ?></div>
    <div class="tab_header"></div>
    <div class="calendar">
        <?php
        $myweek = array();
        // how many days of this current month 
        $daysinmonth = intval(date('t', $unixmonth));

        // Get the current day and month (this will simulate the current date)
        $currentDay = date('j'); // Current day of the month (1-31)
        $currentMonth = date('n'); // Current month (1-12)
        $currentYear = date('Y'); // Current year
        
        // Unix timestamp for the start of the current month
        $currentMonthUnix = mktime(0, 0, 0, $currentMonth, 1, $currentYear);


        // If the current month equals the loop month, apply special logic
        if ($unixmonth == $currentMonthUnix) {
            // Adjust the starting day if the current day is greater than 10
            if ($currentDay > 10) {
                // Start 5 days before the current day or from the 1st if less than 5
                $startDay = max(1, $currentDay - 5);
            } else {
                // If the day is 10 or less, start from day 1
                $startDay = 1;
            }
        } else {
            // For other months, start from day 1
            $startDay = 1;
        }


        for ($day = $startDay; $day <= $daysinmonth; ++$day) {
            $timestamp = strtotime($day . '-' . $thismonth . '-' . $thisyear) . ' | ';
            $timestamp_java = strtotime($day . '-' . $thismonth . '-' . $thisyear);

            // Create a timestamp
            $formattedDatetimestamp = strtotime("$day-$thismonth-$thisyear");
            // Format the date to '01-Feb-24'
            $formattedDate = date("d-M-y", $formattedDatetimestamp);

            $dayname = date_i18n('D', $timestamp_java);

            $has_past_class = '';
            if ($timestamp_java < (time() - 24 * 60 * 60)) {
                $has_past_class = "has_past";
            } else {
                $has_past_class = "has_future";
            }

            $is_reserved = 0;
            $reservation_class = '';
            $isTodayClass = '';
            if ($day == gmdate('j', current_time('timestamp')) && $thismonth == gmdate('m', current_time('timestamp')) && $thisyear == gmdate('Y', current_time('timestamp'))) {
                $isTodayClass = 'calendar-today ';
            } else {
                $isTodayClass = 'calendar-free ';
            }
            ?>
            <div class="<?php esc_attr_e($isTodayClass, '') ?> calendar_pad <?php esc_attr_e($has_past_class); ?>"
                data-curent-date="<?php esc_attr_e($timestamp_java); ?>">

                <div class="dayname"><?php esc_html_e($formattedDate, '') ?></div>

                <!-- <div class="daydate"><?php // esc_html_e($day, '') ?></div> -->
            </div>
            <?php
        }

        ?>
    </div> <!-- calendar -->
</div> <!-- calendar-box -->