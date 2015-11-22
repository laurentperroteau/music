var app = angular.module('musicApp', []);

app.factory('WpApi', function ($http) {

    var getAlbums = function( postId ) {

        var param = postId === undefined ? '': '/'+ postId;

        return $http({
            url: 'http://music.local/wp-json/wp/v2/posts'+ param, 
            method: 'GET'
        });
    };

    return {
        albums: getAlbums
    }
});

app.controller('TestApi', function (
    $scope,
    $sce, 
    WpApi
){

    var _this = this;

    WpApi.albums().then( function (response) {

        _this.posts = response.data;

        // console.log( _this.posts );

    });


    WpApi.albums( 378 ).then( function (response) {

        _this.post = response.data;

        // console.log( _this.post );

        _this.content = $sce.trustAsHtml( _this.post.content.rendered );

        parseContent( _this.post.content.rendered );
    });

    function parseContent( sContent ) {

        $content = angular.element( sContent );
        console.log( $content );
        console.log( $content[2].querySelector('.wp-playlist-script') );
        var sData = $content[2].querySelector('.wp-playlist-script').innerHTML;

        var oData = JSON.parse( sData );

        console.log( oData.tracks[0].src );


    }
});