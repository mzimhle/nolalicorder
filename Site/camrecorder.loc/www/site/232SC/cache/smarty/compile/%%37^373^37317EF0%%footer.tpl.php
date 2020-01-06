<?php /* Smarty version 2.6.20, created on 2019-08-27 14:02:50
         compiled from includes/footer.tpl */ ?>
<div id="footer" class="footer container-fulid"><!-- footer -->
	<div class="copyright"><!-- copyright -->
		<div class="container">
			<div class="social_icon"><!-- social_icon -->
					<?php if ($this->_tpl_vars['activeAccount']['account_social_facebook'] != ''): ?>
					<span><a href="http://facebook.com/<?php echo $this->_tpl_vars['activeAccount']['account_social_facebook']; ?>
" target="_blank"><i class="fa fa-facebook"></i></a></span>
					<?php endif; ?>
					<?php if ($this->_tpl_vars['activeAccount']['account_social_twitter'] != ''): ?>
					<span><a href="https://twitter.com/<?php echo $this->_tpl_vars['activeAccount']['account_social_twitter']; ?>
" target="_blank"><i class="fa fa-twitter"></i></a></span>
					<?php endif; ?>
					<?php if ($this->_tpl_vars['activeAccount']['account_social_instagram'] != ''): ?>
					<span><a href="https://instagram.com/<?php echo $this->_tpl_vars['activeAccount']['account_social_instagram']; ?>
" target="_blank"><i class="fa fa-instagram"></i></a></span>
					<?php endif; ?>
					<?php if ($this->_tpl_vars['activeAccount']['account_social_linkedin'] != ''): ?>
					<span><a href="https://linkedin.com/company/<?php echo $this->_tpl_vars['activeAccount']['account_social_linkedin']; ?>
" target="_blank"><i class="fa fa-linkedin"></i></a></span>
					<?php endif; ?>
			</div><!-- // social_icon -->
			<p>Copyrights © 2018 All Rights Reserved by <a href="/"><?php echo $this->_tpl_vars['activeAccount']['account_name']; ?>
</a></p>
		</div>
	</div><!-- // copyright -->
</div><!-- // footer -->