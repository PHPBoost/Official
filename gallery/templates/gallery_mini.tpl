		<div class="module-mini-container">
			<div class="module-mini-top">
				<h5 class="sub-title">{L_RANDOM_PICS}</h5>
			</div>
			<div class="module-mini-contents">
				<div style="width:{WIDTH_DIV}px;height:{HEIGHT_DIV}px;margin:auto;">
					<div style="position:relative;width:{WIDTH_DIV}px;height:{HEIGHT_DIV}px;overflow:hidden;" id="scrolling_images">
						# IF C_STATIC #
							# START pics_mini #
							<a href="{pics_mini.U_PICS}#pics_max"><img src="{pics_mini.PICS}" alt="{pics_mini.NAME}" width="{pics_mini.WIDTH}px" height="{pics_mini.HEIGHT}px" /></a>
							# END pics_mini #
						# ELSE #
						<ul id="mini-gallery-slideshow">
							# START pics_mini #
							<li><a href="{pics_mini.U_PICS}#pics_max"><img src="{pics_mini.PICS}" alt="{pics_mini.NAME}" width="{pics_mini.WIDTH}px" height="{pics_mini.HEIGHT}px" /></a></li>
							# END pics_mini #
						</ul>
						# ENDIF #
					</div>
				</div>
				<script type="text/javascript">
					var nbr_element = jQuery('#mini-gallery-slideshow li').length;
					jQuery('#mini-gallery-slideshow').css('width', nbr_element * 150);
				</script>
				<a class="small" href="{PATH_TO_ROOT}/gallery/gallery.php">{L_GALLERY}</a>
			</div>
			<div class="module-mini-bottom">
			</div>
		</div>
		