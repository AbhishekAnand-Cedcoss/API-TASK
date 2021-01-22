<?php

get_header();
?>
<center>
<label for="checkpintext"> <?php esc_attr_e( 'PIN' ) ?></label>
<input type="text" name="checkpintext" id="checkpintext" placeholder="Enter PIN Here"><br><br>

<label for="checkpinbutton"></label>
<input type="button" name="checkpinbutton" id="checkpinbutton" value="Check_Availability">
</center>

<?php
get_footer();
?>