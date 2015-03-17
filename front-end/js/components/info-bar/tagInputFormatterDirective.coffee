angular.module("sightApp")
.directive 'tagInputFormatter', () ->
        restrict: 'A',
        require: 'ngModel',
        link: (scope, element, attrs, ngModelController) -> 
            ngModelController.$formatters.push (modelValue)->                
                if modelValue? then modelValue.join ', ' else ''

            ngModelController.$parsers.push (viewValue)->
                _.filter viewValue.split(', '), (s)->s.length > 0