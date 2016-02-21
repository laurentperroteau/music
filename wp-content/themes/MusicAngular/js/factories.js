app.factory('WpApi', function ($http) {

    var getAlbums = function( postId ) {

        var param = postId === undefined ? '?filter[posts_per_page]=-1': '/'+ postId;

        return $http({
            url: location.origin + '/wp-json/wp/v2/posts'+ param, 
            method: 'GET'
        });
    };

    var getImage = function( imageId ) {

        var param = imageId === undefined ? '': '/'+ imageId;

        return $http({
            url: location.origin + '/wp-json/wp/v2/media'+ param, 
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