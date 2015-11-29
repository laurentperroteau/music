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

    <style>
        .wp-playlist { display: none }
        audio {
            background-color: red;
            width: 100%;
            box-shadow: 5px 5px 5px yellow;
            border: 15px solid black;
            font-size: 20px;
        }
    </style>

    <div ng-controller="TestApi as test">

        {{ test.post.title.rendered }}
        
        <div ng-bind-html="test.content"></div>

        <button ng-click="test.play()">Play</button>
        <button ng-click="test.pause()">Pause</button>
        <button ng-click="test.next( 367 )">Next</button>

        <!--<audio controls="controls" audioplayer>
          <source type="audio/mpeg" ng-src="{{ test.currentSong }}"/>
        </audio>-->

        <img src="{{ test.image }}" imageonload="newImageLoaded()" />

        <ul>
            <li ng-repeat="post in test.posts">
                {{ post.title.rendered }}
            </li>
        </ul>
        <div id="ctrlAudio"></div>
    </div>


    Pour récupérer le media, voir _links.http://api.w.org/featuredmedia[0].href
<?php get_footer(); ?>