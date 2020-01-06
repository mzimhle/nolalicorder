<div class="header"><!-- header -->
	<div class="top_bar"><!-- top_bar -->
		<div class="min_top_bar" style="background: linear-gradient(to right,#005c8f 0%,#004983 100%);"><!-- min_top_bar -->
			<div class="container">
				<div class="top_nav"><!-- top_nav -->
					<ul>
						{if !isset($activeParticipant)}
						<li><a href="#">Welcome, Guest </a></li>
						{else}
						<li><a href="#">Welcome, {$activeParticipant.participant_name}</a></li>
						<li><a href="/logout.php">Logout</a></li>
						{/if}
					</ul>
				</div><!-- // top_nav -->
				<div class="social_icon"><!-- social_icon -->				
					{if $activeAccount.account_social_facebook neq ''}
					<span><a href="http://facebook.com/{$activeAccount.account_social_facebook}" target="_blank"><i class="fa fa-facebook"></i></a></span>
					{/if}
					{if $activeAccount.account_social_twitter neq ''}
					<span><a href="https://twitter.com/{$activeAccount.account_social_twitter}" target="_blank"><i class="fa fa-twitter"></i></a></span>
					{/if}
					{if $activeAccount.account_social_instagram neq ''}
					<span><a href="https://instagram.com/{$activeAccount.account_social_instagram}" target="_blank"><i class="fa fa-instagram"></i></a></span>
					{/if}
					{if $activeAccount.account_social_linkedin neq ''}
					<span><a href="https://linkedin.com/company/{$activeAccount.account_social_linkedin}" target="_blank"><i class="fa fa-linkedin"></i></a></span>
					{/if}			
				</div><!-- // social_icon -->
			</div>
		</div><!-- // min_top_bar -->
	</div><!-- // top_bar -->
	<div class="main_header"><!-- main_header -->
		<div class="container">
			<div class="logo_ads"><!-- logo_ads -->
				<div class="logo"><!-- logo -->
					<!-- <h3>logo</h3> -->
					<a href="/"><img src="/images/logo.png" alt="{$activeAccount.account_name}" title="{$activeAccount.account_name}" class="hz_post hz_thumb_post" width="80%" /></a>
				</div><!-- // logo -->
				<!-- ads_block -->
				<div class="ads_block">
					<img src="/images/banner_people.jpg" width="50%" alt="ads">
				</div><!-- // ads_block -->
			</div><!-- // logo_ads -->
		</div>
	</div><!-- // main_header -->
</div><!-- End header -->