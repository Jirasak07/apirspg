angular.module('scotchApp', [])
.controller('MainUsercontroller', function($scope,$window,$http) {
    $scope.UserID = $window.localStorage.getItem('UserID');
    
    $scope.UserIDCreck = $window.localStorage.getItem('UserIDCreck');
    
    if($scope.UserID == null){
        $window.location.href = 'login';
    } else if($scope.UserIDCreck != null){
        $scope.UserID = $window.localStorage.getItem('UserIDCreck');
    }
    
    $scope.loadingpages = function(){
        $http.get('dashboard_profile/profile/'+$scope.UserID)
            .success(function(response){
                     $scope.datafrom = {};
                     $scope.profiledashborad = response;  
                     $scope.datafrom.ID_LOGIN = $scope.profiledashborad[0].ID_LOGIN;
                     $scope.datafrom.PREFIX_LOGIN = $scope.profiledashborad[0].PREFIX_LOGIN;
                     $scope.datafrom.FRISTNAME_LOGIN = $scope.profiledashborad[0].FRISTNAME_LOGIN;
                     $scope.datafrom.MIDNAME_LOGIN = $scope.profiledashborad[0].MIDNAME_LOGIN;
                     $scope.datafrom.LASTNAME_LOGIN = $scope.profiledashborad[0].LASTNAME_LOGIN;
                     $scope.datafrom.CITIZENT_LOGIN = $scope.profiledashborad[0].CITIZENT_LOGIN;
                     $scope.datafrom.TELHOME_LOGIN = $scope.profiledashborad[0].TELHOME_LOGIN;
                     $scope.datafrom.TELMOBILE_LOGIN = $scope.profiledashborad[0].TELMOBILE_LOGIN;
                     $scope.datafrom.FACEBOOK_LOGIN = $scope.profiledashborad[0].FACEBOOK_LOGIN;
                     $scope.datafrom.LINE_LOGIN = $scope.profiledashborad[0].LINE_LOGIN;
                     $scope.datafrom.GENDER_LOGIN = $scope.profiledashborad[0].GENDER_LOGIN;
                     $scope.datafrom.BDATE_LOGIN = $scope.profiledashborad[0].BDATE_LOGIN;
                     $scope.datafrom.UPLOAD_LOGIN = $scope.profiledashborad[0].UPLOAD_LOGIN;
                    $("#BDATE_LOGIN").val($scope.profiledashborad[0].BDATE_LOGIN);
                     
                     
            })
            .error(function(e){

            });
    } 
    $scope.loadingpages();
})

.controller('Maincontroller', function($scope,$window,$http) {
    $scope.UserID = $window.localStorage.getItem('UserID');
    
    $scope.UserIDCreck = $window.localStorage.getItem('UserIDCreck');
    
    if($scope.UserID == null){
        $window.location.href = 'login';
    } else if($scope.UserIDCreck != null){
        $scope.UserID = $window.localStorage.getItem('UserIDCreck');
    }

        $http.get('dashboard_printregistrar2018/profile/'+$scope.UserID)
            .success(function(response){
                $scope.dataProfile = response;
            })
            .error(function(e){

            });
    

})

