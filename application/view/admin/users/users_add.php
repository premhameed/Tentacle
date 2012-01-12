<?	load::view('admin/template-header', array('title' => 'Add a new user', 'assets' => 'application'));?>
<?	load::view('admin/template-sidebar');?>
<div id="wrap">
	<div class="one-full">
		<h1 class='title'><img src="<?=ADMIN_URL;?>images/icons/icon_pages_32.png" alt="" /> Add a new user</h1>
		<div class="one-half">
			<?php if($note = note::get('user_add')): ?>
				<script type="text/javascript">
					$(document).ready(function() {
						jQuery.noticeAdd({
							text : '<?= $note['content'];?>',
							stay : false,
							type : '<?= $note['type']; ?>'
						});
					});
				</script>
			<?php endif;?>
			<form id='validation' action="<?= BASE_URL ?>action/add_user/" method="post">
				<fieldset>
					<div class="clearfix">
						<script type="text/javascript" charset="utf-8">

							function username_check(){
								var username = $('#username').val();
								if(username == "" || username.length < 4){
									$('#username').css('border', '3px #CCC solid');
									$('#tick').hide();
								}else{

							jQuery.ajax({
							type: "POST",
							url: "<?= BASE_URL ?>dev/username_check/",
								data: 'username='+ username,
								cache: false,
								success: function(response) {
									if(response == 1) {
										$('#username').css('border', '3px #C33 solid');
										$('#tick').hide();
										$('#cross').fadeIn();
									} else {
										$('#cross').hide();
										$('#tick').fadeIn();
									}
								}
								});
								}
								}
						</script>
						<label for="username">Username <span class="description">(required)</span></label>
						<div class="input">
							<input type="text" aria-required="true" value="" id="username_off" name="user_name">
							<img id="tick" src="http://papermashup.com/demos/check-username/tick.png" width="16" height="16"/>
							<img id="cross" src="http://papermashup.com/demos/check-username/cross.png" width="16" height="16"/>
						</div>
					</div>
					<div class="clearfix">
						<label for="email">E-mail <span class="description">(required)</span></label>
						<div class="input">
							<input type="text" value="" id="email" name="email">
						</div>
					</div>
					<div class="clearfix">
						<label for="first_name">First Name</label>
						<div class="input">
							<input type="text" value="" id="first_name" name="first_name">
						</div>
					</div>
					<div class="clearfix">
						<label for="last_name">Last Name</label>
						<div class="input">
							<input type="text" value="" id="last_name" name="last_name">
						</div>
					</div>
					<div class="clearfix">
						<label for="display_name">Display Name</label>
						<div class="input">
							<input type="text" value="" id="display_name" name="display_name">
						</div>
					</div>
					<div class="clearfix">
						<label for="url">Website</label>
						<div class="input">
							<input type="text" value="" class="code" id="url" name="url">
						</div>
					</div>
					<div class="clearfix">
						<label for="password">Password <span class="description">(twice, required)</span></label>
						<div class="input">
							<input type="password" autocomplete="off" id="password" name="password" />
						</div>
						<div class="input">
							<input type="password" autocomplete="off" id="confirm_password" name="confirm_password" />
							<span class="help-block">Hint: The password should be at least seven characters long. To make it stronger, use upper and lower case letters, numbers and symbols like ! " ? $ % ^ &amp; ).</span>
						</div>
					</div>
					<div class="clearfix">
						<label for="send_password">Send this password to the new user by email.</label>
						<div class="input">
							<input type="checkbox" id="send_password" name="send_password">
						</div>
					</div>
					<div class="clearfix">
						<label for="type" class="alignleft">Role</label>
						<div class="input">
							<select id="type" name="type">
								<option value="subscriber" selected="selected">Subscriber</option>
								<option value="administrator">Admin</option>
								<option value="editor">Editor</option>
								<option value="author">Author</option>
								<option value="contributor">Contributor</option>
							</select>
						</div>
					</div>
					<div class="clearfix">
						<label for="editor" class="alignleft">Editor</label>
						<div class="input">
							<ul class="inputs-list">
								<li>
									<label title="wysiwyg">
										<input type="radio" checked="checked" value="wysiwyg" name="editor">
										<span>WYSIWYG</span> </label>
								</li>
								<li>
									<label title="html">
										<input type="radio" value="html" name="editor">
										<span>HTML</span> </label>
								</li>
							</ul>
						</div>
					</div>
					<input type="hidden" name="history" value="<?= CURRENT_PAGE ?>"/>
				</fieldset>
				<div class="actions">
					<input type="submit" value="Save" class="btn medium primary" />
					<a href="<?=ADMIN;?>users_manage/" class="red">Cancel</a>
				</div>
			</form>
		</div>
		<div class="one-half">
			&nbsp;
		</div>
	</div>
</div>
<!-- #wrap -->
<?load::view('admin/template-footer');?>