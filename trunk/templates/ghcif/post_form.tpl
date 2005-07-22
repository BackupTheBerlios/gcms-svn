<div class="comment" style="margin-top:10px;">
<form action="{FORM_ACTION}" method="post">
<!-- BEGIN switch_nopost -->
sie haben bereits in den letzten 5 minuten geschrieben
<!-- END switch_nopost -->
<!-- BEGIN switch_confirmation -->
<div class="cell_l">bestaetigen</div>
<div class="cell_r" style="text-align:right; padding:3px;"><input type="checkbox" name="deltrue" class="gfield" value="true" style="width:80%;" /></div>
<div class="cell_r" style="text-align:right; padding:5px 3px;"><input type="submit" name="submit" value="bestaetigen" class="gbutton" /></div>
<!-- END switch_confirmation -->
<!-- BEGIN switch_anoncomment -->
<div class="cell_l">name</div>
<div class="cell_r" style="text-align:right; padding:3px;"><input type="text" name="name" class="gfield" value="{TEMP_NAME}" style="width:80%;" /></div>
<div class="cell_l">email</div>
<div class="cell_r" style="text-align:right; padding:3px;"><input type="text" name="email" class="gfield" value="{TEMP_EMAIL}" style="width:80%;" /></div>
<!-- END switch_anoncomment -->
<!-- BEGIN switch_addposting -->
<div class="cell_l">bereich</div>
<div class="cell_r" style="text-align:right; padding:3px;"><select name="title" class="gfield" style="width:82%; font-size:8px;">{TEMP_SECTIONOPTIONS}</select></div>
<div class="cell_l">titel</div>
<div class="cell_r" style="text-align:right; padding:3px;"><input type="text" name="title" class="gfield" value="{TEMP_TITLE}" style="width:80%;" /></div>
<!-- END switch_addposting -->
<!-- BEGIN switch_posttrue -->
<div class="cell_l">text</div>
<div class="cell_r" style="text-align:right; padding:3px;"><textarea name="text" class="gfield" rows="10" cols="50" style="width:80%; height:100px;">{TEMP_TEXT}</textarea></div>
<!-- END switch_posttrue -->
<!-- BEGIN switch_addsource -->
<div class="cell_l">quelle</div>
<div class="cell_r" style="text-align:right; padding:3px;"><input type="text" name="source" class="gfield" value="{TEMP_SOURCE}" style="width:80%;" /></div>
<!-- END switch_addsource -->
<!-- BEGIN switch_posttrue -->
<div class="cell_r" style="text-align:right; padding:5px 3px;"><input type="submit" name="submit" value="senden" class="gbutton" /></div>
<!-- END switch_posttrue -->
<div class="post" style="text-align:center;"><a href="{LINK_BACK}">zurueck</a></div>
</form>
</div>
