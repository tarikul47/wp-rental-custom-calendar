<?php

get_header();

?>

<div class=" dashboard-margin"></div>

<?php wprentals_dashboard_header_display(); ?>

<div class=" user_dashboard_panel wprentals_allinone_wrapper">
    <div class="arrow-wrapper-allinone">
        <div id="calendar-prev-internal-allinone" class=""><i class="fas fa-chevron-left"></i></div>
        <div id="calendar-next-internal-allinone" class=""><i class="fas fa-chevron-right"></i></div>
    </div>

    <?php
    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

    $args = array(
        'post_type' => 'estate_property',
        'author' => $current_user->ID,
        'posts_per_page' => 2,
        'paged' => $paged,
        'post_status' => array('publish'),
        'meta_query' => array(
            'relation' => 'OR',
            array(
                'key' => '_is_parent_apartment',
                'compare' => 'NOT EXISTS' // Excludes posts with this meta key (child posts)
            ),
            array(
                'key' => '_is_parent_apartment',
                'value' => '',
                'compare' => '=' // This also includes posts where the meta key is empty or not set
            )
        )
    );

    $prop_selection = new WP_Query($args);

    // Here we start calendar design 
    wpestate_get_calendar_allinone($prop_selection);

    // TODO: We need to work on here right now showing all apartment but we need only parent appratment because child appartment showing in parent 
    
    // Apartment pagination 
    if ($prop_selection->have_posts()):
        wprentals_pagination($prop_selection->max_num_pages, $range = 2);
    endif;
    ?>
</div>




<?php

function wpestate_get_calendar_allinone($prop_selection, $initial = true, $echo = true)
{
    global $wpdb, $m, $monthnum, $year, $wp_locale, $posts;
    $daywithpost = array();
    // week_begins = 0 stands for Sunday

    // echo "<pre>";
    // print_r($prop_selection);

    // Current time 
    $time_now = current_time('timestamp');

    // Current data return with format = 2024-10-01
    $now = date('Y-m-d');

    // object with current time 
    $date = new DateTime();

    // Month and Year digit return 
    $thismonth = gmdate('m', $time_now);
    $thisyear = gmdate('Y', $time_now);

    // Month start 1st day timestamp (with second)
    $unixmonth = mktime(0, 0, 0, $thismonth, 1, $thisyear);

    // Current month days return [ For example = 31 That mean Octobar 31 days ]
    $last_day = date('t', $unixmonth);

    $month_no = 1;

    // How many month calendar we wwant to print [ 12 month next from now  ]
    $max_month_no = intval(wprentals_get_option('wp_estate_month_no_show', ''));

    //  $max_month_no = 2;

    while ($month_no < $max_month_no) {

        wpestate_draw_month_allinone($prop_selection, $month_no, $unixmonth, $daywithpost, $thismonth, $thisyear, $last_day);

        $date->modify('first day of next month');
        $thismonth = $date->format('m');
        $thisyear = $date->format('Y');
        $unixmonth = mktime(0, 0, 0, $thismonth, 1, $thisyear);
        $month_no++;
    }

}


// 1 = loop  [ Date Column + Apartment ]

function wpestate_draw_month_allinone($prop_selection, $month_no, $unixmonth, $daywithpost, $thismonth, $thisyear, $last_day)
{
    global $wpdb, $m, $monthnum, $year, $wp_locale, $posts, $current_user;

    $week_begins = intval(get_option('start_of_week'));

    $calendar_output = '';
    $initial = true;
    $echo = true;

    $table_style = '';
    if ($month_no > 1) {
        $table_style = 'display:none;'; // No need to include `style=""` unless it's necessary.
    }
    ?>
    <div class="booking-calendar-wrapper-allinone" data-mno="<?php esc_attr_e($month_no); ?>"
        style="<?php esc_attr_e($table_style); ?>">

        <?php include MY_CALENDAR_PLUGIN_PATH . 'templates/' . 'draw-date.php'; ?>
        <?php include MY_CALENDAR_PLUGIN_PATH . 'templates/' . 'draw-apartments.php'; ?>
    </div>
    <?php
}

// 3 = loop 
function wpestate_draw_reservation_allinone($reservation_note, $current_date)
{
    if (is_numeric($reservation_note) != 0) {
        ?>
        <div class="rentals_reservation allinone_reservations"
            data-internal-reservation="<?php echo esc_attr($reservation_note); ?>">
            <?php
            $current_user = wp_get_current_user();
            $userID = $current_user->ID;

            $internal_booking_id = intval($reservation_note);

            if (!intval($internal_booking_id)) {
                exit();
            }

            // Get booking meta data
            $prop_id = get_post_meta($internal_booking_id, 'booking_id', true);
            $the_post = get_post($prop_id);

            if ($current_user->ID != $the_post->post_author) {
                exit('you don\'t have the right to see this');
            }

            // Get booking details
            $booking_from_date = get_post_meta($internal_booking_id, 'booking_from_date', true);
            $booking_to_date = get_post_meta($internal_booking_id, 'booking_to_date', true);
            $booking_guests = get_post_meta($internal_booking_id, 'booking_guests', true);
            $invoice_no = get_post_meta($internal_booking_id, 'booking_invoice_no', true);

            // Convert booking dates to timestamps for comparison
            $from_timestamp = strtotime($booking_from_date);
            $to_timestamp = strtotime($booking_to_date);

            // Check if the current date matches the booking from date
            if ($current_date == $from_timestamp) {
                // Only show booking details if it's the first day of the booking
                print '<div class="wprentals_reservation_dashboard_wrapper_modal">';
                print __('Booking id', 'wprentals') . ': ' . $internal_booking_id;
                print '<div class="allinone-booking-data">' . esc_html__('From', 'wprentals') . ' ' . wpestate_convert_dateformat_reverse($booking_from_date) . ' ' . __('To ', 'wprentals') . ' ' . wpestate_convert_dateformat_reverse($booking_to_date) . '</div>';

                print '<div class="allinone-booking-data-guests_invoice">';
                if (wprentals_get_option('wp_estate_item_rental_type') != 1) {
                    print '<div class="allinone-booking-data-guests">' . esc_html__('Guests', 'wprentals') . ': ' . esc_html($booking_guests) . '</div>';
                }
                print '<div class="allinone-booking-data-invoice">' . esc_html__('Invoice', 'wprentals') . ': ' . esc_html($invoice_no) . '</div>';
                print '</div>';

                $author_id = get_post_field('post_author', $internal_booking_id);
                $author = get_userdata($author_id);
                $username = $author->user_login;

                print '<div class="allinone-booking-data-booking_author">' . esc_html__('Booking by', 'wprentals') . ': ' . esc_html($username) . '</div>';

                print '</div>';
            } else {
                // Optionally, you can display a message or leave it blank for non-first booking days
                echo '<div class="no-booking-details">' . esc_html__('This date is booked', 'wprentals') . '</div>';
            }
            ?>
        </div>
        <?php
    }
}

wp_reset_query();
get_footer();

