<?php
namespace teik\Theme\Controllers;

use teik\Theme\Traits\Singleton;
use Timber\Term;
use Timber\Timber;
use DateTime;
use Timber\Post;
use Timber\PostQuery;

class BlogController extends Controller
{
  use Singleton;

  public function getPage() {
    $page = get_option('page_for_posts');
    return new Post($page);
  }

  public function getStickyPosts()
  {
    $args = [
      'post_type' => 'post',
      'posts__in' => $this->getPage()->meta('blog_featured')
    ];
    return new PostQuery($args);
  }

  public function getRelatedPosts()
  {
    $post = Timber::get_post();
    $categories = $post->categories();
    echo '<pre>'.print_r($categories,true).'</pre>';
    $cat_str = '';
    foreach ($categories as $key => $cat) {
      $cat_str .= $cat->ID;
    }

    $args = [
      'post_type' => 'post',
      'posts_per_page' => 3,
      ''
    ];

    if($post->meta('related_posts')) {
      $relatedPosts = $post->meta('related_posts');
      $currentPostKey = array_search($post->ID, $relatedPosts);
      if(is_int($currentPostKey)) {
        unset($relatedPosts[$currentPostKey]);
      }
      $args['post__in'] = $relatedPosts;
      $posts = get_posts(array_merge($args, [
        'include' => $relatedPosts,
        'fields' => 'ids',
        'numberposts' => 3
      ]));
      echo '<pre>'.print_r($posts,true).'</pre>';
    }
    return new PostQuery($args);
  }

  public function getYears()
  {
    global $wpdb;
    $dates = $wpdb->get_results(	"SELECT DISTINCT MONTH( post_date ) AS month ,
                YEAR( post_date ) AS year,
                COUNT( id ) as post_count FROM $wpdb->posts
                WHERE post_status = 'publish' and post_date <= now( )
                and post_type = 'post'
                GROUP BY month , year
                ORDER BY post_date DESC");
    $tree = [];
    foreach ($dates as $date) {
      $tree[$date->year][] = [
        'month_name' => __(DateTime::createFromFormat('m', $date->month)->format('F')),
        'month' => $date->month,
      ];
    }
    return $tree;
  }

  public function getSidebar()
  {
    return Timber::get_widgets( 'blog-sidebar' );
  }

  private function getTerms(string $taxonomy, $parent = false)
  {
    $args = [
      'taxonomy' => $taxonomy,
      'hide_empty' => false,
      'fields' => 'ids',
    ];
    if(is_int($parent)) {
      $args['parent'] = $parent;
    }
    $terms = get_terms($args);
    foreach ($terms as $term) {
      yield new Term($term);
    }
  }

  public function getCategories($parent = false)
  {
    return $this->getTerms('category', $parent);
  }

  public function getTags($parent = false)
  {
    return $this->getTerms('post_tag', $parent);
  }
}