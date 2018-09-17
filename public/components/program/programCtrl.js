(function () {
    'use strict';

    angular
        .module('app.program')
        .controller('ProgramController', programController);

    function programController($compile, $scope, $rootScope, $state, universityService, $transitions, $timeout, ModalService, userService, alertService, $cookies, $stateParams) {
        var vm = this;
        $scope.landingShow = false;
        $scope.refLanguage = [];
		$scope.program = { level_of_study: 1};
		
		activate();
		function activate() {

		    // Get diploma study list...
		    universityService.getDiplomaStudy().then(function (response) {
			if (response.status == 200 && response.data.status == true ){
			    $scope.diplomaStudy = response.data.diplomaStudy;
			}		
		    });
		    
		    // Get diploma level of study list...
		    universityService.getDiplomaLevelStudy().then(function (response) {
			if (response.status == 200 && response.data.status == true ){
			    $scope.diplomaLevelStudy = response.data.diplomaLevelStudy;
			}		
		    });
		    
		    // Get Reference of languages...
		    universityService.getRefLanguage().then(function (response) {
			if (response.status == 200 && response.data.status == true ){
			    $scope.refLanguage = response.data.language;
			}		
		    });
		    
		    // Get Program Details By program id...
		    if($stateParams.programId !== undefined){
		    	universityService.getProgramById($stateParams.programId).then(function (response) {
					if(response.status == 200 && response.data.status == true ){
						//$scope.program = response.data.program;
						$scope.program.id = response.data.program.id;
						$scope.program.diploma_study_id = response.data.program.diploma_study_id;
						$scope.program.level_of_study = response.data.program.diploma_level_study_id;
						$scope.program.nameBest = response.data.program.nameBest;
						$scope.program.nameIn = response.data.program.nameIn;
						$scope.program.diploma_specialization_id = response.data.program.diploma_specialization_id;
						$scope.program.specialization = response.data.program.specialization;
						$scope.program.ref_language = response.data.program.ref_language_id;
						$scope.program.price = response.data.program.price;
						$scope.program.numberOfSemeter = response.data.program.numberOfSemeter;
						$scope.program.semester = response.data.program.diplomaSemesters;
						$scope.requirements = response.data.program.diplomaRequirements.length;
						$scope.program.otherDocuments = response.data.program.diplomaRequirements;
						//$scope.file = response.data.program.diplomaRequirements[0].document_required;
						//$scope.program.universitiesDiplomaGrades = response.data.program.universitiesDiplomaGrades;
						//$scope.program.universitiesDiplomaGradesDiplomaRequirements = response.data.program.universitiesDiplomaGradesDiplomaRequirements;
						$scope.program.description = response.data.program.description;
					}		
			    });
			}
		    
		    if($rootScope.userType == 'student'){
		    	    
		    }else if($rootScope.userType == 'school'){
				$state.go("app.program");
		    }else{
				$state.go("app.map");
		    }
		}
	
		/**** Save Program Detail ****/
		vm.saveProgram = function(program) {
		    universityService.saveProgram(program).then(function (response) {
				if (response.status == 200 && response.data.status == true ){
				    $state.go("app.programList");
				    alertService.add("success", response.data.msg);
				}else{
				    alertService.add("danger", response.data.msg);
				}		
		    });
        }
        
        // Go to Program List Page...
        vm.cancelProgram= function(program) {
			$state.go("app.programList");
		}
		
		//Add new text box of requirements...
		$scope.requirements = 1;
		$scope.addRequirements = function() {
		    $scope.requirements++;
		}
    }
})();