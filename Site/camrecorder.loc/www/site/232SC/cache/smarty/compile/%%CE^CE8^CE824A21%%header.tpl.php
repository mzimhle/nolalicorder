<?php /* Smarty version 2.6.20, created on 2019-08-27 14:02:50
         compiled from includes/header.tpl */ ?>
<div class="header"><!-- header -->
	<div class="top_bar"><!-- top_bar -->
		<div class="min_top_bar" style="background: linear-gradient(to right,#005c8f 0%,#004983 100%);"><!-- min_top_bar -->
			<div class="container">
				<div class="top_nav"><!-- top_nav -->
					<ul>
						<?php if (! isset ( $this->_tpl_vars['activeParticipant'] )): ?>
						<li><a href="#">Welcome, Guest </a></li>
						<?php else: ?>
						<li><a href="#">Welcome, <?php echo $this->_tpl_vars['activeParticipant']['participant_name']; ?>
</a></li>
						<li><a href="/logout.php">Logout</a></li>
						<?php endif; ?>
					</ul>
				</div><!-- // top_nav -->
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
			</div>
		</div><!-- // min_top_bar -->
	</div><!-- // top_bar -->
	<div class="main_header"><!-- main_header -->
		<div class="container">
			<div class="logo_ads"><!-- logo_ads -->
				<div class="logo"><!-- logo -->
					<!-- <h3>logo</h3> -->
					<a href="/"><img src="/images/logo.png" alt="<?php echo $this->_tpl_vars['activeAccount']['account_name']; ?>
" title="<?php echo $this->_tpl_vars['activeAccount']['account_name']; ?>
" class="hz_post hz_thumb_post" width="80%" /></a>
				</div><!-- // logo -->
				<!-- ads_block -->
				<div class="ads_block">
					<img src="/images/banner_people.jpg" width="50%" alt="ads">
				</div><!-- // ads_block -->
			</div><!-- // logo_ads -->
		</div>
	</div><!-- // main_header -->
</div><!-- End header -->