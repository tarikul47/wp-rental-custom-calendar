<?php
$start_reservation = 1;
if ($end_reservation === 1) {
    $reservation_class = ' end_reservation mm' . $end_reservation_class;
    $end_reservation = 0;
}
?>
<!-- There is no booking -->
<div class="calendar-free calendar_pad <?php echo esc_attr($has_past_class . $reservation_class); ?>"
    data-curent-date="<?php echo esc_attr($timestamp_java); ?>">
    <?php echo esc_html($day); ?>
</div>

<?php $prev_timestamp_java = $timestamp_java; ?>