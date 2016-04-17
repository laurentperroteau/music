
/**
 * Wordpress API Service: return post and media
 * ============================================
 */
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