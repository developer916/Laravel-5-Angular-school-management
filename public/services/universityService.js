(function () {
    'use strict';

    angular
        .module('app')
        .factory('universityService', universityService);

    function universityService($http, $q, $filter, CSRF_TOKEN) {

        var listUniversities = [];
        var filteredUniversities = [];

        return {
            getAll: getAll,
            getFilteredUniversities: getFilteredUniversities,
            setUniversitiesFilters: setUniversitiesFilters,
            getUniversitiesCountryList: getUniversitiesCountryList,
            getUniversitiesDiplomaList: getUniversitiesDiplomaList,
            getUniversitiesSpecialityList: getUniversitiesSpecialityList,
            searchUniversities: searchUniversities,
            filterUniversitiesByPrice: filterUniversitiesByPrice,
            filterUniversitiesByIcons: filterUniversitiesByIcons,
            getUniversity: getUniversity,
            saveProfile: saveProfile,
            getUniversityProfile: getUniversityProfile,
            getUniversityById: getUniversityById,
            getAmenities: getAmenities,
            saveHousing: saveHousing,
            getCampusById: getCampusById,
            deleteHousing: deleteHousing,
            getCampuses: getCampuses,
            getDiplomaStudy: getDiplomaStudy,
            getDiplomaLevelStudy: getDiplomaLevelStudy,
            getRefLanguage: getRefLanguage,
            saveProgram: saveProgram,
            getProgramById: getProgramById,
            deleteProgram: deleteProgram,
            copyProgram: copyProgram,
            getPrograms: getPrograms,
            getAllPrograms: getAllPrograms,
            getAllSpeciality: getAllSpeciality,
            getUniversities: getUniversities,
            saveFrontpage: saveFrontpage,
            deleteUniversityImages: deleteUniversityImages,
            deleteCampusRoomImages: deleteCampusRoomImages,
            deleteCampusDormImages: deleteCampusDormImages,
            deleteCampusImages: deleteCampusImages,     
        };

        /**
         * @returns all universities
         */
        //function getAll() {
        //    return $http.get('json/universities.json').then(function(response) {
        //        listUniversities = response.data.data;
        //        return response.data;
        //    }, function(error) {
        //        return $q.reject(error.data);
        //    });
        //}
        
        function getAll() {
            return $http.get('getUniversities').then(function(response) {
                listUniversities = response.data.universities;
                return response.data;
            }, function(error) {
                return $q.reject(error.data);
            });
        }

        /**
         * @returns Array filtered universities
         */
        function getFilteredUniversities() {
            return filteredUniversities;
        }

        /**
         * Set universities filters for map
         */
        function setUniversitiesFilters(filters) {
            var deferred = $q.defer();
            filteredUniversities = filters ? $filter('filter')(listUniversities, filters) : listUniversities;
            deferred.resolve(filteredUniversities);
            return deferred.promise;
        }
        
        /**
         * Search universities filters on map
         */
        function searchUniversities(data) {
            return $http.post('searchUniversities', {
            		data: data,
                    _token: CSRF_TOKEN,
            	}).then(function (results) {
            		return results;
        	});
        }
        
        /**
         * filter universities price by semester on map
         */
        function filterUniversitiesByPrice(data) {
            return $http.post('filterUniversitiesByPrice', {
            		data: data,
                    _token: CSRF_TOKEN,
            	}).then(function (results) {
            		return results;
        	});
        }
        
        /**
         * filter universities by Icons on map
         */
        function filterUniversitiesByIcons(data) {
            return $http.post('filterUniversitiesByIcons', {
            		data: data,
                    _token: CSRF_TOKEN,
            	}).then(function (results) {
            		return results;
        	});
        }

        /**
         *
         * @param id university
         * @returns object university by id
         */
        function getUniversity(id) {
            var deferred = $q.defer();
            var university;
            if (listUniversities.length === 0){
                getAll().then(function () {
                    university = $filter('filter')(listUniversities, {id: id}, true);
                    deferred.resolve({data: university[0]});
                });
            } else{
                university = $filter('filter')(listUniversities, {id: id}, true);
                deferred.resolve({data: university[0]});
            }

            return deferred.promise;
        }

        /**
         *
         * @returns Array List of universities's countries available
         */
        function getUniversitiesCountryList() {
            return listUniversities.map(function(university) {return university.address.country});
        }

        /**
         *
         * @returns Array List of universities's diplomas available
         */
        function getUniversitiesDiplomaList() {
            var diplomas = [];
            for(var i = 0; i < listUniversities.length; i++){
                diplomas = listUniversities[i].diplomas.map(function(diploma) {return diploma.name});
            }
            return diplomas;
        }

        /**
         *
         * @returns Array List of universities's specialities available
         */
        function getUniversitiesSpecialityList() {
            var specialities = [];
            for(var i = 0; i < listUniversities.length; i++){
                specialities = listUniversities[i].diplomas.map(function(speciality) {return speciality.speciality});
            }
            return specialities;
        }
        
        
        /**
         * @returns Array list of universities....
         */
        function getUniversities() {
            return $http.get('getUniversities', {
            		_token: CSRF_TOKEN,
            	}).then(function (results) {
            		return results;
        	});
        }
        
        /**
         * @returns Array list of university Profile ....
         */
        function getUniversityProfile() {
            return $http.get('getUniversity', {
            		_token: CSRF_TOKEN,
            	}).then(function (results) {
            		return results;
        	});
        }
        
        /**
         * @returns Array university ....
         */
        function getUniversityById(id) {
            return $http.get('api/getUniversityById/'+id, {
                       _token: CSRF_TOKEN,
            	}).then(function (results) {
            		return results;
        	});
        }
        
        /**
         * @returns Array list of universities Profile ....
         */
        function saveProfile(profile,step) {
            return $http.post('saveProfile', {
            		data: profile,
                        step: step,
            		//email: user_email,
			_token: CSRF_TOKEN,
            	}).then(function (results) {
            		return results;
        	});
        }
        
        /**
         * Get Campus By Campus id ..
         * @returns Array Campus ....
         */
        function getCampusById(id) {
            return $http.get('api/getCampusById/'+id, {
                       _token: CSRF_TOKEN,
            	}).then(function (results) {
            		return results;
        	});
        }
        
        /**
         * Delete Campus By Campus id ..
         * @returns Array Campus ....
         */
        function deleteHousing(id) {
            return $http.get('api/deleteHousing/'+id, {
                       _token: CSRF_TOKEN,
            	}).then(function (results) {
            		return results;
        	});
        }
        
        /**
         * @returns get all Campuses....
         */
        function getCampuses() {
            return $http.get('getCampuses', {
            		_token: CSRF_TOKEN,
            	}).then(function (results) {
            		return results;
        	});
        }
        
        /**
         * @returns get all Amenities....
         */
        function getAmenities() {
            return $http.get('getAmenities', {
            		_token: CSRF_TOKEN,
            	}).then(function (results) {
            		return results;
        	});
        }        
        
        /**
         * @returns Array list of universities Housing ....
         */
        function saveHousing(data) {
            return $http.post('saveHousing', {
            		data: data,
                       _token: CSRF_TOKEN,
            	}).then(function (results) {
            		return results;
        	});
        }
        
        /**
         * Get Program By Program id ..
         * @returns Array Program ....
         */
        function getProgramById(id) {
            return $http.get('api/getProgramById/'+id, {
                       _token: CSRF_TOKEN,
            	}).then(function (results) {
            		return results;
        	});
        }
        
        /**
         * Delete Program By Program id ..
         * @returns Array Program ....
         */
        function deleteProgram(id) {
            return $http.get('api/deleteProgram/'+id, {
                       _token: CSRF_TOKEN,
            	}).then(function (results) {
            		return results;
        	});
        }
        
        /**
         * Copy Program By Program id ..
         * @returns Array Program ....
         */
        function copyProgram(id) {
            return $http.get('api/copyProgram/'+id, {
                       _token: CSRF_TOKEN,
            	}).then(function (results) {
            		return results;
        	});
        }
        
        /**
         * @returns get all Programs by current user login....
         */
        function getPrograms() {
            return $http.get('getPrograms', {
            		_token: CSRF_TOKEN,
            	}).then(function (results) {
            		return results;
        	});
        }
        
        /**
         * @returns get all Programs....
         */
        function getAllPrograms() {
            return $http.get('getAllPrograms', {
            		_token: CSRF_TOKEN,
            	}).then(function (results) {
            		return results;
        	});
        }
        
        /**
         * @returns get all Speciality of Programs(Diploma)....
         */
        function getAllSpeciality() {
            return $http.get('getAllSpeciality', {
            		_token: CSRF_TOKEN,
            	}).then(function (results) {
            		return results;
        	});
        }
        
        /**
         * @returns get all Diploma Study ....
         */
        function getDiplomaStudy() {
            return $http.get('getDiplomaStudy', {
            		_token: CSRF_TOKEN,
            	}).then(function (results) {
            		return results;
        	});
        }
        
        /**
         * @returns get all Diploma level of Study ....
         */
        function getDiplomaLevelStudy() {
            return $http.get('getDiplomaLevelStudy', {
            		_token: CSRF_TOKEN,
            	}).then(function (results) {
            		return results;
        	});
        }
        
        /**
         * @returns get all Reference Language ....
         */
        function getRefLanguage() {
            return $http.get('getRefLanguage', {
            		_token: CSRF_TOKEN,
            	}).then(function (results) {
            		return results;
        	});
        }
        
        /**
         * @returns Array list of universities Programs ....
         */
        function saveProgram(data) {
            return $http.post('saveProgram', {
            		data: data,
                       _token: CSRF_TOKEN,
            	}).then(function (results) {
            		return results;
        	});
        }
        
        /**
         * @returns Array list of universities Front page ....
         */
        function saveFrontpage(data) {
            return $http.post('saveFrontpage', {
            		data: data,
                       _token: CSRF_TOKEN,
            	}).then(function (results) {
            		return results;
        	});
        }
        
        /**
         * @returns remove University picture with folder and database ....
         */
        function deleteUniversityImages(fileName, imageId, type) {
            return $http.post('deleteUniversityImages', {
            			fileName: fileName,
                        imageId: imageId,
                        type: type,
                       _token: CSRF_TOKEN,
            	}).then(function (results) {
            		return results;
        	});
        }
        
        /**
         * @returns remove Campus Room picture with folder and database ....
         */
        function deleteCampusRoomImages(fileName, imageId, type, campusRoomTypeId) {
            return $http.post('deleteCampusRoomImages', {
            			fileName: fileName,
                        imageId: imageId,
                        type: type,
                        campusRoomTypeId: campusRoomTypeId,
                       _token: CSRF_TOKEN,
            	}).then(function (results) {
            		return results;
        	});
        }
        
        /**
         * @returns remove Campus Dorm picture with folder and database ....
         */
        function deleteCampusDormImages(fileName, imageId, type, campusId) {
            return $http.post('deleteCampusDormImages', {
            			fileName: fileName,
                        imageId: imageId,
                        type: type,
                        campusId: campusId,
                       _token: CSRF_TOKEN,
            	}).then(function (results) {
            		return results;
        	});
        }
        
        /**
         * @returns remove Campus picture with folder and database ....
         */
        function deleteCampusImages(fileName, imageId, type, campusId) {
            return $http.post('deleteCampusImages', {
            			fileName: fileName,
                        imageId: imageId,
                        type: type,
                        campusId: campusId,
                       _token: CSRF_TOKEN,
            	}).then(function (results) {
            		return results;
        	});
        }
    }
})();