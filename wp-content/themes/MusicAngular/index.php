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
        
        <div 
            class="content"
            ng-class="{'ctrlWait': music.wait }">
            <select
                ng-model="music.itemSelect"
                ng-options="post as post.title.rendered for post in music.posts track by post.id"
                ng-change="music.updateSelect()">
            </select>            
            <div ng-bind-html="music.content"></div>

        </div>
        <m-audio 
            m-src="music.audio.src" 
            m-pause="music.audio.false"
            m-audio-end="music.onAudioEnd()"
            class="audio" /> 
    </div>

<?php get_footer(); ?>