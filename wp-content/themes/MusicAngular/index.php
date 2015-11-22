<?php
/**
 * Main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Twenty_Ten
 * @since Twenty Ten 1.0
 */

get_header(); ?>

    <style>.wp-playlist { display: none }</style>

    <div ng-controller="TestApi as test">

        {{ test.post.title.rendered }}
        
        <div ng-bind-html="test.content"></div>

        <!--<ul>
            <li ng-repeat="post in test.posts">
                {{ post.title.rendered }}
            </li>
        </ul>-->
    </div>


    Pour récupérer le media, voir _links.http://api.w.org/featuredmedia[0].href
<?php get_footer(); ?>