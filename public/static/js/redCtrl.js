app.controller("redCtrl",function($scope,$timeout){
	$scope.appearClass=true;
	$scope.isbefore=true;
	$scope.ishide=true;
	$scope.mask = true;  
	
	$scope.open_btn = function (){
		$timeout(function(){
	    $scope.appearClass = false;
	    $scope.isafter=true;
	    $scope.mask = false;
	},3000);
	}
});
