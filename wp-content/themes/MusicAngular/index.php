<?php get_header(); ?>

    <style>
        .wp-playlist { display: none }
        audio {
            background-color: red;
            width: 100%;
            box-shadow: 5px 5px 5px yellow;
            border: 15px solid black;
            font-size: 20px;
        }
        .wrapper {
            width: 100%;
            height: 100%;
        }
        .bgi {
            position: fixed;
            left: 0;
            top: 0;
            z-index: -1;
            width: 100%;
            height: 100%;
            -webkit-filter: brightness(1);
            -moz-filter: brightness(1);
            filter: brightness(1);
            -webkit-transition: -webkit-filter 500ms linear;
            -moz-transition: -moz-filter 500ms linear;
        }
        .bgi.ctrlApplyFilter {
            -webkit-filter: brightness(10);
            -moz-filter: brightness(10);
        }
    </style>

    <div ng-controller="MusicApi as music" class="wrapper">

        <img
            style="display: none"
            src="{{ music.image }}" 
            imageonload="newImageLoaded()" />

        <div 
            ng-style="music.imageBackground"
            class="bgi"
            ng-class="{'ctrlApplyFilter': music.bgiApplyFilter}"></div>
            
        <button ng-click="music.play()">Play</button>
        <button ng-click="music.pause()">Pause</button>

        <div class="content">
            <select
                ng-model="music.itemSelect"
                ng-options="post as post.title.rendered for post in music.posts track by post.id"
                ng-change="music.updateSelect()">
            </select>
            
            <div ng-bind-html="music.content"></div>

            <div id="ctrlAudio"></div>
        </div>
    </div>


<?php get_footer(); ?>