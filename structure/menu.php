<?php

if($core->isAdmin && $core->userLevel<=3){
	
$selectClass = '';

function selectClass($ref=NULL){
	if(isset($_GET['page']) && isset($ref) ){
		if($_GET['page'] == $ref){
			echo 'select';	
		}else{
			echo '';	
		}
	}else{
		echo '';
	}
}



/*
news_select
news_send
news_create
actu_select
actu_create
contact
template_select
template_create
admin
*/

?>
<div id="menuHaut">
  <ul id="menuDown">
  		
        <li id=""><a href="./?page=ecrans" class="<?php selectClass('ecrans'); ?>"><span>Écrans</span></a>
        	<ul>
            	<li id=""><a href="./?page=ecran_create" class="<?php selectClass('ecran_create'); ?>"><span>Créer un écran</span></a></li>
				<li id=""><a href="./?page=ecrans_groupe_modif" class="<?php selectClass('ecrans_groupe_create'); ?>"><span>Créer un groupe</span></a></li>
            </ul>
  		</li>
        <!--<li id=""><a href="./?page=playlist_select" class="<?php selectClass('playlist_select'); ?>"><span>Playlist</span></a>
            <ul>
       			<li id=""><a href="./?page=playlist_create" class="<?php selectClass('playlist_create'); ?>"><span>Créer</span></a></li>
            </ul>	
        </li>-->
        <li id=""><a href="./?page=slides_select" class="<?php selectClass('slides_select'); ?>"><span>Slides</span></a>
            <ul>
            	<li id=""><a href="./?page=slide_create" class="<?php selectClass('slide_create'); ?>"><span>Créer</span></a></li>
            </ul>
        </li>
       
        <!--<li id=""><a href="./?page=options" class="<?php selectClass('options'); ?>"><span>Options</span></a>
            <ul>-->
            	<li id=""><a href="./?page=etablissements" class="<?php selectClass('etablissements'); ?>"><span>Établissements</span></a></li>
            <!--</ul>-->
		</li>
		<li id=""><a href="./?page=comptes" class="<?php selectClass('comptes'); ?>"><span>Comptes</span></a>
       		<ul>
           		<li id=""><a href="./?page=organismes" class="<?php selectClass('organismes'); ?>"><span>Organismes</span></a></li>
        	</ul>
	</li>
    </ul>
    <div class="reset"></div>
</div>
<hr class="reset" />

<?php } ?>
