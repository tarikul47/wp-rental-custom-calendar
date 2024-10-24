<?php

if (!$prop_selection->have_posts()) {
    ?>
    <div>You don\'t have any properties yet!</div> <!-- TODO: We need to set section for showing this  -->
    <?php
} else {
    while ($prop_selection->have_posts()):
        $prop_selection->the_post();
        $post_id = get_the_ID();
        $link = esc_url(get_permalink());
        $title = get_the_title();
        ?>
        <div class="rooms property">
            <div class="property_tab_headers">
                <a target="_blank" href="<? esc_attr_e($link, '') ?>">
                    <?php esc_html_e($title, '') ?>
                </a> <!-- property_tab_headers -->
            </div>
            <!------------------- Room Start ------------------->
    <?php
            $parent_listing_id = get_the_ID(); // Current parent listing ID
    
            // Query to fetch child apartments
            $args = array(
                'post_type' => 'estate_property',
                'author' => $current_user->ID,
                'posts_per_page' => -1, // Retrieve all child posts
                'post_status' => 'publish',
                'meta_query' => array(
                    array(
                        'key' => '_is_parent_apartment',
                        'value' => $parent_listing_id, // The parent post ID
                        'compare' => '='
                    )
                ),
            );
            // Get child posts
            $child_posts = get_posts($args);

            // Combine parent and child IDs
            $post_ids = array_merge([$parent_listing_id], wp_list_pluck($child_posts, 'ID'));

            // Query to fetch both parent and child listings using post__in
            $combined_args = array(
                'post_type' => 'estate_property',
                'post__in' => $post_ids, // Include parent and child posts by IDs
                'orderby' => 'post__in', // Maintain the order of posts as in the array
                'posts_per_page' => -1, // Retrieve all specified posts
            );

            //$child_apartments = new WP_Query($combined_args);
            $child_apartments = new WP_Query($combined_args);
            ?>
    <div class="child-room">
        <?php
        while ($child_apartments->have_posts()):
            $child_apartments->the_post();
            $post_id = get_the_ID();
            $link = esc_url(get_permalink());
            $title = get_the_title();
            ?>
        <div class="child">
            <a target="_blank" href="<? esc_attr_e($link, '') ?>">
                <?php esc_html_e($title, '') ?>
            </a>
            <div class="calendar">
                <!-- ------------Room Wise calendar Box -->
                            <?php
                            $reservation_array = get_post_meta($post_id, 'booking_dates', true);
                            //$reservation_array = get_post_meta(42267, 'booking_dates', true);
                
                            if (!is_array($reservation_array) || $reservation_array == '') {
                                $reservation_array = array();
                            }

                            $start_reservation = '';
                            $end_reservation = '';
                            $end_reservation_class = '';
                            $reservation_class = '';

                            $prev_timestamp_java = '';



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

                                $timestamp = strtotime($day . '-' . $thismonth . '-' . $thisyear);
                                $timestamp_java = strtotime($day . '-' . $thismonth . '-' . $thisyear);

                                $dayname = date('D', $timestamp_java);

                                $has_past_class = '';

                                if ($timestamp_java < (time() - 24 * 60 * 60)) {
                                    $has_past_class = "has_past";
                                } else {
                                    $has_past_class = "has_future";
                                }


                                $is_reserved = 0;
                                $reservation_class = '';
                                $booking_type_class = '';

                                // Today checking
                                if ($day == gmdate('j', current_time('timestamp')) && $thismonth == gmdate('m', current_time('timestamp')) && $thisyear == gmdate('Y', current_time('timestamp'))) {

                                    include MY_CALENDAR_PLUGIN_PATH . 'templates/partials/' . 'today-booking.php';

                                    // Without Today checking here 
                                } else if (array_key_exists($timestamp_java, $reservation_array)) {

                                    include MY_CALENDAR_PLUGIN_PATH . 'templates/partials/' . 'other-booking.php';

                                } else {

                                    include MY_CALENDAR_PLUGIN_PATH . 'templates/partials/' . 'no-booking.php';
                                }
                            }
                            ?>
                            <!-------------- Room Wise calendar Box -->
                        </div>
                    </div>
                <?php endwhile; ?>
            </div> <!-- child-room -->

            <!---------------- Room End ------------->
</div> <!-- rooms -->
        <?php
    endwhile;
}