<? load::view('admin/partials/header', array('title' => 'Media settings', 'assets' => array('application')));?>

<div id="wrap">
	<h1 class='title'><img src="<?=ADMIN_URL;?>images/icons/icon_pages_32.png" alt="" /> Media settings</h1>
	<form action="<?= BASE_URL ?>action/udpate_settings_post/" method="post" class="form-stacked">
		<input type="hidden" name="history" value="<?= CURRENT_PAGE ?>"/>
		<div class="row">
			<div class="col-md-6">
				<h2>Image sizes</h2>
				<hr />
				<p>
					The sizes listed below determine the maximum dimensions in pixels to use when inserting an image into the body of a post.
				</p>
				<fieldset>
					<div class="clearfix">
						<label>Thumbnail size</label>
						<div class="input">
							<div class="inline-inputs">
								<input type="text" value="<?= get::option('image_thumb_size_w'); ?>" name="image_thumb_size_w" id="thumbnail_size_w" class='span2' />
								x
								<input type="text" value="<?= get::option('image_thumb_size_h'); ?>" name="image_thumb_size_h" id="thumbnail_size_h" class='span2' />
							</div>
						</div>
<? /*
						<div class="input">
							<ul class="inputs-list">
								<li>
									<label>
										<input type="hidden" value="0" name="thumbnail_crop">
										<input type="checkbox" value="1" name="thumbnail_crop">
										<span>Crop thumbnail to exact dimensions (normally thumbnails are proportional)</span> </label>
								</li>
							</ul>
						</div> 
*/ ?>
					</div>
					<div class="clearfix">
						<label>Medium size</label>
						<div class="input">
							<div class="inline-inputs">
								<input type="text" value="<?= get::option('image_medium_size_w'); ?>" name="image_medium_size_w" id="medium_size_w" class='span2' />
								x
								<input type="text" value="<?= get::option('image_medium_size_h'); ?>" name="image_medium_size_h" id="medium_size_h" class='span2' />
							</div>
						</div>
					</div>
					<div class="clearfix">
						<label>Large size</label>
						<div class="input">
							<div class="inline-inputs">
								<input type="text" value="<?= get::option('image_large_size_w'); ?>" name="image_large_size_w" class='span2' />
								x
								<input type="text" value="<?= get::option('image_large_size_h'); ?>" name="image_large_size_h" class='span2' />
							</div>
						</div>
					</div>
				</fieldset>
			</div>
			<div class="col-md-6">

                <h2>Embeded media sizes</h2>
                <hr />
                <p>
                    Choose a size for embeded rich media.
                </p>
                <fieldset>
                    <div class="clearfix">
                        <label>&nbsp;</label>
                        <div class="input">
                            <div class="inline-inputs">
                                <input type="text" value="<?= get::option('embed_size_w'); ?>" name="embed_size_w" id="embed_size_w" class='span2' />
                                x
                                <input type="text" value="<?= get::option('embed_size_h'); ?>" name="embed_size_h" id="embed_size_h" class='span2' />
                            </div>
                        </div>
                    </div>
                </fieldset>
<? /*
				<h2>Uploading Files</h2>
				<hr />
				<div class="clearfix">
					<label for='upload_path'>Store uploads in this folder</label>
					<div class="input">
						<input type="text" class="regular-text code" value="<?= get::option('upload_folder'); ?>" name="upload_folder" />
						<span class="help-block">Default is
							<code>
								tentacle/storage
							</code>
						</span>
					</div>
				</div>
				<div class="clearfix">
					<label for="upload_url_path">Full URL path to files</label>
					<div class="input">
						<input type="text" class="regular-text code" value="<?= get::option('upload_url'); ?>" name="upload_url">
						<span class="help-block">Configuring this is optional. By default, it should be blank.</span>
					</div>
					<div class="input">
						<ul class="inputs-list">
							<li>
								<label for="uploads_use_yearmonth_folders">
									<input type="hidden" value="0" name="upload_organize">
									<input type="checkbox" value="1" name="upload_organize">
									<span>Organize my uploads into month- and year-based folders</span>
								</label>
							</li>
						</ul>
					</div>
				</div>
*/ ?>
			</div>
		</div>
		<div class="row">
			<div class="actions">
                <button class="btn btn-primary" type="submit">
                    Save Changes
                </button>
			</div>
		</div>
	</form>
</div><!-- #wrap -->
<? load::view('admin/partials/footer', array( 'assets' => array( '' ) ) ); ?>