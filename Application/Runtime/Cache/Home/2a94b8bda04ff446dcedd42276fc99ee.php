<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>起承</title>
	<script src="http://cdn.static.runoob.com/libs/angular.js/1.4.6/angular.min.js"></script>
  </head>
  <body ng-app="myApp" ng-controller="myCtrl">
	<form>
  <div class="form-group">
    <textarea class="form-control" rows="3" ng-model="content"></textarea>
  </div>
  
  <button type="submit" class="btn btn-default" ng-click="send()">Submit</button>
</form>

 </body>
 <script>
var app = angular.module('myApp', []);
app.config(function ($httpProvider) {
	$httpProvider.defaults.headers.put['Content-Type'] = 'application/x-www-form-urlencoded';
	$httpProvider.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded';
	// Override $http service's default transformRequest
	$httpProvider.defaults.transformRequest = [function (data) {
		/**
		 * The workhorse; converts an object to x-www-form-urlencoded serialization.
		 * @param {Object} obj
		 * @return {String}
		 */
		var param = function (obj) {
			var query = '';
			var name, value, fullSubName, subName, subValue, innerObj, i;
			for (name in obj) {
				value = obj[name];
				if (value instanceof Array) {
					for (i = 0; i < value.length; ++i) {
						subValue = value[i];
						fullSubName = name + '[]';
						innerObj = {};
						innerObj[fullSubName] = subValue;
						query += param(innerObj) + '&';
					}
				} else if (value instanceof Object) {
					for (subName in value) {
						subValue = value[subName];
						fullSubName = subName;
						innerObj = {};
						innerObj[fullSubName] = subValue;
						query += param(innerObj) + '&';
					}
				} else if (value !== undefined && value !== null) {
					query += encodeURIComponent(name) + '='
						+ encodeURIComponent(value) + '&';
				}
			}
			return query.length ? query.substr(0, query.length - 1) : query;
		};
		return angular.isObject(data) && String(data) !== '[object File]'
			? param(data)
			: data;
	}];
});
app.controller('myCtrl', function($scope,$http) {
    var data = {"sponsorId":1,"tags":[8,9],"time":1878798799};
	$scope.send = function(){
    	$http.post(
    	"http://192.168.31.207/~mile/qicheng/index.php/Api/Tale/newTale",
    	data
    	).then(function(res){
    		alert(res.status);
    	},function(){
    		
    	});
    }
});
</script>
</html>