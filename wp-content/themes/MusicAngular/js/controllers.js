app.controller('MusicApi', function (
    $scope,
    $sce, 
    WpApi,
    audio,
    $document,
    $timeout,
    $interval,
    $location
){

    var _this = this;

    _this.currentAudio = null;
    _this.currentId    = null;
    _this.image        = null;
    _this.imageLoaded  = false;
    _this.bgiApplyFilter = true;
    _this.bgTransition = 500;

    // Audio tag
    _this.audio       = {};
    _this.audio.src   = null;
    _this.audio.pause = false;
    _this.audio.wait  = true;

    var waitLoadImage = 0;
    var waitLoadImageInterval = null;

    // At first, get all post
    WpApi.albums().then( function(response) {

        _this.posts = response.data;
        getFirstPost();
    });

    /**
     * Get first post (after getting all post)
     * =======================================
     */
    function getFirstPost() {

        var iKeyPost = 0;

        // Check if url is the same as post
        if( $location.path() !== '' ) {

            var sCurrentPath = $location.$$absUrl;

            angular.forEach( _this.posts, function(oPost, key) {

                if( oPost.link == sCurrentPath ) {

                    iKeyPost = key;
                }
            });            
        }

        setNewPost( iKeyPost );
    }

    /**
     * Get next post to play
     * =====================
     */
    function getNextPost() {

        var iKeyPost = 0;

        angular.forEach( _this.posts, function(oPost, key) {

            if( oPost.id == _this.currentId ) {

                iKeyPost = key + 1;
                return false;
            }
        });            

        setNewPost( iKeyPost );
    }

    /**
     * Set new post
     * ============
     * @param {int} iKeyPost => key of array post
     */
    function setNewPost( iKeyPost ) {
        
        displayPost( _this.posts[ iKeyPost ].id );

        _this.itemSelect = _this.posts[ iKeyPost ];
    }

    /**
     * Display post (get content of post, image and slug)
     * =================================================
     * @param  {int} id => id to displayed
     */
    function displayPost( id ) {

        _this.currentId = id;

        WpApi.albums( id ).then( function (response) {

            _this.post = response.data;

            _this.content = $sce.trustAsHtml( _this.post.content.rendered );

            getTrackInWpContent( _this.post.content.rendered );

            _this.imageLoaded = false;

            WpApi.image( _this.post.featured_image ).then( function (response) {

                _this.image = response.data.source_url;
                _this.bgiApplyFilter = true;
            });

            $location.url( _this.post.slug );

            // Launch events
            onKeypressSpace();
        });
    }

    /**
     * Get track in json data Worspress content
     * ========================================
     * @param  {string} sContent => string html
     */
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


    /**
     * Audio events functions
     * ======================
     */
    
    _this.setAndPlay = function() {

        _this.audio.src = _this.currentAudio;

        $timeout(function() {

            _this.audio.wait = false;
        }, _this.bgTransition);
    };

    _this.play = function() {

        _this.audio.pause = false;
    };

    _this.pause = function() {

        if( _this.audio.src === null ) return false;

        _this.audio.pause = !_this.audio.pause;
    };

    _this.updateSelect = function() {

        _this.audio.wait = true;

        waitLoadImageInterval = $interval(function() {
            waitLoadImage += 100;
        }, 100);

        _this.setAndPlay();
        displayPost( _this.itemSelect.id );
    };

    _this.onAudioEnd = function() {

        if( _this.audio.src === null ) return false;

        getNextPost();
    }


    /**
     * Show image when new image loaded (and if css transition end)
     * ============================================================
     */
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


    var iPageView = null;

    /**
     * Set page view on GA
     * ===================
     */
    $scope.$on("$locationChangeStart", function() {

        if( $location.path() == '/' ) {

            iPageView = 'firstView';
        }
        else if( iPageView === null ) {

            iPageView = 'firstView';
        }
        else if( iPageView == 'firstView' ) {

            iPageView = 'endFirstView';
        }
        else {

            if (typeof ga == 'function') {
                ga('send', 'pageview', $location.path());
            }
        }
    });


    /**
     * Event function
     * ==============
     */
    
    function onKeypressSpace() {

        document.body.onkeyup = function(e) {

            if( e.keyCode == 32 ){

                _this.pause();
            }
        }
    }
});