<!-- TOP BAR -->
	<div id="top-bar">
		
		<div class="page-full-width cf">

			<ul id="nav" class="fl">
	
				<!--<li class="v-sep"><a href="javascript:void(0);" onclick="javascript:window.open('shortcuts.html','myNewWinsr','width=600,height=110,toolbar=0,menubar=no,status=no,resizable=yes,location=no,directories=no,scrollbars=yes');" class="round button dark ic-info image-left">Show Shortcuts</a></li>-->
				<li class="v-sep"><a href="#" class="round button dark menu-user "><strong><?php echo $POSNIC['username'] ?></strong></a>
					<ul>
				
						<li><a href="change_password.php">Changer Mot de passe</a></li>
						
						<li><a href="logout.php">Log out</a></li>
					</ul> 
				</li>
				
			
				
			</ul> <!-- end nav -->

			<div class="fr">
				<a href="update_details.php" class="round button dark menu-settings ">Parametres</a>
				<a href="logout.php" class="round button dark menu-logoff ">Se deconnecter</a>
			</div>

		</div> <!-- end full-width -->	
	
	</div> <!-- end top-bar -->