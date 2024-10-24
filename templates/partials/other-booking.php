<?php
$end_reservation = 1;
$reservation_class = ' tt ' . $timestamp_java . '  start_reservationxxxx ' . $start_reservation . '-' . $end_reservation . '  $prev_timestamp_java=' . $prev_timestamp_java;

if ($start_reservation == 1) {
    $reservation_class = ' start_reservation x2';
    $start_reservation = 0;
}

if (is_numeric($reservation_array[$timestamp_java]) != 0) {
    $booking_type_class = ' allinone_internal_booking ';
    $end_reservation_class = ' end_allinone_internal_booking q1';
} else {
    $booking_type_class = ' allinone_external_booking ';
    $end_reservation_class = ' end_allinone_external_booking q2';
}

// get previous time stamp - and check if the value is diffrent

if (
    isset($reservation_array[$prev_timestamp_java]) &&
    $reservation_array[$prev_timestamp_java] !== $reservation_array[$timestamp_java]
) {
    $reservation_class .= ' start_reservation end_reservation';
}



// other days booking 
?>
<div class="calendar-reserved calendar_pad <?php echo esc_attr($has_past_class . $reservation_class . $booking_type_class); ?>"
    data-curent-id="<?php echo esc_attr($post_id); ?>" data-current-date="<?php echo esc_attr($timestamp_java); ?>">
    <?php echo esc_html($day) . wpestate_draw_reservation_allinone($reservation_array[$timestamp_java], esc_attr($timestamp_java)); ?>
</div>

