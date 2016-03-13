var app = angular.module('musicApp', [], function( 
    $locationProvider 
){
    $locationProvider.html5Mode({
        enabled: true,
        requireBase: false
    });
});
