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
    _this.bgiApplyFilter = true;
    _this.bgTransition = 500;
    _this.wait = true;

    var waitLoadImage = 0;
    var waitLoadImageInterval = null;


    WpApi.albums().then( function (response) {

        _this.posts = response.data;
        getFirstPost();
    });

    function getFirstPost() {

        displayPost( _this.posts[0].id );

        _this.itemSelect = _this.posts[0];
    }

    function displayPost( id ) {

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

        aContent = angular.element( sContent );

        angular.forEach(aContent, function(elems, key) {

            if( elems.nodeType === 1 ) {

                if( elems.querySelector('.wp-playlist-script') ) {

                    var sData = elems.querySelector('.wp-playlist-script').innerHTML;
                    
                    var oData = JSON.parse( sData );

                    _this.currentAudio = oData.tracks[0].src;

                    _this.setAndPlay();
                }
            }
        });
    }


    _this.setAndPlay = function() {

        if( _this.audioElement === null ) {

            _this.audioElement = $document[0].createElement('audio');
            _this.audioElement.setAttribute('controls',true)

            document.getElementById('ctrlAudio').appendChild(_this.audioElement);

        }

        audio.setAndPlay( _this.audioElement, _this.currentAudio );

        $timeout(function() {

            _this.wait = false;
        }, _this.bgTransition);
    };

    _this.play = function() {

        audio.play( _this.audioElement );
    };

    _this.pause = function() {

        if( _this.audioElement === null ) return false;

        audio.pause( _this.audioElement );
    };

    _this.updateSelect = function() {

        _this.wait = true;

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

            if( waitLoadImage < iTrans ) {

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