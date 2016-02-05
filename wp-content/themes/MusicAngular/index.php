<?php get_header(); ?>

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