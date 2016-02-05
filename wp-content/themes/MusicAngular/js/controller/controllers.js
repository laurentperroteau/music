var app = angular.module('musicApp', []);

app.factory('WpApi', function ($http) {

    var getAlbums = function( postId ) {

        var param = postId === undefined ? '': '/'+ postId;

        return $http({
            url: 'http://music.local/wp-json/wp/v2/posts'+ param, 
            method: 'GET'
        });
    };

    var getImage = function( imageId ) {

        var param = imageId === undefined ? '': '/'+ imageId;

        return $http({
            url: 'http://music.local/wp-json/wp/v2/media'+ param, 
            method: 'GET'
        });
    };

    return {
        albums: getAlbums,
        image: getImage
    }
});


app.factory('audio',function () {
  return {
    setAndPlay: function( audioElement, filename ) {
        audioElement.src = filename;
        audioElement.play();
    },
    play: function( audioElement ) {
        audioElement.play(); 
    },
    pause: function( audioElement ) {
        audioElement.pause(); 
    }
  }
});

app.directive('imageonload', function() {
    return {
        restrict: 'A',
        link: function(scope, element, attrs) {
            element.bind('load', function() {
                scope.$apply(attrs.imageonload);
            });
        }
    };
});


app.controller('MusicApi', function (
    $scope,
    $sce, 
    WpApi,
    audio,
    $document,
    $timeout,
    $interval
){

    var _this = this;
    _this.audioElement = null;
    _this.currentAudio = null;
    _this.image        = null;
    _this.imageLoaded = false;
    _this.bgiApplyFilter = false;
    _this.bgTransition = 500;

    var waitLoadImage = 0;
    var waitLoadImageInterval = null;


    WpApi.albums().then( function (response) {

        _this.posts = response.data;
        console.log( _this.posts );
        getFirstPost();
    });

    function getFirstPost() {
        console.log( 'getFirstPost' );

        displayPost( _this.posts[0].id );

        _this.itemSelect = _this.posts[0];
    }

    function displayPost( id ) {
        console.log( 'displayPost' );


        WpApi.albums( id ).then( function (response) {

            _this.post = response.data;

            _this.content = $sce.trustAsHtml( _this.post.content.rendered );

            getTrackInWpContent( _this.post.content.rendered );

            _this.imageLoaded = false;

            WpApi.image( _this.post.featured_image ).then( function (response) {

                _this.image = response.data.source_url;
                _this.bgiApplyFilter = true;
            });
        });
    }

    function getTrackInWpContent( sContent ) {
        console.log( 'getTrackInWpContent' );

        aContent = angular.element( sContent );

        angular.forEach(aContent, function(elems, key) {

            if( elems.nodeType === 1 ) {

                if( elems.querySelector('.wp-playlist-script') ) {

                    var sData = elems.querySelector('.wp-playlist-script').innerHTML;
                    
                    var oData = JSON.parse( sData );

                    _this.currentAudio = oData.tracks[0].src;

                    console.log( _this.currentAudio );

                    _this.setAndPlay();
                }
            }
        });
    }


    _this.setAndPlay = function() {
        console.log( 'setAndPlay' );

        if( _this.audioElement === null ) {

            _this.audioElement = $document[0].createElement('audio');
            _this.audioElement.setAttribute('controls',true)

            document.getElementById('ctrlAudio').appendChild(_this.audioElement);

            console.log( _this.audioElement, _this.currentAudio );
        }

        audio.setAndPlay( _this.audioElement, _this.currentAudio );
    };

    _this.play = function() {
        console.log( 'play' );

        audio.play( _this.audioElement );
    };

    _this.pause = function() {
        console.log( 'pause' );

        if( _this.audioElement === null ) return false;

        audio.pause( _this.audioElement );
    };

    _this.updateSelect = function() {

        console.log( 'stop sound' );

        waitLoadImageInterval = $interval(function() {
            waitLoadImage += 100;
        }, 100);

        _this.setAndPlay();
        displayPost( _this.itemSelect.id );
    };

    $scope.newImageLoaded = function() {

        /*
            Interval is setting on change and filter 
            transition is launched :
            - wait end transition to show image
            - except firt time of course
         */
        if( waitLoadImage > 0 ) {

            var iTrans = _this.bgTransition * 2;

            console.log( '----------- '+ waitLoadImage );

            if( waitLoadImage < iTrans ) {

                console.log( 'wait before load image' );
                $timeout(function() {

                    cancelWait();
                }, iTrans - waitLoadImage);
            }
            else {
                cancelWait();
            }
        }
        else {
            cancelWait();
            showImage();
        }

        
        function cancelWait() {
            $interval.cancel( waitLoadImageInterval );
            waitLoadImage = 0;
            showImage();
        }

        function showImage() {

            _this.imageLoaded = true;
            _this.imageBackground = {'background': 'url('+ _this.image +') repeat center center'};
            _this.bgiApplyFilter = false;
        }
    };
});