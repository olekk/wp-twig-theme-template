<?php namespace teik\Theme\Controllers;

class FormConsentsController extends Controller {
  use \teik\Theme\Traits\Singleton;

  public function hook() {
    add_action( 'wpcf7_init', array($this,'formTag') );
    add_action( 'wpcf7_admin_init', array($this,'tagGenerator'), 35 );
    add_filter( 'wpcf7_validate_gt_consent', [$this,'validation'], 10, 2 );
    add_filter( 'wpcf7_validate_gt_consent*', [$this,'validation'], 10, 2 );
    add_filter( 'wpcf7_mail_tag_replaced_gt_consent*', [$this,'replaceTag'], 20,4 );
    add_filter( 'wpcf7_mail_tag_replaced_gt_consent', [$this,'replaceTag'], 20,4 );
    // add_action('wpcf7_before_send_mail',array($this,'beforeSend'),20, 3);
    add_filter( 'wpcf7_messages', [$this,'messages'], 10, 1 );
    // add_filter('wpcf7_mail_components', [$this, 'components'], 10 ,3);
  }

  public function replaceTag($replaced, $submitted, $html, $mail_tag) {
    $consents = get_field('form_consents','option');
    $replaced = '';
    foreach ($submitted as $value) {
      $replaced .= strip_tags($consents[$value]['long_content'])."\r\n";
    }
    return $replaced;
  }

  public function components($components, $form, $mail) {
    $submission = \WPCF7_Submission::get_instance();
    $post_code = $submission->get_posted_data('post_code_valid');
    if($post_code == 'false') {
      $components['recipient'] = get_field('invalid_recipent','option');
    }
    return $components;
  }

  public function messages($messages) {
    $messages = array_merge( $messages, array(
      'gt_consent_invalid' => array(
        'description' =>
          __( "You must accept our data policy", 'contact-form-7' ),
        'default' =>
          __( "You must accept our data policy", 'contact-form-7' ),
      ),
    ) );
    return $messages;
  }

  public function formTag() {
    wpcf7_add_form_tag('gt_consent', array($this,'formTagHandler'),array( 'name-attr' => true ));
    wpcf7_add_form_tag('gt_consent*', array($this,'formTagHandler'),array( 'name-attr' => true ));
  }

  public function formTagHandler($tag) {
    $class = wpcf7_form_controls_class( $tag->type, 'wpcf7-checkbox ' );
    $validation_error = wpcf7_get_validation_error( $tag->name );

    if ( $validation_error ) {
      $class .= ' wpcf7-not-valid';
    }

    $value = (string) reset( $tag->values );
    $atts['class'] = $tag->get_class_option( $class );
    $atts['type'] = 'checkbox';
    if ( $tag->has_option( 'placeholder' ) || $tag->has_option( 'watermark' ) ) {
      $atts['placeholder'] = $value;
      $value = '';
    }
    if ( $tag->is_required() ) {
      $atts['aria-required'] = 'true';
    }
    $atts['aria-invalid'] = $validation_error ? 'true' : 'false';
    $atts['name'] = $tag->name.'[]';
    $atts = wpcf7_format_atts( $atts );

    return \Timber\Timber::compile('components/form/consent.twig', [
      'atts' => $atts,
      'tag' => $tag,
      'form' => \WPCF7_ContactForm::get_current(),
      'consents' => get_field('consents','option'),
    ]);
  }

  function tagGenerator() {
    $tag_generator = \WPCF7_TagGenerator::get_instance();
    $tag_generator->add( 'gt_consent', __( 'Zgody', 'contact-form-7' ),
      array($this,'tagGeneratorHTML') );
  }

  public function tagGeneratorHTML($contact_form, $args = '') {
    $type = $args['id'];
    \Timber\Timber::render('components/admin/consent-input.twig', $args);
  }

  public function validation($result, $tag) {
    // $consents = get_field('form_consents','option');
    // if(is_array($_POST[$tag->name])) {
    //   foreach ($consents as $key => $consent) {
    //     if($consent['is_required'] && !in_array($key,$_POST[$tag->name])) {
    //       $result->invalidate( $tag, wpcf7_get_message( 'gt_consent_invalid' ) );
    //     }
    //   }
    // }

    // if($tag->is_required() && !isset($_POST[$tag->name])) {
    //   $result->invalidate( $tag, wpcf7_get_message( 'gt_consent_invalid' ) );
    // }
    return $result;
  }

}