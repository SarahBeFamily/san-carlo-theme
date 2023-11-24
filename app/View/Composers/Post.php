<?php

namespace App\View\Composers;

use Roots\Acorn\View\Composer;

class Post extends Composer
{
    /**
     * List of views served by this composer.
     *
     * @var array
     */
    protected static $views = [
        'partials.page-header',
        'partials.content',
        'partials.content-*',
    ];

    /**
     * Data to be passed to view before rendering, but after merging.
     *
     * @return array
     */
    public function override()
    {
        return [
            'title' => $this->title(),
            'image' => $this->featured_image(),
        ];
    }

    /**
     * Returns the post title.
     *
     * @return string
     */
    public function title()
    {
        if ($this->view->name() !== 'partials.page-header') {
            return get_the_title();
        }

        if (is_home()) {
            if ($home = get_option('page_for_posts', true)) {
                return get_the_title($home);
            }

            return __('Articoli Recenti', THEMEDOMAIN);
        }

        if (is_post_type_archive('spettacoli')) {
            return __('Spettacoli ed eventi', THEMEDOMAIN);
        }

        if (is_archive()) {
            return post_type_archive_title('',false);
        }

        if (is_search()) {
            return sprintf(
                /* translators: %s is replaced with the search query */
                __('Risultati di ricerca per %s', THEMEDOMAIN),
                get_search_query()
            );
        }

        if (is_404()) {
            return __('Non trovato', THEMEDOMAIN);
        }

        return get_the_title();
    }

    /**
     * Retrieve featured image or its placeholder from options
     *
     * @return void
     */
    public function featured_image()
    {
        $featured = get_the_post_thumbnail_url( get_the_ID(), 'full' );
        $image = $featured ? $featured : get_field('title_placeholder', 'options');

        return $image;
    }
}
