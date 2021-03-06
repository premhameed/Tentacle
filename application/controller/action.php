<?php

load::helper( 'data_properties' );

class action_controller extends properties {
	
	/**
	* Cut it Out
	* ----------------------------------------------------------------------------------------------*/
	public function index ()
	{	
		echo 'No direct access';
	}
	
	
	/**
	* Agree to terms
	* ----------------------------------------------------------------------------------------------*/
	public function agree ()
	{
		$update_agree = $this->options_model()->update( 'is_agree', 'true' );

		url::redirect('admin/dashboard');
	}
	
	/**
	 * 
	 * 
	 * 
	 * ========================= Log-in/Out
	 * 
	 * 
	 * 
	 * 
	 */
	
	
	/**
	* Login
	* ----------------------------------------------------------------------------------------------*/
	public function login ()
	{
		$username = input::post( 'username' );
	    $password = input::post( 'password' );
		$history = input::post( 'history' );

	    user::login($username, $password);

        $user_data = user::get($username);
        $attempt = $user_data->data['login_attempt'];

	    if(user::valid() and $attempt !== 3 and $attempt <= 3):
            user::update($username)
                ->data('login_attempt', 0 )
                ->save();

            if ($history != '')
				url::redirect($history);
			else
				url::redirect('admin/dashboard');
		else:
            if( array_key_exists( 'login_attempt', $user_data->data ) )
                $attempt = $attempt + 1;
            else
                # Move into less than three strikes.
                $attempt = '1';

            user::update($username)
                ->data('login_attempt', $attempt )
                ->save();

            if ( $attempt >= 3):
                $locked = $this->email_model()
                    ->locked_account( $user_data, 'Your account has been disabled for security reasons.' );

                note::set("error","login",'Your account has been disabled for security reasons.');

                url::redirect('admin/logout');
            else:
                note::set("error","login",'Password Error');
                url::redirect('admin/index');
            endif;
	    endif;
	}
	

	/**
	* Lost Password
	* ----------------------------------------------------------------------------------------------*/
	public function lost()
	{
 		$username = input::post('username');

		if ( strstr( $username, '@', true) ) {
			$user = $this->user_table()
                ->select('*')
                ->where('email','=',$username)
                ->execute();
		} else {
			$user = $this->user_table()
                ->select('*')
                ->where('username','=',$username)
                ->execute();
		}
		           
	    if ( isset($user[0]->email)) 
		{
	        // Generate a Hash from the users IP
            $locked = $this->email_model()->lost( $user, 'Recover your password.' );

			note::set("success","sent_message",'An email has been sent with instructions.');

			url::redirect('admin');
			
	    } else {
	        echo 'No match, Set an error message';
	    }
	

	} // END Function Action Login


	/**
	* Activate account
	* ----------------------------------------------------------------------------------------------*/
	public function activate( $hash='' )
    {
		$user_details = $this->user_model()->get_hash( $hash );
		
		if ($user_details != false) {
			user::update($user_details->email)
			           ->data('activation_key','')
					   ->data('status','')
			           ->save();
			note::set('success','sent_message','Your email has been confirmed.');

			url::redirect( 'admin' );
		} else {
			url::redirect( );
		}
	}


	/**
	* Upgrade Tentacle
	* ----------------------------------------------------------------------------------------------*/
	public function do_core_upgrade()
	{
		$core_update = $this->serpent_model()->get_core();
		
		// Make sure some one did not rewquest this URL directly.
		if ( is::update( TENTACLE_VERSION, $core_update->version ) )
		{
			// Download and update Core Files.
            upgrade::core( $core_update->download );

			// Migrate forward on the Database.
			upgrade_db();

		} else {
			note::set('success','upgrade_message','There was nothing to upgrade.');
		}
		
		url::redirect( 'admin/updated/' );
	}

	public function do_db_upgrade()
	{
		upgrade_db();
		
		note::set('success','upgrade_message','Tentacles Database has been updated.');
		
		url::redirect( 'admin/updated' );
	}


	/**
	* Set password from hash
	* ----------------------------------------------------------------------------------------------*/
	public function set_password( )
	{	
		$user_details = $this->user_model()->set_password( );

		user::update( $user_details->email )
		           ->data('activation_key','')
				   ->data('status','')
		           ->save();

        $user_data = user::get( $user_details->email );

       if( array_key_exists( 'login_attempt', $user_data->data ) )
       {
           user::update( $user_details->email )
               ->data('login_attempt', 0 )
               ->save();
       }

		note::set('success','sent_message','Your password has been reset.');
		
		url::redirect( 'admin' );
	}
	

	/**
	* Check for Unique user name
	* ----------------------------------------------------------------------------------------------*/
	public function unique_user ()
	{
		return $this->user_model()->unique( $_POST['username'] );
	}


	/**
	 * 
	 * 
	 * 
	 * ========================= Load Scaffold
	 * 
	 * 
	 * 
	 * 
	 */
	
	/**
	* Render Admin
	* ----------------------------------------------------------------------------------------------*/
	public function render_admin ( $location, $template = ''/*, $id = null */ )
	{
		if ( $template == 'default' ):
			$delete = session::delete ( 'template' );
		else:
			if ( session::get( 'template' ) ):
				session::update( 'template', $template );
			else:
				session::set( 'template', $template);
			endif;
		endif;
		
		url::redirect( 'admin/content_'.$location.'/' );
	}


	/**
	 * 
	 * 
	 * 
	 * ========================= Pages
	 * 
	 * 
	 * 
	 * 
	 */

	/**
	* Add Page
	* ----------------------------------------------------------------------------------------------*/
 	public function add_page ()
 	{
		tentacle::valid_user();

		$page_single = $this->content_model()
            ->type('page')
            ->add( );
			
		// Delete the selected template from the session once the Page has been posted.
		session::delete ( 'template' );

    event::trigger('add_page');
  	url::redirect( 'admin/content_update_page/'.$page_single );
	}
	

	/**
	* Update Page
	* ----------------------------------------------------------------------------------------------*/	
	public function update_page ( $page_id )
 	{
		tentacle::valid_user();

		$page_single = $this->content_model()
            ->type('page')
            ->update( $page_id );
		
		/*
		$post_tags = input::post( 'tags' );
		$tag = load::model( 'tags' );
		$tag_relations = $tag->relations( $post_single, $post_tags, true );
		*/
		
		session::delete ( 'template' );

    event::trigger('update_page');
		url::redirect( input::post( 'history' ) );
	}


	/**
	* Delete User
	* ----------------------------------------------------------------------------------------------*/	
	public function delete_page ()
 	{
	
	}
	

	/**
	* Trash User
	* ----------------------------------------------------------------------------------------------*/	
	public function trash_page ( $id )
 	{
		$this->post_table()
            ->update( array(
			    'status'=>'trash'
		    ))
			->where( 'id', '=', $id )
			->execute();
			
		note::set( 'success','page_soft_delete','Moved to the trash.' );

    event::trigger('trash_page');
		url::redirect( 'admin/content_manage_pages' );
	}
	
	/**
	 * 
	 * 
	 * 
	 * ========================= Posts
	 * 
	 * 
	 * 
	 * 
	 */
	
	/**
	* Add Post
	* ----------------------------------------------------------------------------------------------*/
 	public function add_post ()
 	{
		tentacle::valid_user();
			
		$post_single = $this->content_model()->type( 'post' )->add( );
		
		$post_categories = input::post( 'post_category' );

        foreach ( $post_categories as $post_category ):
            $category_relations = $this->category_model()
                ->relations( $post_single, $post_category );
        endforeach;

            $post_tags = input::post( 'tags' );
            $post_tags = explode(',', $post_tags );

            foreach ( $post_tags as $post_tag ):
                $tag_single = $this->tag_model()->add( $post_tag );

                $tag_relations = $this->tag_model()->relations( $post_single, $tag_single );
        endforeach;

        event::trigger('add_post');
		url::redirect( 'admin/content_update_post/'.$post_single );
	}	
	
	
	/**
	* Update User
	* ----------------------------------------------------------------------------------------------*/
	public function update_post ( $post_id )
 	{
		tentacle::valid_user();

		$post_single = $this->content_model()
            ->type( 'post' )
            ->update( $post_id );

    $post_categories    = input::post( 'post_category' );

    # This clears all relations ( for tags as well )
    $delete_relations   = $this->category_model()->delete_relations( $post_id );

    foreach ( $post_categories as $post_category )
        $category_relations = $this->category_model()->relations( $post_id, $post_category );

		$post_tags = input::post( 'tags' );
		$post_tags = explode(',', $post_tags );

		foreach ( $post_tags as $tag ) {
			$tag_single     = $this->tag_model()->add( $tag );
			$tag_relations  = $this->tag_model()->relations( $post_id, $tag_single );
		}

    event::trigger('update_post');
		url::redirect( input::post( 'history' ) );
	}
	

	/**
	* Delete Post
	* ----------------------------------------------------------------------------------------------*/	
	public function delete_post ()
 	{
	
	}
	

	/**
	* Trash Post
	* ----------------------------------------------------------------------------------------------*/	
	public function trash_post ( $id )
 	{
        $this->content_model()->trash( $id );
		note::set('success','post_soft_delete','Moved to the trash.');

        event::trigger('trash_post');
		url::redirect( 'admin/content_manage_posts' );
	}
	
	/**
	 * 
	 * 
	 * 
	 * ========================= Users
	 * 
	 * 
	 * 
	 * 
	 */
	
	
	/**
	* Add User
	* ----------------------------------------------------------------------------------------------*/
	public function add_user ()
	{
		tentacle::valid_user();
				
		$user_single = $this->user_model()->add();

		if (input::post( 'send_password' ) == 'yes'):

			$user_name    = input::post( 'user_name' );
			$password     = input::post( 'password' );
			$email        = input::post( 'email' );
		
			$first_name   = input::post( 'first_name' );
			$last_name    = input::post( 'last_name' );
	
			load::helper('email');

			$hashed_ip = sha1( $_SERVER['REMOTE_ADDR'].time() );
			
			user::update($user_name)
			           ->data('activation_key',$hashed_ip)
					   ->data('status','inactive')
			           ->save();
		
			if ($password == ''):
				$message = '<p>Hello '.$first_name.' '.$last_name.',<br />Here are your account details.</p>
							<p><strong>Username</strong>: '.$user_name.'<br />
							<p><strong>Click the link to create a password.</strong><br /> '.BASE_URL.'admin/set_password/'.$hashed_ip.'</p>
							<strong>From:</strong> <a href="'.BASE_URL.'admin/">'.BASE_URL.'admin/</a>';
			else:
				$message = '<p>Hello '.$first_name.' '.$last_name.',<br />Here are your account details.</p>
							<p><strong>Username</strong>: '.$user_name.'<br />
							<strong>Password</strong>: '.$password.'</p>
							<p><strong>Click the link to activate your account.</strong><br /> '.BASE_URL.'action/activate/'.$hashed_ip.'</p>
							<strong>From:</strong> <a href="'.BASE_URL.'admin/">'.BASE_URL.'admin/</a>';
            endif;

			$user_email = $this->email_model()->send( 'Welcome to Tentacle CMS', $message, $email );
		endif;
		
		$history = input::post( 'history' );
		
		url::redirect('admin/users_manage/');
	}


	/**
	* Update User
	* ----------------------------------------------------------------------------------------------*/	
	public function update_user ( )
	{
		tentacle::valid_user();
		$user_single = $this->user_model()->update();
		url::redirect( input::post( 'history' ) );
	}
	

	/**
	* Delete User
	* ----------------------------------------------------------------------------------------------*/
	public function delete_user ( $id = '' )
	{
		tentacle::valid_user();
		
		$confirm = input::post('delete_user');
		
		if ( $confirm == 'delete' )
			$user_delete = $this->user_model()->delete( $id );

		url::redirect('admin/users_manage/');
	}
	
	
	/**
	 * 
	 * 
	 * 
	 * ========================= Plugin Activation
	 * 
	 * 
	 * 
	 * 
	 */	
	
	
	public function enable_plugin( $slug )
	{
		$activate = $this->plugin_model()->activate( $slug );
		url::redirect('admin/settings_plugins/');
	}
	
	
	public function disable_plugin( $slug )
	{
		$deactivate = $this->plugin_model()->deactivate( $slug );
		url::redirect('admin/settings_plugins/');
	}
	
	
	/**
	 * 
	 * 
	 * 
	 * ========================= Snippets
	 * 
	 * 
	 * 
	 * 
	 */
	
	
	/**
	* Add Snippet
	* ----------------------------------------------------------------------------------------------*/
	public function add_snippet ()
	{	
		tentacle::valid_user();
		$snippet_single = $this->snippet_model()->add( );
		url::redirect('admin/snippets_manage/');
	}


	/**
	* Update Snippet
	* ----------------------------------------------------------------------------------------------*/	
	public function update_snippet ( $id )
	{
		tentacle::valid_user();
		$snippet_single = $this->snippet_model()->update( $id  );
		url::redirect('admin/snippets_manage/');
	}
	

	/**
	* Delete Snippet
	* ----------------------------------------------------------------------------------------------*/
	public function delete_snippet ( $id = '' )
	{
		tentacle::valid_user();
		$snippet_delete = $this->snippet_model()->delete( $id );
		url::redirect('admin/snippets_manage/');
	}
	
	
	/**
	 * 
	 * 
	 * 
	 * ========================= Categories
	 * 
	 * 
	 * 
	 * 
	 */
	
	
	/**
	* Add Category
	* ----------------------------------------------------------------------------------------------*/
 	public function add_category ()
 	{	
		tentacle::valid_user();	
 		$category_single = $this->category_model()->add( );
 		url::redirect('admin/content_manage_categories/');
 	}


	/**
	* Update Category
	* ----------------------------------------------------------------------------------------------*/ 	
 	public function update_category ( $id ) 
 	{
		tentacle::valid_user();
		$category_single = $this->category_model()->update( $id  );
		url::redirect('admin/content_manage_categories/');
 	}
 

	/**
	* Delete Category
	* ----------------------------------------------------------------------------------------------*/	
 	public function delete_category ( $id ) 
 	{
		tentacle::valid_user();
 		$category_delete = $this->category_model()->delete( $id );
 		url::redirect('admin/content_manage_categories/');
  	}


	/**
	 * 
	 * 
	 * 
	 * ========================= Settings
	 * 
	 * 
	 * 
	 * 
	 */
	
	
	/**
	* Update Settings
	* ----------------------------------------------------------------------------------------------*/
 	public function update_settings ( $key, $value, $autoload = 'yes' )
	{
    tentacle::valid_user();
    $update_appearance = $this->options_model()->update( $key, $value, $autoload );
		url::redirect('admin/settings_appearance');
	}
	
	
	/**
	* Update Settings Post
	* ----------------------------------------------------------------------------------------------*/
	public function udpate_settings_post ( )
	{
		$autoload = 'yes';
		$keys = array_keys( $_POST );
		$values = array_values( $_POST );

		for (
		     reset($keys), 
		     reset($values);
		     list(, $key ) = each( $keys ) ,
		     list(, $value ) = each( $values );
		):
			if ( $key != 'submit' && $key != 'history') 
				$update_settings = $this->options_model()->update( $key, $value, $autoload );
		endfor;

		$history = input::post( 'history' );
		url::redirect($history);
	}


    /**
     *
     *
     *
     * ========================= Media
     *
     *
     *
     *
     */

	public function upload_media()
	{
		$upload_dir = STORAGE_DIR.'/images/';

		if (!is_dir($upload_dir))
			exit_status('Folder path does not exist');

		$allowed_ext = array('jpg','jpeg','png','gif');

		if(strtolower($_SERVER['REQUEST_METHOD']) != 'post')
			exit_status('Error! Wrong HTTP method!');

		if(array_key_exists('pic',$_FILES) && $_FILES['pic']['error'] == 0 ):
			$pic = $_FILES['pic'];

			if(!in_array(get_extension($pic['name']),$allowed_ext))
				exit_status('Only '.implode(',',$allowed_ext).' files are allowed!');

			// Move the uploaded file from the temporary 
			// directory to the uploads folder:

			$image = $pic['name'];

			$file_meta = string_to_parts($image);

			if(move_uploaded_file($pic['tmp_name'], $upload_dir.$file_meta['name'])):
				$add_image = $this->media_model()->add( $file_meta['name'] );
				
				load::helper('image');
                chmod(IMAGE_DIR.$file_meta['name'], 0664);
				process_image($file_meta['name'], TRUE);

				echo json_encode('true');
			else:
				exit_status('Error! Unable to move the file.');
			endif;
    endif;
	}


	public function insert_media( $id )
	{
		$update = $this->media_model()->update( $id );
		
		$title				    = input::post('title');
		$alt_text				= input::post('alt_text');
		$caption				= input::post('caption');
		$link_url				= input::post('link_url');

		$filename				= input::post('filename');
		$extension				= input::post('extension');
		
    $size		          	= input::post('image_size');
		
		if ( $size == 'full' )
			$image_size			= '';
		else
			$image_size			= '_'.$size;

		$image_url				= IMAGE_URL.$filename.$image_size.'.'.$extension;
		
		if ( $link_url == '' ) {
            $html = '<img src="'.$image_url.'" alt="'.$alt_text.'" title="'.$title.'"  />';
        } else {
            $html = '<a href="'.$link_url.'"><img src="'.$image_url.'" alt="'.$alt_text.'" title="'.$title.'" /></a>';
        }
		
		header("Content-type: text/html"); ?>
<script type="text/javascript">
/* <![CDATA[ */
var win = window.dialogArguments || opener || parent || top;
win.send_to_editor('<?=$html?>');
/* ]]> */
</script>
<?	}
	

	public function update_media( $id )
	{
    	$update = $this->media_model()->update( $id );

		url::redirect( input::post( 'history' ) );
	}

	
	public function delete_media()
	{
		
	}
	
	
	public function trash_media()
	{
		
	}
	

    public function import_wordpress ()
    {
        tentacle::valid_user();

        if (!is_dir(TEMP)) {
            if (!mkdir(TEMP, 0755, true)) {
                die('Failed to create folders...');
            }
        }

        if ($_FILES["xml_file"]["error"] > 0):
            note::set("error","import",'You must choose a WordPress WXR file to upload.');
            url::redirect( input::post( 'history' ) );
        elseif (!file_exists( TEMP. $_FILES["xml_file"]["name"] )):
            note::set("success","import",'You have succsssfully uploaded '.$_FILES["xml_file"]["name"]);
            move_uploaded_file($_FILES["xml_file"]["tmp_name"], TEMP.$_FILES["xml_file"]["name"]);
        endif;

        load::library('import', 'wordpress');
        load::helper('image');

        $wordpress_xml = TEMP.$_FILES["xml_file"]["name"];

        $parser = new WXR_Parser();
        $import = $parser->parse( $wordpress_xml );

        # import new categories
        foreach ($import['categories'] as $import_category )
            $this->category_model()->add($import_category);

        # import new tags
        foreach ($import['tags'] as $import_tag )
            $this->tag_model()->add($import_tag);

        # Only work with post content, we don't want pages, file attachments, or empty posts.
        foreach ($import['posts'] as $import_post ):
            if ($import_post['post_type'] == 'post' && $import_post['post_content'] != ''):
                # This is  the base media upload URL from the old WordPress site.
                $regexp_url = preg_quote($import['base_url'].'/wp-content/uploads/', "/");

                # This will return all URL matches as $media
                preg_match_all("/{$regexp_url}([^\.\!,\?;\"\'<>\(\)\[\]\{\}\s\t ]+)\.([a-zA-Z0-9]+)/",
                    $import_post['post_content'],
                    $remote_media);

                $content_modified = null;

                foreach ($remote_media[0] as $matched_url):
                    $url_parts = string_to_parts($matched_url);

                    # download a copy of the old content ( because it might be sized differently than our newly processed images.
                    $content_image = get::url_contents($matched_url);
                    file_put_contents(STORAGE_DIR.'/images/'.$url_parts['name'], $content_image);

                    # Replace the old URL with our new URL
                    $content_modified = str_replace($matched_url, IMAGE_URL.$url_parts['name'], $import_post['post_content']);
                endforeach;

                if(!$content_modified == '')
                    $import_post['post_content'] = $content_modified;

                # Import the post and return the new ID
                $post_id = $this->content_model()->type( 'post' )->add_by_import( $import_post );

                # assosiate tags, and categories with the new post.
                if(array_key_exists("terms", $import_post)):
                    foreach($import_post['terms'] as $term ):
                        if ( $term['domain'] == 'post_tag' ):
                            $tag_id = $this->tag_model()->lookup($term['slug']);
                            $tag_relations = $this->tag_model()->relations( $post_id, $tag_id );
                        elseif( $term['domain'] == 'category' ):
                            $category_id = $this->category_model()->lookup($term['slug']);
                            $category_relations = $this->category_model()->relations( $post_id, $category_id );
                        endif;
                    endforeach;
                endif;
            endif;
        endforeach;

        # Bring over all images that are attachments, This is independent of any content manipulation that takes place.
        foreach ($import['posts'] as $import_post ):
            if ( $import_post['post_type'] == 'attachment' ):
                $url_parts = string_to_parts($import_post['attachment_url']);

                $attachment_image = get::url_contents($import_post['attachment_url']);

                if (!file_exists(STORAGE_DIR.'/images/'.$url_parts['name'])):
                    file_put_contents(STORAGE_DIR.'/images/'.$url_parts['name'], $attachment_image);
                    $add_image = $this->media_model()->add( $url_parts['name'] );
                    process_image( $url_parts['name'] );
                endif;
            endif;
        endforeach;

        note::set("success","import",'Your content has been imported successfully.');

        url::redirect( input::post( 'history' ) );
    }


	public function editor_file_upload()
	{
        tentacle::valid_user();

        copy($_FILES['file']['tmp_name'], STORAGE_DIR.'/files/'.$_FILES['file']['name']);

		$array = array(
			'filelink' => STORAGE_URL.'/files/'.$_FILES['file']['name'],
			'filename' => $_FILES['file']['name']
		);

		echo stripslashes(json_encode($array));
	}
	

	public function editor_save()
	{
		echo 'saved';
	}


    public function generate_sitemap()
    {
        $posts = $this->content_model()->type( 'post' )->get_sitemap( );

        tentacle::generate_sitemap($posts);

        note::set('success','sent_message','Generated sitemap.xml');

        url::redirect( 'admin/settings_seo' );
    }
	
	/**
	 * 
	 * 
	 * 
	 * ========================= Install Process
	 * 
	 * 
	 * 
	 * 
	 */	
	

	/**
	* Admin
	* ----------------------------------------------------------------------------------------------*/
	public function admin()
	{
		load::config('db');
		
		$config = config::get('db');

		$user_name    = input::post( 'user_name' );
		$password     = input::post( 'password' );
		$email        = input::post( 'email' );

		$first_name   = input::post( 'first_name' );
		$last_name    = input::post( 'last_name' );
		$display_name = input::post( 'display_name' );

        set::option('admin_email', $email);

        $hash_password = new PasswordHash(8, FALSE);
        $encrypted_password = $hash_password->HashPassword($password );
		
		$registered = time();
		$hashed_ip = sha1($_SERVER['REMOTE_ADDR'].$registered);
		$hash_address = BASE_URL.'admin/activate/'.$hashed_ip;

		$pdo = new pdo("{$config['default']['driver']}:dbname={$config['default']['database']};host={$config['default']['host']}",$config['default']['username'],$config['default']['password']);

		$build = $pdo->exec( "INSERT INTO `users` (`email`, `username`, `password`, `type`, `data`, `registered`, `status`)
								VALUES
									('$email', '$user_name', '$encrypted_password', 'administrator', '{\"first_name\":\"$first_name\",\"last_name\":\"$last_name\",\"activity_key\":\"$hashed_ip\",\"url\":\"\",\"display_name\":\"$display_name\",\"editor\":\"wysiwyg\"}', '$registered', 1)" );
		
		
		if (input::post( 'send_password' ) == 'yes'):
			load::helper('email');

			$hashed_ip = sha1($_SERVER['REMOTE_ADDR'].time());
			$hash_address = BASE_URL.'admin/activate/'.$hashed_ip;

			$message = '<p>Hello '.$first_name.' '.$last_name.',<br />Here are your account details.</p>
						<p><strong>Username</strong>: '.$user_name.'<br />
						<strong>Password</strong>: '.$password.'</p>
						<a href="'.BASE_URL.'admin/">'.BASE_URL.'admin/</a>';

			$user_email = $this->email_model()->send( 'Welcome to Tentacle CMS', $message, $email );
		endif;

		url::redirect('install/done');
	}
}
