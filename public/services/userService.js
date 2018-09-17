(function () {
    'use strict';

    angular
        .module('app')
        .factory('userService', userService);

    function userService($http, $q, CSRF_TOKEN) {

        var user = null;

        return {
            getUser: getUser,
            DoLogin: DoLogin,
            getUserData: getUserData,
	    getUserAccountData: getUserAccountData,
            SignUp: SignUp,
            saveProfile: saveProfile,
	    updatePassword: updatePassword,
	    updateAccount: updateAccount,
	    getRefCountry: getRefCountry,
	    getEducations: getEducations,
	    getEducation: getEducation,
	    getEducationsByStudentId: getEducationsByStudentId,
	    saveEducation: saveEducation,
	    deleteEducation: deleteEducation,
	    getExperiences: getExperiences,
	    getExperience: getExperience,
	    getExperiencesByStudentId: getExperiencesByStudentId,
	    saveExperience: saveExperience,
	    deleteExperience: deleteExperience,
	    getLanguages: getLanguages,
	    getLanguage: getLanguage,
	    getLanguagesByStudentId: getLanguagesByStudentId,
	    saveLanguage: saveLanguage,
	    deleteLanguage: deleteLanguage,
	    getBasicDocuments: getBasicDocuments,
	    deleteBasicDocument: deleteBasicDocument,
	    applyApplication: applyApplication,
	    getApplications: getApplications,
	    getApplicationsSortByStatus: getApplicationsSortByStatus,
	    getApplicationsFilterByStatus: getApplicationsFilterByStatus,
	    getApplicationsSearchByKeyword: getApplicationsSearchByKeyword,
	    getApplicationById: getApplicationById,
	    updateApplicationStatus: updateApplicationStatus,
	    getUpdateStatusTrackNotification: getUpdateStatusTrackNotification,
	    getDocumentTypes: getDocumentTypes,
	    deleteApplications: deleteApplications,
        };

        /**
         * @returns current user
         */
        function getUser() {
            return !user ? $http.get('json/user.json').then(function(response) {
                user = response.data.data;
                return response.data;
            }, function(error) {
                return $q.reject(error.data);
            }) : user;
        }
        
        /**
         * @returns current user
         */
        function getUserData(user_email) {
            return $http.post('getUserData',{
			email: user_email,
			_token: CSRF_TOKEN,
		    }).then(function (results) {
			return results;
		    });
        }
	
		/**
         * @returns current user with Account Data
         */
        function getUserAccountData() {
            return $http.post('getUserAccountData',{
			//email: user_email,
			_token: CSRF_TOKEN,
		    }).then(function (results) {
			return results;
		    });
        }
	
        
        /**
         * @returns Check user login or not
         */
        function DoLogin(customer) {
            return $http.post('login', {
			email: customer.email,
			password: customer.password,
			_token: CSRF_TOKEN,
		    }).then(function (results) {
			//$http.toast(results);
			return results;
		    });
        }
        
        /**
         * @returns Check user login or not
         */
        function SignUp(customer) {
            return $http.post('register', {
            		email: customer.email,
            		password: customer.password,
            		confirmed: customer.password2,
            		name: customer.name,
            		type: customer.type,
			_token: CSRF_TOKEN,
        	    }).then(function (results) {
            		//$http.toast(results);
            		return results;
        	    });
        }
        
        /**
         * @returns Save School Profile ....
         */
        function saveProfile(user_email,profile) {
            return $http.post('saveProfile', {
            		data:profile,
            		email: user_email,
			_token: CSRF_TOKEN,
            	}).then(function (results) {
            		return results;
        	});
        }
	
		/**
         * @returns Update Password for current user login....
         */
        function updatePassword(customer) {
            return $http.post('updatePassword', {
            		password: customer.old_password,
			new_password: customer.password,
            		confirmed: customer.password2,
			_token: CSRF_TOKEN,
            	}).then(function (results) {
            		return results;
        	});
        }
	
		/**
         * @returns Update Account Detail for current user login....
         */
        function updateAccount(customer) {
            return $http.post('updateAccount', {
            		firstname: customer.firstname,
					lastname: customer.lastname,
            		email: customer.email,
					phone: customer.phone,
					Bdate: customer.Bdate,
					address: customer.address,
					ref_country_id: customer.ref_country_id,
					_token: CSRF_TOKEN,
            	}).then(function (results) {
            		return results;
        	});
        }
	
		/**
         * @returns Get Reference of country....
         */
        function getRefCountry() {
            return $http.get('getRefCountry',{
			_token: CSRF_TOKEN,
		    }).then(function (results) {
			return results;
		    });
        }
	
		/**
         * @returns Get Educations Detail....
         */
        function getEducations() {
            return $http.get('getEducations',{
			_token: CSRF_TOKEN,
		    }).then(function (results) {
			return results;
		    });
        }
	
		/**
         * @returns Get Educations Detail By education id....
         */
        function getEducation(id) {
            return $http.get('api/getEducation/'+id,{
			_token: CSRF_TOKEN,
		    }).then(function (results) {
			return results;
		    });
        }
	
		/**
         * @returns Get Educations Detail By Student Id....
         */
        function getEducationsByStudentId(id) {
            return $http.get('api/getEducationsByStudentId/'+id,{
			_token: CSRF_TOKEN,
		    }).then(function (results) {
			return results;
		    });
        }
	
	
		/**
         * @returns Save Education Detail for current user login....
         */
        function saveEducation(education) {
            return $http.post('saveEducation', {
            		data: education,
			_token: CSRF_TOKEN,
            	}).then(function (results) {
            		return results;
        	});
        }
        
        /**
         * @returns Delete Education Detail By Education Id....
         */
        function deleteEducation(id) {
            return $http.get('api/deleteEducation/'+id,{
				_token: CSRF_TOKEN,
		    }).then(function (results) {
				return results;
		    });
        }
	
		/**
         * @returns Get Experiences Detail....
         */
        function getExperiences() {
            return $http.get('getExperiences',{
			_token: CSRF_TOKEN,
		    }).then(function (results) {
			return results;
		    });
        }
	
		/**
         * @returns Get Experience Detail By experience id....
         */
        function getExperience(id) {
            return $http.get('api/getExperience/'+id,{
			_token: CSRF_TOKEN,
		    }).then(function (results) {
			return results;
		    });
        }
	
		/**
         * @returns Get Experiences Detail By student id....
         */
        function getExperiencesByStudentId(id) {
            return $http.get('api/getExperiencesByStudentId/'+id,{
			_token: CSRF_TOKEN,
		    }).then(function (results) {
			return results;
		    });
        }
	
		/**
         * @returns Save Experience Detail for current user login....
         */
        function saveExperience(experience) {
            return $http.post('saveExperience', {
            		data: experience,
			_token: CSRF_TOKEN,
            	}).then(function (results) {
            		return results;
        	});
        }
        
      	/**
         * @returns Delete Experience Detail By Experience Id....
         */
        function deleteExperience(id) {
            return $http.get('api/deleteExperience/'+id,{
				_token: CSRF_TOKEN,
		    }).then(function (results) {
				return results;
		    });
        }
        
		/**
         * @returns Get Languages Detail....
         */
        function getLanguages() {
            return $http.get('getLanguages',{
			_token: CSRF_TOKEN,
		    }).then(function (results) {
			return results;
		    });
        }
	
		/**
         * @returns Get Language Detail By language id....
         */
        function getLanguage(id) {
            return $http.get('api/getLanguage/'+id,{
			_token: CSRF_TOKEN,
		    }).then(function (results) {
			return results;
		    });
        }
	
		/**
         * @returns Get Languages Detail By student Id....
         */
        function getLanguagesByStudentId(id) {
            return $http.get('api/getLanguagesByStudentId/'+id,{
			_token: CSRF_TOKEN,
		    }).then(function (results) {
			return results;
		    });
        }
	
		/**
         * @returns Save Language Detail for current user login....
         */
        function saveLanguage(language) {
            return $http.post('saveLanguage', {
            		data: language,
			_token: CSRF_TOKEN,
            	}).then(function (results) {
            		return results;
        	});
        }
        
        /**
         * @returns Delete Language Detail By Language Id....
         */
        function deleteLanguage(id) {
            return $http.get('api/deleteLanguage/'+id,{
				_token: CSRF_TOKEN,
		    }).then(function (results) {
				return results;
		    });
        }
        
        /**
         * @returns Get uploaded Basic Documents With document type....
         */
        function getBasicDocuments() {
            return $http.get('getBasicDocuments',{
				_token: CSRF_TOKEN,
		    }).then(function (results) {
				return results;
		    });
        }
		
		/**
         * @returns Delete Basic Document By Document Id....
         */
        function deleteBasicDocument(id) {
            return $http.get('api/deleteBasicDocument/'+id,{
				_token: CSRF_TOKEN,
		    }).then(function (results) {
				return results;
		    });
        }
        
		/**
         * @returns Apply Application by student of current user login....
         */
        function applyApplication(data) {
            return $http.post('applyApplication', {
            		data: data,
			_token: CSRF_TOKEN,
            	}).then(function (results) {
            		return results;
        	});
        }
	
		/**
         * @returns Get Applications list....
         */
        function getApplications() {
            return $http.get('getApplications',{
			_token: CSRF_TOKEN,
		    }).then(function (results) {
			return results;
		    });
        }
   	
		/**
         * @returns Get Applications list sort by Update status....
         */
        function getApplicationsSortByStatus(status) {
            return $http.get('api/getApplicationsSortByStatus/'+status,{
			_token: CSRF_TOKEN,
		    }).then(function (results) {
			return results;
		    });
        }
    
    	/**
         * @returns Get Applications list sort by Update status....
         */
        function getApplicationsFilterByStatus(status, isAllSelected) {
            return $http.post('getApplicationsFilterByStatus',{
            	status: status,
            	isAllSelected: isAllSelected,
				_token: CSRF_TOKEN,
		    }).then(function (results) {
				return results;
		    });
        }
    	
    	/**
         * @returns Get Applications list search by keyword....
         */
        function getApplicationsSearchByKeyword(keyword) {
            return $http.post('api/getApplicationsSearchByKeyword',{
            	keyword:keyword,
				_token: CSRF_TOKEN,
		    }).then(function (results) {
				return results;
		    });
        }     
	
		/**
         * @returns Array Application by application id ....
         */
        function getApplicationById(id) {
            return $http.get('api/getApplicationById/'+id, {
                       _token: CSRF_TOKEN,
            	}).then(function (results) {
            		return results;
        	});
        }
	
	
		/**
         * @returns Update Application status by current user login....
         */
        function updateApplicationStatus(application_id, status, page, document) {
	    if (page === undefined){ page = false;}
	    if (document === undefined){ document = {};}
	    
            return $http.post('updateApplicationStatus', {
            		application_id: application_id,
			status: status,
			page: page,
			document: document,
			_token: CSRF_TOKEN,
            	}).then(function (results) {
            		return results;
        	});
        }
	
		/**
         * @returns Get TMAP Notification Update Status ....
         */
        function getUpdateStatusTrackNotification() {
            return $http.get('getUpdateStatusTrackNotification',{
			_token: CSRF_TOKEN,
		    }).then(function (results) {
			return results;
		    });
        }
	
		/**
         * @returns Get Document Type list....
         */
        function getDocumentTypes() {
            return $http.get('getDocumentTypes',{
			_token: CSRF_TOKEN,
		    }).then(function (results) {
			return results;
		    });
        }
        
        /** 
    	 * Delete Application using selected app by school user
         * @returns Mixed Appplication Data....
         */
        function deleteApplications(data) {
            return $http.post('deleteApplications', {
            		data: data,
					_token: CSRF_TOKEN,
            	}).then(function (results) {
            		return results;
        	});
        }
    }
    
})();