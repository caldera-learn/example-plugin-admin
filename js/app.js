var APP = angular.module('example', ['ui.router', 'ngResource', 'ngSanitize' ]);

APP.config(function($stateProvider, $urlRouterProvider) {
    var addOnsTemplate = document.getElementById( 'cf-addon-template' ).innerHTML;
    var settingsTemplate = document.getElementById( 'settings-template' ).innerHTML;

    $stateProvider.state({
        name: 'settings',
        url: '/settings',
        template: settingsTemplate,
        controller: 'settings'
    });

    $stateProvider.state({
        name: 'all',
        url: '/add-ons/all',
        template: addOnsTemplate,
        controller: 'addons'
    });

    $stateProvider.state({
        name: 'email',
        url: '/add-ons/email',
        template: addOnsTemplate,
        controller: 'addons'
    });

    $stateProvider.state({
        name: 'tools',
        url: '/add-ons/tools',
        template: addOnsTemplate,
        controller: 'addons'
    });

    $stateProvider.state({
        name: 'free',
        url: '/add-ons/free',
        template: addOnsTemplate,
        controller: 'addons'
    });

    $stateProvider.state({
        name: 'payment',
        url: '/add-ons/payment',
        template: addOnsTemplate,
        controller: 'addons'
    });

    $urlRouterProvider.otherwise("/settings")
});

APP.controller( 'settings', ['$scope', '$http', '$state', '$sce', function($scope, $http, $state, $sce ) {
    var route = EXAMPLE.api.settings;
    var nonce = EXAMPLE.api.nonce;
    var url = route + '?_wpnonce=' + nonce;
    var $report = jQuery( '#report' );


    $http.get( url  ).then( function( r ){
        $scope.data = r.data;
    });

    $scope.save = function(){
        $report.html( '' );
        $http.post( url,
            {
                '_wpnonce' : nonce,
                'api_key': $scope.data.api_key,
                'enabled': $scope.data.enabled
            }).then( function( r ){
                $scope.data = r.data;
                $report.html( '<div class="alert alert-success" role="alert"><p>' + EXAMPLE.strings.saved + '</p>' );
        }, function( error ){
            var message;
            if( 'object' == typeof  error && 'object' == typeof  error.data && 'undefined' != typeof  error.data.message ){
                message = error.data.message;
            }else{
                message = EXAMPLE.strings.error;
            }
            $report.html( '<div class="alert alert-danger" role="alert"><p>' + message + '</p>' );
        });
    }


}]);

APP.controller( 'addons', ['$scope', 'addonsAPI', '$state', '$sce', function($scope, addonsAPI, $state, $sce ) {
    $scope.addons = addonsAPI.get( { type:$state.current.name } );
    $scope.trustAsHtml = $sce.trustAsHtml;
}]);


APP.factory( 'addonsAPI', function($resource){
    var api;
    if( 'object' == typeof CFADDONS && 'string' == typeof CFADDONS.api ){
        api = CFADDONS.api;
    }else{
        api = 'https://calderaforms.com/wp-json/calderawp_api/v2/products/cf-addons';
    }
    return $resource( api + '?category=:type', {
        type: 'all'
    });
});