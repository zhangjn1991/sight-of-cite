angular.module("sightApp")
.directive 'authorInputFormatter', () ->
        restrict: 'A',
        require: 'ngModel',
        link: (scope, element, attrs, ngModelController) -> 
            ngModelController.$formatters.push (modelValue)->                
                if modelValue? then _.pluck(modelValue,'name').join ', ' else ''

            ngModelController.$parsers.push (viewValue)->
                _.map _.filter(viewValue.split(', '), (s)->s.length > 0), (d)->{name:d}