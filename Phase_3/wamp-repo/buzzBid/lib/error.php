 
 	<?php if($error_msg) {  ?>
		<div class='error'>
			 <div class='error_msg'>
				<?php
					foreach ($error_msg as $error) {
						echo $error . NEWLINE;
					 }
				?>
			</div>
		</div>
	<?php  } ?>
	
    