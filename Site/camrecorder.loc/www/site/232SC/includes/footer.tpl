<div id="footer" class="footer container-fulid"><!-- footer -->
	<div class="copyright"><!-- copyright -->
		<div class="container">
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
			<p>Copyrights © 2018 All Rights Reserved by <a href="/">{$activeAccount.account_name}</a></p>
		</div>
	</div><!-- // copyright -->
</div><!-- // footer -->