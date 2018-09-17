(function () {
    'use strict';

    angular
        .module('app.studentProfile')
        .controller('StudentProfileController', studentProfileController);

    function studentProfileController($compile, $scope, $rootScope, $state, universityService, $transitions, $timeout, ModalService, userService, $cookies, alertService) {
        var vm = this;
        $scope.landingShow = false;
       	$scope.educations = [];
		$scope.experiences = [];
		$scope.languages = [];
		$scope.refCountry = [];
		$scope.documents = [];
	    activate();
        function activate() {
		    //Set to default Profile Picture...
		    if($scope.uploadPicture === undefined){
		   		$scope.uploadPicture = $rootScope.userData.img_profile_link;
		    }
		    
		    //Need to fetch user data if user already logged in
		    if ($rootScope.authenticated) {
				userService.getUserAccountData().then(function (response) {
				    if (response.status == 200){
					//$cookies.put('user_data', JSON.stringify(response.data.auth));
					$scope.studentData = response.data.auth;
					//$state.go("app.studentprofile");
				    }
				});
		    }
		    
		    // Get All Reference of Country list...
		    userService.getRefCountry().then(function (response) {
				if (response.status == 200 && response.data.status == true ){
				    $scope.refCountry = response.data.refCountry;
				}		
		    });
		    // Get Current student Educations Details...
		    userService.getEducations().then(function (response) {
				if (response.status == 200 && response.data.status == true ){
				    $scope.educations = response.data.educations;
				}		
		    });
		    
		    // Get Current student Experiences Details...
		    userService.getExperiences().then(function (response) {
				if (response.status == 200 && response.data.status == true ){
				    $scope.experiences = response.data.experiences;
				}		
		    });
		    
		    // Get Current student Languages Details....
		    userService.getLanguages().then(function (response) {
				if (response.status == 200 && response.data.status == true ){
				    $scope.languages = response.data.languages;
				}		
		    });
		    
		    // Get Current student Basic Documents with document type....
		    userService.getBasicDocuments().then(function (response) {
				if (response.status == 200 && response.data.status == true ){
				    $scope.documents = response.data.documents;
				}		
		    });
		    
		    if($rootScope.userType == 'student'){
		    	    
		    }else if($rootScope.userType == 'school'){
				$state.go("app.profile");
		    }else{
				$state.go("app.map");
		    }
        }
	
	
		/**** Save Education Detail For Student user ****/
		vm.saveEducation = function (education) {
			if(education.graduated == 'No'){
				if(education.confirm === undefined || education.confirm == false ){
					alert("Please confirm expected date of graduation.");
					return true;
				}
			}
		    $('#addEducation').modal('hide');
		    $('#editEducation').modal('hide');
		    userService.saveEducation(education).then(function (response) {
			if (response.status == 200 && response.data.status == true ){
			    $scope.educations = response.data.educations;
			    alertService.add("success", response.data.msg);
			}else{
			    alertService.add("danger", response.data.msg);
			}		
		    });
        }
	
		/**** Update Education Detail For Student user ****/
		vm.updateEducation = function (id) {
		    userService.getEducation(id).then(function (response) {
			if (response.status == 200 && response.data.status == true ){
			    $scope.education = response.data.education;
			    $scope.eductionFrom1.id = $scope.education.id;
			    $scope.eductionFrom1.period = $scope.education.period;
			    $scope.eductionFrom1.graduated = $scope.education.graduated;
			    if($scope.education.program == 'General Education'){
					$scope.eductionFrom1.programOption = 'General Education';
					$scope.education.program = '';
				}else{
					$scope.eductionFrom1.programOption = 'Other';	
					$scope.eductionFrom1.program = $scope.education.program;
				}
			    
			    $('#editEducation').modal('show');
			}		
		    });
        }
    
    	//Delete Eduction By Eduction id
		$scope.deleteEducation = function(education_id) {
			if(confirm('Are you sure delete Education Detail?')){
				userService.deleteEducation(education_id).then(function (response) {
					if (response.status == 200 && response.data.status == true ){
					    $scope.educations = response.data.educations;
					    alertService.add("success", response.data.msg);
					}else{
					    alertService.add("danger", response.data.msg);
					}		
			    });
			}
       	}    
	
		/**** Save Education Detail For Student user ****/
		vm.saveExperience = function (experience) {
		    $('#addExperience').modal('hide');
		    $('#editExperience').modal('hide');
		    userService.saveExperience(experience).then(function (response) {
			if (response.status == 200 && response.data.status == true ){
			    $scope.experiences = response.data.experiences;
			    alertService.add("success", response.data.msg);
			}else{
			    alertService.add("danger", response.data.msg);
			}		
		    });
        }
	
		/**** Update Experience Detail For Student user ****/
		vm.updateExperience = function (id) {
		    userService.getExperience(id).then(function (response) {
			if (response.status == 200 && response.data.status == true ){
			    $scope.experience = response.data.experience;
			    $scope.experienceForm1.id = $scope.experience.id;
			    $('#editExperience').modal('show');
			}		
		    });
        }
        
        //Delete Experience By Experience id
		$scope.deleteExperience = function(experience_id) {
			if(confirm('Are you sure delete experience Detail?')){
				userService.deleteExperience(experience_id).then(function (response) {
					if (response.status == 200 && response.data.status == true ){
					    $scope.experiences = response.data.experiences;
					    alertService.add("success", response.data.msg);
					}else{
					    alertService.add("danger", response.data.msg);
					}		
			    });
			}
       	} 
	
		/**** Save Language Detail For Student user ****/
		vm.saveLanguage = function (language) {
		    $('#addLanguage').modal('hide');
		    $('#editLanguage').modal('hide');
		    userService.saveLanguage(language).then(function (response) {
			if (response.status == 200 && response.data.status == true ){
			    $scope.languages = response.data.languages;
			    alertService.add("success", response.data.msg);
			}else{
			    alertService.add("danger", response.data.msg);
			}		
		    });
        }
	
		/**** Update Language Detail For Student user ****/
		vm.updateLanguage = function (id) {
		    userService.getLanguage(id).then(function (response) {
			if (response.status == 200 && response.data.status == true ){
			    $scope.language = response.data.language;
			    $scope.languageForm1.id = $scope.language.id;
			    $('#editLanguage').modal('show');
			}		
		    });
        }
        
       	//Delete Language By Language id
		$scope.deleteLanguage = function(languages_id) {
			if(confirm('Are you sure delete language Detail?')){
				userService.deleteLanguage(languages_id).then(function (response) {
					if (response.status == 200 && response.data.status == true ){
					    $scope.languages = response.data.languages;
					    alertService.add("success", response.data.msg);
					}else{
					    alertService.add("danger", response.data.msg);
					}		
			    });
			}
       	} 
       	
		// Check period selected date is past date the present date.
		$scope.checkDate = function() {
		    if($scope.eductionFrom.period){
				var fromMonth = $scope.eductionFrom.period.fromMonth;
				var fromYear = $scope.eductionFrom.period.fromYear;
				var toMonth = $scope.eductionFrom.period.toMonth;
				var toYear = $scope.eductionFrom.period.toYear;
				if(fromMonth && fromYear){
					var fromDate = new Date(fromYear+"-"+fromMonth+"-01");
				}
				if(toMonth && toYear){
					var toDate = new Date(toYear+"-"+toMonth+"-01");	
				}	
				var currentDate = new Date();
  				if(toDate > currentDate && fromDate < toDate){
					$scope.eductionFrom.graduated = 'No';
				}else{
					$scope.eductionFrom.graduated = 'Yes';
				}
			}
			if($scope.eductionFrom1.period){
				var fromMonth = $scope.eductionFrom1.period.fromMonth;
				var fromYear = $scope.eductionFrom1.period.fromYear;
				var toMonth = $scope.eductionFrom1.period.toMonth;
				var toYear = $scope.eductionFrom1.period.toYear;
				if(fromMonth && fromYear){
					var fromDate = new Date(fromYear+"-"+fromMonth+"-01");
				}
				if(toMonth && toYear){
					var toDate = new Date(toYear+"-"+toMonth+"-01");	
				}	
				var currentDate = new Date();
  				if(toDate > currentDate && fromDate < toDate){
					$scope.eductionFrom1.graduated = 'No';
				}else{
					$scope.eductionFrom1.graduated = 'Yes';
				}
			}    
		}
		
		// View Basic documents modal
		$scope.viewDocuments = function(type) {
			$scope.viewDocumentsType = type;
			$scope.documentFound = false;
			angular.forEach($scope.documents, function (val, key) {
			    if(val.document_type.name == type){
					$scope.documentFound = true; 	
				}
			});
			$('#viewDocuments').modal('show');			
		}
		
		// Delete Basic Document by document id
		$scope.deleteBasicDocument = function(id) {
			if(confirm('Are you sure delete this file?')){
				userService.deleteBasicDocument(id).then(function (response) {
					if (response.status == 200 && response.data.status == true ){
					    $scope.documents = response.data.documents;
					    alertService.add("success", response.data.msg);
					}else{
					    alertService.add("danger", response.data.msg);
					}		
			    });
			}
		}
		
    }
})();