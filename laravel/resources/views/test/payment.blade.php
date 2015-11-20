<!doctype html>
<html lang="pt-br" ng-app="payments">
<head>
    <meta charset="UTF-8">
    <title>My HTML File</title>
</head>
    <body>

        <div ng-controller="ProductListCtrl as ProductListCtrl">
            <div ng-repeat="(key, product) in products">
                <h3>Quantity:
                    <input name="@{{'product'+product.id}}"
                           ng-init="product.quantity = 0"
                           ng-model="product.quantity"
                           ng-change="setTotal()"
                           type="text"
                           placeholder="Quantity"
                           value="0"
                           style="width: 20px"
                    >
                    name: @{{product.name}}
                    price: @{{product.price}}
                </h3>
            </div>

            <br>

            <h2>total value: @{{totalValue | currency}}</h2>

            <br>

            <button ng-click="buy()">Buy</button>
        </div>

        <script src="build/js/vendor/angular.min.js"></script>
        <script type="text/javascript" src="{!!asset('build/js/payments/payMain.js')!!}"></script>

        <script>
            app = angular.module('payments', []);

            baseUrl = window.location.href.split('/');
            baseUrl.pop();
            baseUrl = baseUrl.join('/');

            app.controller('ProductListCtrl', ['$scope', '$http', function ($scope, $http) {
                $scope.products = [];
                $scope.totalValue = 0;

                $http({
                    method: 'GET',
                    url: baseUrl+'/products/info'
                }).then(function successCallback(response) {
                    $scope.products = response.data;
                }, function errorCallback(response) {
                    console.log('ajax error')
                });

                $scope.setTotal = function(){
                    $scope.totalValue = 0;
                    $scope.products.forEach(function(product){
                        $scope.totalValue += (product.quantity)*product.price;
                    });
                };

                $scope.buy = function(){
                    data = {
                        items: [],
                        transactionDescription: "Compra de cores da casa da vovo",
                        callback: 'buyCallback'
                    };
                    $scope.products.forEach(function(product){
                        if (product.quantity != 0){
                            data.items.push({
                                id: product.id,
                                quantity: product.quantity
                            });
                        }
                    });

                    getPaymentPage(data);
                };

            }]);

            function buyCallback(response){
                console.log(response);
            }
        </script>
    </body>
</html>