// Generated by CoffeeScript 1.7.1
angular.module('sightApp').filter('authorCellFilter', function() {
  return function(input) {
    return _.pluck(input, 'name').join(', ');
  };
});
