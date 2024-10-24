<?php
// confimr today there is booking 
if (array_key_exists($timestamp_java, $reservation_array)) {

    // enternal or external bbooking
    if (is_numeric($reservation_array[$timestamp_java]) != 0) {
        //  $booking_type_class = ' allinone_internal_booking ';
        $end_reservation_class = ' end_allinone_internal_booking q1';
    } else {
        //  $booking_type_class = ' allinone_external_booking ';
        $end_reservation_class = ' end_allinone_internal_booking q2';
    }

    $start_reservation = 0;
    $end_reservation = 1;
    $reservation_class = ' start_reservation x1-' . $start_reservation;

    // Today booing 
    ?>
    <div class="calendar-reserved calendar_pad <?php echo esc_attr($has_past_class . $reservation_class . $booking_type_class); ?>"
        data-current-date="<?php echo esc_attr($timestamp_java); ?>">
        <?php echo esc_html($day) . wpestate_draw_reservation_allinone($reservation_array[$timestamp_java], esc_attr($timestamp_java)); ?>
    </div>
    <?php

} else {
    // if today there is no booking 
    ?>
    <div class="calendar-today calendar_pad <?php echo esc_attr($has_past_class); ?>"
        data-curent-date="<?php echo esc_attr($timestamp_java); ?>">
        <?php echo esc_html($day); ?>
    </div>
    <?php
}
