<li<?php if(empty($info['id'])){ ?> class="new" id="'+timestamp+'"<?php } ?>>
<a href="#" class="del"><img src="../graphisme/round_minus.png" alt="supprimer un slide" height="16"/></a>
<img src="<?php echo $iconeURL; ?>" width="28" height="18" class="icone" />
<input type="hidden" name="id_rel[]" value="<?php echo !empty($info['id'])?$info['id']:''; ?>" class="id_rel" />
<input type="hidden" name="timestamp[]" value="<?php if(empty($info['id'])){ ?>'+timestamp+'<?php } ?>" />
<span> durée : <input name="duree[]" type="text" value="<?php echo !empty($duree) ? $duree : '00:30:00'; ?>" class="dureeslide"/></span>
<?php if(!empty($is_freq)){ ?>
<!-- FREQ LISTE -->
<input type="hidden" name="typerel[]" value="freq" />
<input type="hidden" name="date[]" value="" />
<input type="hidden" name="time[]" value="" />
<span><?php echo $MSelect.$JSelect.$jSelect; ?></span>
<span>horaire : <input name="H[]" type="text" value="<?php echo !empty($json)?$json->H:''; ?>" class="timeslide"/></span>
<?php } else if(!empty($is_date)){ ?>
<!-- DATE LISTE -->
<input type="hidden" name="typerel[]"	value="date" />
<input type="hidden" name="M[]" value="" />
<input type="hidden" name="J[]" value="" />
<input type="hidden" name="j[]" value="" />
<input type="hidden" name="H[]" value="" />
<span>date : <input name="date[]" type="text" value="<?php echo !empty($date)?$date:date("Y-m-d"); ?>" class="dateslide"/></span>
<span>horaire : <input type="text" name="time[]" value="<?php echo !empty($time) ? $time : '12:00:00'; ?>" class="timeslide" /></span>
<?php } else if(!empty($is_flux)){ ?>
<!-- FLUX LISTE -->
<input type="hidden" name="typerel[]" value="flux" />
<input type="hidden" name="M[]" value="" />
<input type="hidden" name="J[]" value="" />
<input type="hidden" name="j[]" value="" />
<input type="hidden" name="H[]" value="" />
<input type="hidden" name="date[]" value="" />
<input type="hidden" name="time[]" value="" />
<?php } ?>
<span>
<a href="../slideshow/?slide_id=<?php echo !empty($info['id_slide'])?$info['id_slide']:'';?>&preview" target="_blank" class="preview">
 <img src="../graphisme/eye.png" alt="voir"/></a>
</span>
<input type="hidden" name="ordre[]"		value="<?php echo !empty($info['ordre'])?$info['ordre']:''; ?>" />
<!-- SELECTEUR DE SLIDES -->
<input type="hidden" value="<?php echo !empty($info['id_slide'])?$info['id_slide']:$ecran->get_form_select()->default; ?>" name="id_slide[]" class="id_slide"/>
 <a class="slidelistselect<?php echo !empty($info['nom'])?'':' empty';?>"><?php echo !empty($info['nom'])? ''.$info['nom']:'&nbsp;choisir&nbsp;'; ?></a>
 <?php //echo $info['id_slide'];?>
</li>