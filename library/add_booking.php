<?php
// Handle completing the booking
if (isset($_POST['add_booking'])) {
    $date_borrow_from = $_POST['date_borrow_from'];
    $date_to = $_POST['date_to'];
    $status = $_POST['status'];
    $borrow_id = $_POST['borrow_id'];
    $cart = $_SESSION['cart'];

    if (empty($cart)) {
        $error = "Please add at least one book to the cart.";
    } else {
        $result = $booking->addBooking($date_borrow_from, $date_to, $status, $borrow_id, $cart);
        if (is_numeric($result)) {
            $_SESSION['cart'] = []; // Clear the cart after successful booking
            header("Location: bookings.php?success=1&booking_id=$result");
            exit();
        } else {
            $error = $result; // Display the error returned by addBooking
        }
    }
}