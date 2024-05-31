<?php
// Simulate processing delay
sleep(3); // Delay for 3 seconds

// Redirect to fake processing page
header("Location: processing_fake.php");
exit; // Ensure no further code is executed after redirection
?>
