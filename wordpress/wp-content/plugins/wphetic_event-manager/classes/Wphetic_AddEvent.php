<?php


class Wphetic_AddEvent {

	private $styles;

	public function __construct() {
		$this->styles = get_terms( [
			'taxonomy'   => 'style',
			'hide_empty' => false
		] );
	}

	public function generateForm() {
		ob_start();
		?>
        <form action="<?= admin_url( 'admin-post.php' ); ?>" method="post" enctype="multipart/form-data"
              class="p-5 border rounded m-auto w-75">
            <h4 class="mb-5 text-center">Enregistrer un nouvel évènement</h4>

			<?php if ( ! empty( get_query_var( 'message' ) ) ) : ?>
                <div class="alert alert-danger" role="alert">
					<?= esc_html( get_query_var( 'message' ) ); ?>
                </div>
			<?php endif; ?>

            <div class="mb-3">
                <label for="event_title" class="form-label">Titre de l'évènement</label>
                <input type="text" name="event_title" id="event_title" class="form-control">
            </div>
            <div class="mb-3">
                <label for="event_description" class="form-label">Déscription</label>
                <textarea name="event_description" id="event_description" class="form-control"></textarea>
            </div>
            <div class="mb-3">
                <label for="event_price" class="form-label">Prix</label>
                <div class="input-group">
                    <input type="number" name="event_price" id="event_price" class="form-control">
                    <span class="input-group-text" id="basic-addon2">€</span>
                </div>
            </div>
            <div class="mb-3">
                <label for="event_style" class="form-label">Style</label>
                <select name="event_style" id="event_style" class="form-select" aria-label="Default select example">
					<?php foreach ( $this->styles as $style ) : ?>
                        <option value="<?= $style->term_id; ?>"><?= $style->name; ?></option>
					<?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="event_thumbnail" class="form-label">Miniature</label>
                <input type="file" name="event_thumbnail" id="event_thumbnail" multiple="false" class="form-control"/>
            </div>
            <input type="hidden" name="action" value="wphetic_event_post">
			<?php wp_nonce_field( 'wphetic_event_post', 'wphetic_event_nonce' ); ?>

            <button type="submit" class="btn btn-primary mt-3 w-100">Poster</button>
        </form>
		<?php

		return ob_get_clean();
	}

	public static function handleForm() {
		if ( current_user_can( 'manage_events' )
		     && wp_verify_nonce( $_POST['wphetic_event_nonce'], 'wphetic_event_post' ) ) {

			$post_args = array(
				'post_title'   => $_POST['event_title'],
				'post_content' => $_POST['event_description'],
				'post_type'    => 'event',
				'post_status'  => 'publish',
				'post_author'  => get_current_user_id(),
				'tax_input'    => [
					'style' => [ $_POST['event_style'] ]
				],
				'meta_input'   => [
					'event_prix' => $_POST['event_price']
				]
			);

			$postId        = wp_insert_post( $post_args );
			$attachment_id = media_handle_upload( 'event_thumbnail', $postId );
			set_post_thumbnail( $postId, $attachment_id );
			wp_redirect( home_url( '?p=' . $postId ) );
		} elseif ( !is_user_logged_in() ) {
			wp_redirect( $_POST['_wp_http_referer'] . '?message=Vous n\'êtes pas connecté' );
		} else {
			wp_redirect( $_POST['_wp_http_referer'] . '?message=Erreur inconnue' );
		}
		exit();

	}

}