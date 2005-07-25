<div class="moduleheader">{MOD_TITLE}</div>				

<div class="post">
<h2>{SECTION} <span style="font-size:8px;">::</span> <a href="{LINK_COMMENTS}">{TITLE}</a></h2>
<div class="meta">{AUTHORLINE}{ADMINISTRATE}</div>
<div class="storycontent">

{CONTENT}

</div>
<div class="feedback">quelle &raquo; {SOURCE}</div>
</div><br />

<div class="post">
<h2>kommentare</h2>
<div class="meta">{COUNT_COMMENTS}</div>
					
<div class="storycontent">
<!-- BEGIN newscomments -->
<div class="comment">
<div class="meta">{newscomments.COMMENTNUMBER} {newscomments.COMMENTWRITER}{newscomments.ADMINISTRATE}</div>

<div class="storycontent">
			
{newscomments.COMMENT}
			
</div>
</div><br />
<!-- END newscomments -->
<!-- BEGIN switch_nocomments -->
<div class="comment" style="text-align:center;">keine kommentare</div><br />						
<!-- END switch_nocomments -->
</div>
</div><br />

{PAGINATION_CONTENT}

<div class="post">
	<h2>kommentar schreiben</h2>
	<div class="meta">erfordert die freischaltung durch einen admin</div>

{COMMENT_FORM}			
			
</div>
