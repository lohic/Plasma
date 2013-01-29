<div id="header">
    <a href="./"><img src="../graphisme/logo_full.gif" alt="Sciences-Po" width="310" height="90"/></a>
    <h1><a href="./">/ plasma</a></h1>

    <?php if($core->isAdmin){  ?>
    <form id="logout_form" action="./" method="post">
        <input name="logout" type="hidden" value="true" />
        <input type="submit" name="logout_btn" id="logout_btn" value="se déconnecter du compte <?php echo $core->user_info->login ;?>" />
    </form>
    <!--<form id="select_group" action="" method="post">
    	<label for="id_actual_group">Vous utilisez le groupe : </label>        
        <?php echo createSelect($core->user_info->groups,	'id_actual_group', 	$_SESSION['id_actual_group'], 	"onchange=\"$('#select_group').submit();\"", false ); ?>    	
    </form>-->
    <?php } ?>
</div>