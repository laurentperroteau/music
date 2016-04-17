/**
 * Trigger function in attribut on image loader
 * ============================================
 */
app.directive('imageonload', function() {
    return {
        restrict: 'A',
        link: function(scope, element, attrs) {
            element.bind('load', function() {
                scope.$apply( attrs.imageonload );
            });
        }
    };
});


/**
 * HTML5 audio element
 * ===================
 */
app.directive('mAudio', function() {
    return {
        restrict: 'E',
        replace: true,
        template: '<audio controls="true" pause="{{ pause }}" class="audio" src="{{ src }}" audio-end=""></audio>',
        scope: {
            mSrc: '=', // pass song
            mPause: '=', // true to pause song
            mAudioEnd: '&' // function triggered on end of song
        },
        link: function(scope, element, attrs) {
            
            var nElem = element[0];
            
            scope.$watch('mSrc', function( newSong ) {
                
                setAndPlay( newSong );
            });

            scope.$watch('mPause', function( bPause ) {
                
                if( bPause ) {
                    pause();
                }
                else {
                    play();
                }
            });

            function setAndPlay( filename ) {
                nElem.src = filename;
                nElem.play();
            }

            function play() {
                nElem.play(); 
            }

            function pause() {
                nElem.pause(); 
            }

            // Launch function on the end of song
            nElem.addEventListener('ended', scope.mAudioEnd);
        }
    };
});


/*
<mybutton 
    text="coucou text" 
    value="test.coucou" 
    my-click="test.action( 'coucou depuis html' )" />

app.directive('mybutton', function() {
    return {
        restrict: 'E',
        replace: true, // remplace l'élément, si false est inséré
        template: '<button type="button" ng-click="triggerClick( sMsg )" myTrigger>{{ text }}</button>',
        scope: {
            text: '@',
            value: '=', // value on js var
            triggerClick: '&myClick', // function, ie: write attribut like attribute, no camelCase
        },
        link: function(scope, element, attrs) {

            // console.log( element );            
            // console.log(  attrs );            
        }
    };
});

<mybuttontransclude>
    <span class="inner">Mon deuxième bouton</span>
</mybuttontransclude>
app.directive('mybuttontransclude', function() {
    return {
        restrict: 'E',
        transclude: true, // permet d'insérer le contenu de la directive dans l'élément
        template: '<button type="button" ng-transclude></button>'
    };
});
*/