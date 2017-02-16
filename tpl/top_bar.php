<!-- TOP BAR -->
	<div id="top-bar">
		
		<div class="page-full-width cf">

			<ul id="nav" class="fl">
	
				<!--<li class="v-sep"><a href="javascript:void(0);" onclick="javascript:window.open('shortcuts.html','myNewWinsr','width=600,height=110,toolbar=0,menubar=no,status=no,resizable=yes,location=no,directories=no,scrollbars=yes');" class="round button dark ic-info image-left">Show Shortcuts</a></li>-->
				<li class="v-sep"><a href="#" class="round button dark menu-user image-left"><strong><?php echo $POSNIC['username'] ?></strong></a>
					<ul>
				
						<li><a href="change_password.php">Changer Mot de passe</a></li>
						
						<li><a href="logout.php">Log out</a></li>
					</ul> 
				</li>
			<li><a href="update_details.php" class="round button dark menu-settings image-left">Changer informations du Magasin</a></li>
				<li><a href="logout.php" class="round button dark menu-logoff image-left">Se deconnecter</a></li>
				
			</ul> <!-- end nav -->

					
			<form action="#" method="POST" id="search-form" class="fr">
				<fieldset>
					<input type="text" id="search-keyword" class="round button dark ic-search image-right" placeholder="Rechercher ..." />
					<input type="hidden" value="SUBMIT" />
				</fieldset>
			</form>

		</div> <!-- end full-width -->	
	
	</div> <!-- end top-bar -->