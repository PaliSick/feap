<?php
require_once 'passwordhash.class.php';


$t_hasher = new PasswordHash(8, FALSE);
//$hash = $t_hasher->HashPassword('admin');

				if ($t_hasher->CheckPassword('admin', '$2a$08$F1xn0CZFXcILA3EFMM821eMdZqwW2v6/MjmmjYF.2FqjLx48mUuUu')) {
					$_SESSION['d_id'] = $row['id'];
			 		echo 'bien entra';
				} else {
					echo ' mal no entra';
				}

?>
